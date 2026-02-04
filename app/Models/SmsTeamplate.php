<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmsTeamplate extends Model
{
    protected $guarded = ['id'];
    protected $table = "smsteamplate";
    
    
   //  public function flashdeal()
   // {
   //  return $this->belongsTo(Flashdeal::class, 'flash_deal_id', 'id');
   // }

}