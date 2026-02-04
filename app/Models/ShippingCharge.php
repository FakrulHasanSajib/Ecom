<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
   protected $fillable = ['name', 'amount', 'status'];
    use HasFactory;
    protected $guarded = [];
}