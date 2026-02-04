<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    protected $guarded = ['id'];
    protected $table = "thana";
    use HasFactory;
    
   
   public function City()
    {
        return $this->belongsTo(City::class,'city_id');
    }

}