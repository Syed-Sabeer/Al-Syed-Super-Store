<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\DigitalFile;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class ProductManager
 *
 * This class is responsible for managing product-related operations.
 */
class ProductManager
{

    /**
     * Download a digital product file.
     *
     * @param $product The product containing the file to be downloaded.
     *
     * @return mixed The file content for download.
     */
    public function downloadDigitalProductFile($id)
    {
        try {
            $file  = DigitalFile::with('fileable')->findOrFail(decrypt($id));
            $fullPath = getFilePath('digitalProductFile') . '/' . $file->name;
            $mimetype = mime_content_type($fullPath);
            header('Content-Disposition: attachment; filename="' . slug($file->fileable->name) . '.' . pathinfo($file->name, PATHINFO_EXTENSION) . '";');
            header("Content-Type: " . $mimetype);
            return readfile($fullPath);
        } catch (\Throwable $th) {
            Log::error("Error in downloadDigitalProductFile: " . $th->getMessage());
            abort(404);
        }
    }

    /**
     * Delete a product (either delete or restore it).
     *
     * @param $product The product to be deleted or restored.
     *
     * @return string A message indicating the success of the operation.
     */
    public function deleteProduct(Product $product)
    {
        if ($product->trashed()) {
            $product->restore();
            $message = 'Product restored successfully';
        } else {
            $product->delete();
            $message = 'Product deleted successfully';
        }
        return $message;
    }

    /**
     * Keep the log of stock changes
     *
     * @param $product The instance of product for which stock log is being created.
     * @param $changeQuantity The quantity is added or removed from the stock.
     * @param $remark The remark is to identify is the changes is for added or removed.
     * @param string $description The description of the changes.
     * @param $variant The instance of product variant for which stock log is being created.
     */
    public function createStockLog($product, $changeQuantity, $description = '', $variant = null, $remark = null, $orderId = null)
    {
        if ($changeQuantity != 0) {
            $log                     = new StockLog();
            $log->product_id         = $product->id;
            $log->product_variant_id = $variant ? $variant->id : 0;
            $log->order_id           = $orderId;
            $log->change_quantity    = abs($changeQuantity);
            $log->post_quantity      = $variant->in_stock ?? $product->in_stock;
            $log->description        = $description;
            $log->remark             = $remark;
            $log->save();
        }
    }

    /**
     * Set product entities based on form input.
     *
     * @param $request The request containing product attribute data.
     * @param $product The product to which entities are being set.
     */
    public function setProductEntities(Request $request, Product $product)
    {
        $product->name                      = $request->name ?? 'No Title';
        $product->slug                      = $request->slug;
        $product->product_type              = $request->product_type;
        $product->brand_id                  = $request->brand_id ?? 0;

        $product->regular_price             = $request->regular_price ?? null;
        $product->sale_price                = $request->sale_price ?? null;
        $product->sale_starts_from          = $request->sale_starts_from;
        $product->sale_ends_at              = $request->sale_ends_at;


        $product->description               = $request->description;
        $product->summary                   = $request->summary;

        $product->meta_title                = $request->meta_title;
        $product->meta_description          = $request->meta_description;
        $product->meta_keywords             = $request->meta_keywords ?? null;

        $product->main_image_id             = $request->main_image ?? 0;
        $product->video_link                = $request->video_link;

        $product->extra_descriptions        = $request->extra_description ?? null;

        $product->is_published              = $request->is_published ? Status::YES : Status::NO;
        $product->is_showable               = $request->is_showable ? Status::YES : Status::NO;


        $product->is_downloadable           = $request->is_downloadable ? Status::YES : Status::NO;
        $product->delivery_type             = $request->delivery_type;

        $product->sku                       = $request->sku;
        $product->track_inventory           = $request->track_inventory ? Status::YES : Status::NO;
        $product->show_stock                = $request->show_stock ? Status::YES : Status::NO;
        $product->in_stock                  = $request->in_stock ?? 0;
        $product->alert_quantity            = $request->alert_quantity ?? 0;

        $product->product_type_id           = $request->product_type_id ?? 0;
        $product->specification             = $request->specification ?? null;

        $product->save();
    }


    /**
     * Remove a digital product file from the server.
     *
     * @param $file The name of the file to be removed.
     */
    public function removeDigitalProductFile($file)
    {
        fileManager()->removeFile(getFilePath('digitalProductFile') . '/' . $file);
    }


