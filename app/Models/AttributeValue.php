<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('media_id');
    }

    public function media() {
        return $this->belongsToMany(Media::class, 'attribute_value_product');
    }
}
