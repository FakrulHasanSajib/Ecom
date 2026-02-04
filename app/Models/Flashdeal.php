<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashdeal extends Model
{
    protected $guarded = ['id'];
    protected $table = "flash_deal";
    use HasFactory;

    public function flash_deal_products()
{
    return $this->hasMany(Flashdealpro::class, 'flash_deal_id', 'id');
}


}