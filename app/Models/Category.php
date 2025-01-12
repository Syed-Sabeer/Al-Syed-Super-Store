<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $casts   = [
        'meta_keywords' => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id')->with('parent');
    }

    public function subcategories()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function allSubcategories()
    {
        return $this->subcategories()->with('allSubcategories');
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupons_categories', 'category_id', 'coupon_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_categories', 'category_id', 'product_id');
    }

    public function shopLink()
    {
        return route('product.by.category', $this->slug);
    }

    public function scopeIsParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::YES);
    }

    public function specialProducts()
    {
        return $this->belongsToMany(Product::class, 'products_categories', 'category_id', 'product_id')
            ->whereHas('categories')
            ->orderBy('id', 'desc')->limit(15);
    }

    public function categoryImage()
    {
        return getImage(getFilePath('category') . '/' . $this->image);
    }

    public function categoryIcon()
    {
        return getImage(getFilePath('categoryIcon') . '/' . $this->icon);
    }

    public function getSeoImageAttribute() {
        return $this->categoryImage();
    }

    public function getSeoImageSizeAttribute() {
        return getFileSize('category');
    }


    public function allParents() {
        $parents = collect();
        $current = $this;

        while ($current->parent) {
            $parents->push($current->parent);
            $current = $current->parent;
        }

        return $parents->reverse();
    }

}
