<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalFile extends Model {

    public function fileable() {
        return $this->morphTo();
    }
}
