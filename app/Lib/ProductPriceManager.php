<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\ProductVariant;

/**
 * Class ProductPriceManager
 *
 * This class is responsible for managing product-price related operations.
 */
class ProductPriceManager {

    /**
     * Get the prices for a product or variant.
     *
     * @param object $product The product.
     * @param object|null $variant The variant (optional).
     *
     * @return object Prices for the product or variant.
     */
    public function getProductPrices($product, $variant = null) {
        // If a variant is provided, use its prices; otherwise, use main product prices
        if ($variant) {
            $regularPrice = $variant->regular_price;
            $salePrice    = $this->getOnSalePrice($variant);
        } else {
            $regularPrice = $product->regular_price;
            $salePrice    = $this->getOnSalePrice($product);
        }

        // Return an object containing both regular and sale prices
        return (object) ['regular_price' => $regularPrice, 'sale_price' => $salePrice];
    }

    /**
     * Get the sale price for an item based on its sale start and end dates.
     *
     * @param object $item The item for which to determine the sale price.
     * @return float|null The sale price of the item, or null if no sale price is set.
     */
    public function getOnSalePrice($item) {

        if (!$item->regular_price) {
            return null;
        }

        $activeOffer = $item instanceof ProductVariant ? $item->product?->activeOffer : $item->activeOffer;

        if ($activeOffer) {
            $discount = $activeOffer->discountAmount($item->regular_price);
            return $item->regular_price - $discount;
        }

        if (is_null($item->sale_price)) {
            return $item->regular_price;
        }

        // Check if the sale is still valid based on the expiry time
        if (productManager()->checkSaleExpiryTime($item)) {
            return $item->sale_price;
        }

        // If the sale has expired, return the regular price
        return $item->regular_price;
    }

    /**
     * Calculate the price range for a product based on its variants.
     *
     * @param object $product The product for which to calculate the price range.
     *
     * @return object|null The calculated price range (min_price and max_price), or null if not applicable.
     */
    public function calculatePriceRange($product) {
        // Check if the product type is variable
        if ($product->product_type != Status::PRODUCT_TYPE_VARIABLE) {
            return null; // Non-variable product type
        }

        // Initialize an array to store prices
        $prices = [];

        $productVariants = $product->productVariants ?? collect();

        // Collect prices from variants with valid regular_price
        $prices = $productVariants
        ->map(fn($variant) => $this->getProductPrices($product, $variant))
        ->filter(fn($price) => !is_null($price->regular_price));

        // If no variants have a sale price, return null
        if ($prices->isEmpty()) {
            return null;
        }

        $prices = collect($prices);

        // If the product has only one variant, return its price as the price range
        if ($prices->count() == 1) {
            return $prices->first();
        }

        // Calculate and return the price range for multiple variants
        return (object) ['min_price' => $prices->min('sale_price'), 'max_price' => $prices->max('sale_price')];
    }

    /**
     * Get the formatted price for a product based on regular and sale prices or a price range.
     *
     * @param float $regularPrice The regular price of the product.
     * @param float $salePrice The sale price of the product after offer.
     * @param object|null $priceRange Optional price range object with min_price and max_price.
     *
     * @return string Formatted price content as a string.
     */
    public function getFormattedPrice($regularPrice, $salePrice, $priceRange = null) {
        // Check if a price range is provided and construct the formatted price accordingly
        if ($priceRange && isset($priceRange->min_price) && isset($priceRange->max_price)) {
            return gs('cur_sym') . getAmount($priceRange->min_price) . (($priceRange->min_price != $priceRange->max_price) ? ' - ' . gs('cur_sym') . getAmount($priceRange->max_price) : '');
        }

        // If a variant product with only one variant then show the price like a simple product
        // Check if a price range with regular and sale prices is provided and update the values
        if ($priceRange && isset($priceRange->regular_price) && isset($priceRange->sale_price)) {
            $regularPrice = $priceRange->regular_price;
            $salePrice = $priceRange->sale_price;
        }


        $priceContent = $salePrice ? gs('cur_sym') . $salePrice : '';

        // Include the original price with a strikethrough if it's different from the sale price
        if ($salePrice != $regularPrice && $regularPrice) {
            $priceContent .= " <del>" . gs('cur_sym') . getAmount($regularPrice) . "</del>";
        }

        // If no price content is set, display a placeholder
        return $priceContent ?: '<span class="tba-price">' . trans('TBA') . '</span>';
    }
}
