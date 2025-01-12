<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class PromotionalBanner extends Model
{

    public function images()
    {
        return $this->hasMany(PromotionalBannerImage::class);
    }

    public function fileKeyName()
    {
        $keyName = null;

        if ($this->type == Status::SINGLE_IMAGE_BANNER) {
            $keyName = 'singlePromoBanner';
        } elseif ($this->type == Status::DOUBLE_IMAGE_BANNER) {
            $keyName = 'doublePromoBanner';
        } else {
            $keyName = 'triplePromoBanner';
        }

        return $keyName;
    }
}