    /**
     * Upload the digital product file to the server.
     *
     * @param Illuminate\Http\UploadedFile $file The file containing the digital product file.
     * @param String $oldFile The existing digital product file (optional).
     *
     * @return mixed The new file path or the existing file if no new file is provided.
     */
    public function uploadDigitalProductFile($file, $oldFile = null)
    {
        return fileUploader($file, getFilePath('digitalProductFile'), old: $oldFile);
    }

    /**
     * Add, remove, or update variant gallery images.
     *
     * @param $galleryImages The array of media IDs to be associated with the product variant.
     * @param $variant The product variant to which images are being adjusted.
     */
    public function adjustVariantGalleryImages($galleryImages, $variant)
    {
        $variant->galleryImages()->sync($galleryImages);
    }

    /**
     * Add, remove, or update product images.
     *
     * @param $galleryImages The array of media IDs to be associated with the product.
     * @param $product The product to which images are being adjusted.
     */
    public function adjustGalleryImages($galleryImages, $product, $isUpdate = false)
    {

        if ($isUpdate) {
            $product->galleryImages()->sync($galleryImages);
        } else {
            $product->galleryImages()->attach($galleryImages);
        }
    }

    /**
     * Add, remove, or update product categories.
     *
     * @param $categories The array of category IDs to be associated with the product.
     * @param $product The product to which categories are being adjusted.
     */
    public function adjustCategories($categories, $product, $isUpdate = false)
    {
        if ($isUpdate) {
            $product->categories()->sync($categories);
        } else {
            $product->categories()->attach($categories);
        }
    }

    /**
     * Adjust the attributes of a product.
     *
     * This function allows you to modify the attributes associated with a product.
     * You can either update the existing attributes or attach new attributes.
     *
     * @param array   $attributes An array containing the IDs of the attributes to be associated with the product.
     * @param Product $product    An instance of the Product class representing the product whose attributes are to be adjusted.
     * @param bool    $isUpdate   A boolean flag indicating whether to update the product's attributes or attach new attributes to it.
     * @return void
     */
    public function adjustProductAttributes(array $attributes, Product $product, bool $isUpdate): void
    {
        if ($isUpdate) {
            $product->attributes()->sync($attributes);
        } else {
            $product->attributes()->attach($attributes);
        }
    }


    /**
     * Adjust the attribute values of a product.
     *
     * This function allows you to modify the attribute values associated with a product.
     * You can either update the existing attribute values or attach new ones.
     *
     * @param array   $attributeValues An array containing  the IDs of the attribute values to be associated with the product.
     * @param Product $product         An instance of the Product class representing the product whose attribute values are to be adjusted.
     * @param bool    $isUpdate        A boolean flag indicating whether to update the product's attribute values or attach new ones.
     * @return void
     */
    public function adjustProductAttributeValues(array $attributeValues, Product $product, bool $isUpdate): void
    {
        if ($isUpdate) {
            $product->attributeValues()->sync($attributeValues);
        } else {
            $product->attributeValues()->attach($attributeValues);
        }
    }

    public function adjustProductVariants($id)
    {
        // Find the product with it variants, attribute, and attribute values are assigned.
        $product = Product::with(['productVariants', 'attributes', 'attributeValues'])->findOrFail($id);

        $this->deleteOldVariants($product->productVariants);
    }

    public function saveProductVariants($generatedVariants, $product)
    {
        foreach ($generatedVariants as $variant) {
            $variant                    = collect($variant);
            $attributeArray             = $this->prepareAttributeValuesArray($variant);
            $savedVariant               = $product->productVariants->where('attribute_values', $attributeArray)->first();

            if ($savedVariant && $savedVariant->trashed()) {
                $savedVariant->restore();
            }

            $productVariant                   = $savedVariant ?? new ProductVariant();
            $productVariant->product_id       = $product->id;
            $productVariant->name             = implode(' - ', $variant->pluck('name')->toArray());
            $productVariant->attribute_values = $attributeArray;

            $productVariant->save();
        }
    }

    /**
     * Generate variants according to the attribute_values id
     *
     * @param Collection $attributeValues
     * @return array the array of variants
     */
    public function generateVariants(Collection $attributeValues)
    {
        // Group the attribute_values by the attributes
        $attributeGroup = $attributeValues->groupBy('attribute_id');

        $variantsArray   = [];

        foreach ($attributeGroup as $attributes) {
            $variantArray = [];
            foreach ($attributes as $attributeValue) {
                $variantArray[] = [
                    'name' => $attributeValue->name,
                    'id' => $attributeValue->id
                ];
            }
            $variantsArray[] = $variantArray;
        }

        return $this->generateCombination($variantsArray);
    }

