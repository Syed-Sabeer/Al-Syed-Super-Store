<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ProductPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, ProductPrice;

    protected $appends = ['price_range'];

    protected $casts = [
        'sale_price'         => 'double',
        'regular_price'      => 'double',
        'sale_starts_from'   => 'datetime',
        'sale_ends_at'       => 'datetime',
        'reviews_avg_rating' => 'double',
        'extra_descriptions' => 'array',
        'specification'      => 'object',
        'meta_keywords'      => 'array',
    ];

    public function digitalFile()
    {
        return $this->morphOne(DigitalFile::class, 'fileable');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_categories', 'product_id', 'category_id');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function displayImage()
    {
        return $this->belongsTo(Media::class,  'main_image_id');
    }

    public function activeOffer()
    {
        return $this->offer()->running();
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupons_products', 'product_id', 'coupon_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class)->withPivot('media_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public function userReview()
    {
        return $this->hasOne(ProductReview::class, 'product_id')->where('user_id', auth()->id());
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function galleryImages()
    {
        return $this->belongsToMany(Media::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /*======= Scopes =======*/
    public function scopeUnpublished($query)
    {
        return $query->where('is_published', Status::NO);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', Status::YES);
    }

    public function scopePhysical($query)
    {
        return $query->where('is_downloadable', Status::NO);
    }

    public function scopeDigital($query)
    {
        return $query->where('is_downloadable', Status::YES);
    }

    public function scopeShopPageProducts($query)
    {
        return $query->where('is_showable', Status::YES);
    }

    public function scopeLowStock($query)
    {
        return $query->where(function ($query) {
            $query->where('track_inventory', Status::YES)
                ->whereColumn('in_stock', '<=', 'alert_quantity');
        })->orWhere(function ($query) {
            $query->whereHas('productVariants', function ($q) {
                $q->where('track_inventory', Status::YES)
                    ->whereColumn('in_stock', '<=', 'alert_quantity');
            });
        });
    }
    public function scopeOutOfStock($query)
    {
        return $query->where(function ($query) {
            $query->where('track_inventory', Status::YES)
                ->where(function ($q) {
                    $q->where('in_stock', '=', 0)
                        ->orWhereNull('in_stock');
                });
        })->orWhere(function ($query) {
            $query->whereHas('productVariants', function ($q) {
                $q->where('track_inventory', Status::YES)
                    ->where(function ($subQuery) {
                        $subQuery->where('in_stock', '=', 0)
                            ->orWhereNull('in_stock');
                    });
            });
        });
    }

    /*======= Helper Methods =======*/

    public function link()
    {
        return route('product.detail', $this->slug);
    }

    public function mainImage($thumb = true)
    {
        $thumb = $thumb ? '/thumb_' : '/';
        $image = $this->displayImage;
        if (!$image) {
            return getImage(null);
        }
        return getImage($image->path . $thumb . $image->file_name);
    }

    function typeInText()
    {
        return $this->is_downloadable ? 'digital' : 'physical';
    }

    public function inStock($variant = null)
    {
        return $variant ? $variant->inStock($this) : $this->in_stock;
    }

    function detailedStock($limit = null)
    {
        return productManager()->getDetailedStock($this, $limit);
    }

    function totalInStock()
    {
        return productManager()->getStockData($this);
    }

    public function trackInventory($variant = null)
    {
        return $variant ? $variant->trackInventory($this) : $this->track_inventory;
    }

    public function showCategories($limit = null)
    {
        if (!blank($this->categories)) {
            $productCategories = $this->categories->take($limit)->pluck('name')->toArray();
            return implode(', ', $productCategories);
        }
        return trans('N/A');
    }

    public function editUrl()
    {
        return route("admin.products.edit", $this->id);
    }

    public static function topSales($limit = 6, $paginate = false)
    {
        $products = self::leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
            ->selectRaw('products.*, COALESCE(sum(order_details.quantity),0) total')
            ->where('orders.status', '=', Status::ORDER_DELIVERED)
            ->groupBy('products.id')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('total', 'desc');

        if ($paginate) {
            return $products->searchable(['name'])->filter(['brand_id'])->paginate(getPaginate());
        }

        return $products->limit($limit)->get();
    }

    public function getSeoImageAttribute()
    {
        return $this->mainImage(false);
    }

    public function getSeoImageSizeAttribute()
    {
        return getFileSize('product');
    }
}
