<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model {

    protected $appends = ['full_url', 'thumb_url'];

    public function productImages() {
        return  $this->belongsToMany(Product::class);
    }

    public function productVariantImages() {
        return  $this->belongsToMany(ProductVariant::class);
    }

    function products() {
        return $this->hasMany(Product::class, 'main_image_id');
    }

    function productVariants() {
        return $this->hasMany(ProductVariant::class, 'main_image_id');
    }

    function categories() {
        return $this->hasMany(Category::class);
    }

    function brands() {
        return $this->hasMany(Brand::class);
    }

    function getFullUrlAttribute() {
        return url($this->path . '/' . $this->file_name);
    }

    function getThumbUrlAttribute() {
        $thumb = $this->path . '/thumb_' . $this->file_name;
        if(file_exists($thumb) && is_file($thumb)){
            return url($thumb);
        }
        return $this->full_url;
    }
}
