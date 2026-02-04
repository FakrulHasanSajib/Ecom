<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = ['id'];
    protected $table = "citys";
    use HasFactory;
    
   //  public function flashdeal()
   // {
   //  return $this->belongsTo(Flashdeal::class, 'flash_deal_id', 'id');
   // }

}