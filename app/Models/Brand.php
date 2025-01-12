<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'meta_keywords' => 'array',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::YES);
    }

    public function logo()
    {
        return getImage(getFilePath('brand') . '/' . $this->logo, getFileSize('brand'));
    }

    public function shopLink()
    {
        return route('product.by.brand', $this->slug);
    }

    public function getSeoImageAttribute() {
        return$this->logo();
    }

    public function getSeoImageSizeAttribute() {
        return getFileSize('brand');
    }
}
