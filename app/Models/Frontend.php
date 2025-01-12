<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frontend extends Model
{
    protected $casts = [
        'data_values' => 'object',
        'seo_content'=>'object'
    ];

    public function labelText($color_name)
    {
        if($color_name == 'Red'){
            return 'danger';
        }elseif($color_name == 'Green'){
            return 'success';
        }elseif($color_name == 'Blue'){
            return 'primary';
        }elseif($color_name == 'Yellow'){
            return 'warning';
        }elseif($color_name == 'Gray'){
            return 'secondary';
        }else{
            return 'dark';
        }
    }

    public static function scopeGetContent($data_keys)
    {
        return Frontend::where('data_keys', $data_keys);
    }
}
