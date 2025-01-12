<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class SalePriceGreaterThanRegularPrice implements Rule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function passes($attribute, $value)
    {
        // Extract the index from the attribute name
        $slices = explode('.', $attribute);
        $index = $slices[1];

        // Construct the regular price key based on the index
        $regularPriceKey = "variants.$index.regular_price";
        $regularPrice = request()->input($regularPriceKey);

        // Check if sale price is greater than regular price
        return $value <= $regularPrice;
    }

    public function message()
    {
        return 'The sale price must not be greater than the regular price';
    }
}
