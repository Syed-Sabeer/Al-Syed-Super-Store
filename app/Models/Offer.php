<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model {
    use GlobalStatus;

    protected $casts = [
        'starts_from' => 'datetime',
        'ends_at'     => 'datetime',
        'amount'      => 'double'
    ];

    public function scopeRunning($query) {
        $query->active()->where('starts_from', '<=', now())->where('ends_at', '>=', now());
    }


    public function products() {
        return $this->hasMany(Product::class);
    }

    public function discountTypeBadge() {
        if ($this->discount_type == Status::DISCOUNT_FIXED) {
            return '<span class="badge badge--primary">' . trans('Fixed') . '</span>';
        } else {
            return '<span class="badge badge--dark">' . trans('Percentage') . '</span>';
        }
    }

    public function getStatusTextAttribute() {
        if ($this->status == Status::ENABLE) {
            return 'Active';
        } else {
            return 'Deactivated';
        }
    }

    public function discountAmount($regularPrice) {
        if ($this->discount_type == Status::DISCOUNT_PERCENT) {
            return $this->amount * $regularPrice / 100;
        } else {
            return $this->amount;
        }
    }
}
