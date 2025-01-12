<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionalBannerImage extends Model
{

    public function promotionalBanner()
    {
        return $this->belongsTo(PromotionalBanner::class);
    }

    public function getImage()
    {
        $banner = $this->promotionalBanner;
        return getImage(getFilePath($banner->fileKeyName()) . '/' . $this->image, getFileSize($banner->fileKeyName()));
    }
}
