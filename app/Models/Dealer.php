<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Dealer extends Authenticatable
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
        'balance',
        'status',
        'verify_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'dealer_products')
            ->withPivot('dealer_price', 'reseller_price', 'commission_amount', 'commission_type')
            ->withTimestamps();
    }

    public function resellers()
    {
        return $this->hasMany(Reseller::class);
    }

    public function customers()
    {
        // Keeping this for backward compatibility if needed, but primary logic is now Resellers
        return $this->hasMany(Customer::class);
    }
}
