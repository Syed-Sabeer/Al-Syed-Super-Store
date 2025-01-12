<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model {
    protected $guarded = ['id'];


    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function productVariant() {
        return $this->belongsTo(ProductVariant::class);
    }

    public function scopeHasProduct($query) {
        return  $query->whereHas('product', function ($q) {
            $q->published();
        });
    }
}