    private function deleteOldVariants($oldVariants)
    {
        if (empty($oldVariants)) return;
        foreach ($oldVariants as $oldVariant) {
            $oldVariant->delete();
        }
    }

    /**
     * Prepare the arrays of attribute_values_id from the collection of arrays that contains name, id pair
     *
     * @param Collection $variant the variant by which ids are need to prepare
     * @return array The array of attribute_values
     */
    private function prepareAttributeValuesArray($variant)
    {
        $attributeValueArray = $variant->pluck('id')->toArray();
        sort($attributeValueArray);
        return $attributeValueArray;
    }

    private function generateCombination($arrays, $currentIndex = 0)
    {

        $combinations = [];

        // If there's only one array remaining, return its elements as combinations.
        if ($currentIndex === count($arrays) - 1) {
            foreach ($arrays[$currentIndex] as $element) {
                $combinations[] = [$element];
            }
        } else {
            // Recursively generate combinations for the remaining arrays.
            $subCombinations = $this->generateCombination($arrays, $currentIndex + 1);

            foreach ($arrays[$currentIndex] as $element) {
                foreach ($subCombinations as $subCombination) {
                    // Combine the current element with each subCombination.
                    $combinations[] = array_merge([$element], $subCombination);
                }
            }
        }

        return $combinations;
    }

    public function checkSaleExpiryTime($item)
    {
        // If sale_starts_from is null or in the past, and sale_ends_at is null or in the future, return the sale price
        if ((is_null($item->sale_starts_from) || $item->sale_starts_from <= now()) && (is_null($item->sale_ends_at) || $item->sale_ends_at >= now())) {
            return true;
        }
        return false;
    }

    public function getStockData($product)
    {
        // Check if the product is simple and tracks inventory
        if ($product->product_type == Status::PRODUCT_TYPE_SIMPLE) {
            return $this->getSimpleProductStock($product);
        } else {
            return $this->getTotalVariantStock($product);
        }
    }

    public function getSimpleProductStock($product, $formatted = false)
    {
        // Check if the simple product tracks inventory
        if ($product->track_inventory) {
            return $product->in_stock;
        }
        // if doesn't track inventory the quantity will be infinite
        return $formatted ? '<i class="las la-infinity"></i>' : 'INFINITY';
    }


    public function getDetailedStock($product, $limit = null)
    {
        if ($product->product_type == Status::PRODUCT_TYPE_SIMPLE) {
            return $this->getSimpleProductStock($product, true);
        }

        $result = '';

        if (blank($product->productVariants)) {
            $result = 'N/A';
        } else {
            foreach ($product->productVariants->take($limit) as $variant) {
                // Check if the variant tracks inventory
                if ($variant->trackInventory($product)) {
                    $result .= '<small class="d-block text-muted">' . $variant->name . ': ' . $variant->inStock($product) . '</small>';
                } else {
                    // Variant doesn't track inventory
                    $result .= '<small class="d-block text-muted">' . $variant->name . ': ' . '<i class="las la-infinity"></i></small>';
                }
            }
        }

        return $result;
    }

    public function getTotalVariantStock($product)
    {
        $total = 0;
        $infinity = false;

        if (blank($product->productVariants)) {
            return 'NOT_AVAILABLE';
        } else {
            foreach ($product->productVariants as $variant) {
                // Check if the variant tracks inventory
                if ($variant->trackInventory($product)) {
                    $total += $variant->inStock($product);
                } else {
                    $infinity = true;
                }
            }
        }
        return $infinity ? 'INFINITY' : $total;
    }

    /**
     * Determine whether the cart button should be displayed for a product or variant.
     *
     * @param mixed $product The product object.
     * @param mixed $variant The product variant object (optional for variable products).
     *
     * @return bool Returns true if the cart button should be displayed, otherwise false.
     */
    public function shouldCartButtonShow($product, $variant = null)
    {
        // Simple Product
        if ($product->product_type == Status::PRODUCT_TYPE_SIMPLE) {
            if (!$product->prices()->regular_price || ($product->track_inventory && $product->in_stock == 0)) {
                return false;
            }
        } else {
            // Variable Product
            if (blank($variant)) {
                return false;
            }

            if (!$product->price_range) {
                return false;
            }
        }

        return true;
    }
}
