<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppliedCoupon extends Model
{
    use SoftDeletes;
    protected $guarded  = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
