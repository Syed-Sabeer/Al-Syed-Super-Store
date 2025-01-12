<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ProductPrice {

    /**
     * Get the formatted price of the product.
     *
     * This method returns the formatted price of the product, taking into account
     * the regular price, sale price, and price range. It uses the productPriceManager
     * to handle the formatting.
     *
     * @return string The formatted price of the product.
     */
    public function formattedPrice() {
        return productPriceManager()->getFormattedPrice($this->regular_price, $this->salePrice(), $this->price_range);
    }

    public function prices($variant = null) {
        return productPriceManager()->getProductPrices($this, $variant);
    }

    public function salePrice() {
        return productPriceManager()->getOnSalePrice($this);
    }

    public function getPriceRangeAttribute() {
        return Cache::remember("product_{$this->id}_price_range", now()->addMinutes(10), function () {
            $priceRange = productPriceManager()->calculatePriceRange($this);
            return $priceRange ?? (object) ['min_price' => null, 'max_price' => null];
        });
    }
}
