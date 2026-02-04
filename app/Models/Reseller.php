<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Reseller extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'store_name',
        'address',
        'password',
        'image',
        'status',
        'balance',
        'verify_token',
        'dealer_id',
        'district_id', 
        'thana_id', 
        'user_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id')->where('order_type', 'reseller');
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Order::class, 'customer_id', 'order_id', 'id', 'id')
            ->where('orders.order_type', 'reseller');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'reseller_id');
    }

    public function referrals()
    {
        return $this->hasMany(Reseller::class, 'referrer_id');
    }

    public function referrer()
    {
        return $this->belongsTo(Reseller::class, 'referrer_id');
    }
    public function thana()
    {
        return $this->belongsTo(Thana::class, 'thana_id');
    }
    public function district()
{
    return $this->belongsTo(District::class, 'district_id');
}
}
