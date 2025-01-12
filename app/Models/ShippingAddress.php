<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ShippingAddress extends Model
{
    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }
}
