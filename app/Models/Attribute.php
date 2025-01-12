<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model {
    use GlobalStatus;

    public function attributeValues() {
        return $this->hasMany(AttributeValue::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class);
    }

    function typeInText() {
        if ($this->type == 2) {
            return 'color';
        } elseif ($this->type == 3) {
            return 'img';
        } else {
            return 'text';
        }
    }
}
