<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Rules\FileTypeValidate;
use App\Rules\SalePriceGreaterThanRegularPrice;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductValidationService
{
    /**
     * Define validation rules for product data.
     *
     * @param $request The request containing product data.
     * @param $product The product being validated.
     *
     * @return mixed The validation result.
     */

    public function productValidationRule(Request $request, Product $product)
    {
        $productTypes = implode(',', [Status::PRODUCT_TYPE_SIMPLE, Status::PRODUCT_TYPE_VARIABLE]);

        $validationRule = [
            // Basic Information
            'name'                      => 'nullable|string',
            'slug'                      => 'nullable|regex:/^[a-z0-9-]+$/',
            'product_type'              => 'nullable|in:' . $productTypes,

            'brand_id'                  => 'nullable|integer|exists:brands,id',
            "categories"                => 'nullable|array|min:1',
            "categories.*"              => 'required|exists:categories,id',

            // Pricing
            'regular_price'             => 'nullable|required_with:sale_price|numeric|gte:0',
            'sale_price'                => 'nullable|numeric|lte:regular_price',
            "sale_starts_from"          => 'nullable|date|date_format:Y-m-d h:i A',
            "sale_ends_at"              => 'nullable|date|date_format:Y-m-d h:i A|after:sale_starts_from',

            // Product Description
            'description'               => 'nullable|string',
            'summary'                   => 'nullable|string|max:6000',

            // SEO Contents
            'meta_title'                => 'nullable|string',
            'meta_description'          => 'nullable|string',
            'meta_keywords'             => 'nullable|array',
            'meta_keywords.array.*'     => 'required_with:meta_keywords|string',

            // Media Contents
            'main_image'                => 'nullable|numeric|exists:media,id',
            'gallery_images'            => 'nullable|string',
            'video_link'                => 'nullable|url',

            // Extra Description
            'extra_description'         => 'nullable|array',
            'extra_description.*.key'   => 'required_with:extra_description',
            'extra_description.*.value' => 'required_with:extra_description',

            // Product Status
            'is_published'              => 'nullable|in:1',

            // Variant Management
            'product_attributes'        => 'nullable|required_if:product_type,' . Status::PRODUCT_TYPE_VARIABLE . '|array|min:1',
            'product_attributes.*'      => 'required_with:product_attributes|exists:attributes,id',
            'attribute_values'          => 'nullable|required_with:product_attributes|array|size:' . count($request['product_attributes'] ?? []),
            'attribute_values.*'        => 'required_with:attribute_values|exists:attribute_values,id',

            // Downloadable Configuration
            'is_downloadable'           => 'nullable|in:1',
            'delivery_type'             => 'nullable|required_if:is_downloadable,1|in:1,2',
            'file'                      => $this->digitalProductFileValidationRule($request, $product),

            // Inventory
            'track_inventory'           => 'nullable|in:1',
            'show_stock'                => 'nullable|in:1',
            'sku'                       => 'nullable|string|max:40',
            'in_stock'                  => 'nullable|integer|gte:0',
            'alert_quantity'            => 'nullable|integer|gte:0',
        ];

        if (@$product->id && $product->product_type == Status::PRODUCT_TYPE_VARIABLE) {
            $variantRules = $this->productVariantsValidationRules($product);
            $validationRule = [...$validationRule, ...$variantRules]; // Merge validation rules
        }

        if (@$product->id) {
            $specificationRules = $this->productSpecificationValidationRules();
            $validationRule = [...$validationRule, ...$specificationRules];
        }

        return Validator::make($request->all(), $validationRule, $this->customValidationMessages());
    }

    /**
     * Define validation rules for product specifications.
     *
     * This method sets up the validation rules for product specifications,
     * including the specification template and individual specification items.
     *
     * @return array An array of validation rules for product specifications.
     */
    private function productSpecificationValidationRules()
    {
        return [
            'product_type_id'           => 'nullable|exists:product_types,id',
            'specification'             => 'nullable|required_with:product_type_id|array',
            'specification.*.key'       => 'required_with:specification',
            'specification.*.value'     => 'nullable|string',
        ];
    }

    /**
     * Define validation rules for product variants.
     *
     * This method sets up the validation rules for product variants, including
     * the variant's ID, regular price, sale price, sale start and end dates,
     * SKU, manage stock, track inventory, show stock, publication status, and
     * stock quantity.
     *
     * @param Product $product The product being validated.
     *
     * @return array An array of validation rules for product variants.
     */
    private function productVariantsValidationRules($product)
    {
        $variants       = $product->productVariants;

        return [
            'variants'                    => 'nullable|array|min:1',
            'variants.*.id'               => 'required|in:' . implode(',', $variants->pluck('id')->toArray()),
            'variants.*.regular_price'    => 'nullable|numeric|gte:0',
            'variants.*.sale_price'       => ['nullable', 'numeric', 'gte:0', new SalePriceGreaterThanRegularPrice()],
            "variants.*.sale_starts_from" => 'nullable|date|date_format:Y-m-d h:i A',
            "variants.*.sale_ends_at"     => 'nullable|date|date_format:Y-m-d h:i A|after:sale_starts_from',
            'variants.*.sku'              => 'nullable|string|max:40',
            'variants.*.manage_stock'     => 'nullable|in:1',
            'variants.*.track_inventory'  => 'nullable|in:1',
            'variants.*.show_stock'       => 'nullable|in:1',
            'variants.*.is_published'     => 'nullable|in:1',
            'variants.*.in_stock'         => 'nullable|integer|gte:0',
            'variants.*.alert_quantity'   => 'nullable|integer|gte:0',
            'variants.*.file'             => ['nullable', new FileTypeValidate(['zip'])],
            'variants.*.main_image'       => 'nullable|numeric|exists:media,id',
            'variants.*.gallery_images'   => 'nullable|string',
        ];
    }

    /**
     * Set validation rule for the product SKU.
     *
     * @param $request The request containing SKU-related data.
     *
     * @return void
     *
     * @throws ValidationException If the attribute and its corresponding value do not match.
     */
    public function validateAttributeValues(Request $request)
    {

        if ($request->product_type == Status::PRODUCT_TYPE_VARIABLE) {
            $productAttributes  = $request->product_attributes ?? [];
            $attributeValuesKey = array_keys($request->attribute_values ?? []);

            sort($productAttributes);
            sort($attributeValuesKey);

            if ($productAttributes != $attributeValuesKey) {
                throw ValidationException::withMessages(['error' => 'The attribute and its corresponding value do not match']);
            }
        }
    }

    /**
     * Define custom validation error messages.
     *
     * @return array An array of custom validation error messages.
     */
    private function customValidationMessages()
    {
        return [
            'specification.*.name.required_with'  => 'All specification name is required',
            'specification.*.value.required_with' => 'All specification value is required',
            'product_attributes.required_if'      => 'Product attributes is required if product type is variable',
            'delivery_type.required_if'           => 'Delivery type field is required if product is downloadable',
            'extra_description.*.key.required_with'   => 'Extra description name field is required',
            'extra_description.*.value.required_with'   => 'Extra description value field is required',
        ];
    }

    /**
     * Set validation rules for the digital product file.
     *
     * @param Request $request The request containing digital product file data.
     * @param Product $product The product being validated.
     *
     * @return string The validation rule for the digital product file.
     */
    private function digitalProductFileValidationRule(Request $request, Product $product)
    {
        $rule = 'nullable';

        if ($request->is_downloadable == Status::YES && $request->product_type == Status::PRODUCT_TYPE_SIMPLE && $request->delivery_type == Status::DOWNLOAD_INSTANT) {
            $rule = 'required';

            if ($product->id && !$product->digitalFile) {
                $rule = 'required';
            } else {
                $rule = 'nullable';
            }
        }

        return [$rule, new FileTypeValidate(['zip'])];
    }
}
