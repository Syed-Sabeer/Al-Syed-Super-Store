<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\UserNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, UserNotify;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'ver_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'ver_code_send_at' => 'datetime'
    ];

    public function appliedCoupons()
    {
        return $this->hasMany(AppliedCoupon::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn() => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn() => $this->dial_code . $this->mobile,
        );
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)
        // ->where('ev', Status::VERIFIED)->where('sv', Status::VERIFIED)
        ;
    }

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    // public function scopeEmailUnverified($query)
    // {
    //     return $query->where('ev', Status::UNVERIFIED);
    // }

    // public function scopeMobileUnverified($query)
    // {
    //     return $query->where('sv', Status::UNVERIFIED);
    // }

    // public function scopeEmailVerified($query)
    // {
    //     return $query->where('ev', Status::VERIFIED);
    // }

    // public function scopeMobileVerified($query)
    // {
    //     return $query->where('sv', Status::VERIFIED);
    // }

    public function scopeProfileCompleted($query)
    {
        return $query->where('profile_complete', Status::YES);
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }
}
