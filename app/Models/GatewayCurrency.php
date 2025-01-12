<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class GatewayCurrency extends Model
{

    protected $hidden = [
        'gateway_parameter'
    ];

    protected $casts = ['status' => 'boolean'];

    // Relation
    public function method()
    {
        return $this->belongsTo(Gateway::class, 'method_code', 'code');
    }

    public function currencyIdentifier()
    {
        return $this->name ?? $this->method->name . ' ' . $this->currency;
    }

    public function scopeBaseCurrency()
    {
        return $this->method->crypto == Status::ENABLE ? 'USD' : $this->currency;
    }

    public function scopeBaseSymbol()
    {
        return $this->method->crypto == Status::ENABLE ? '$' : $this->symbol;
    }

    public function codMethod() {
        $this->name = 'COD';
        $this->id = 0;
        $this->fixed_charge = 0;
        $this->percent_charge = 0;
        $this->rate = 1;
        $this->method_code = 0;
        $this->currency = gs('cur_text');
        return $this;
    }

}
