<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model {
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function scopeHasProduct($query) {
        return  $query->whereHas('product', function ($q) {
            $q->published();
        });
    }
}
