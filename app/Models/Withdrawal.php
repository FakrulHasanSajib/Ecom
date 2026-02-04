<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reseller()
    {
        return $this->belongsTo(Reseller::class, 'reseller_id');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id');
    }
}
