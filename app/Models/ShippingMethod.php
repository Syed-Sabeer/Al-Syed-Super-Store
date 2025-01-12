<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $guarded = ['id'];

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get: fn () => $this->badgeData(),
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::ENABLE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', Status::DISABLE);
    }
}
