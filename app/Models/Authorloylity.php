<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Authorloylity extends Model
{
    protected $guarded = ['id'];
    protected $table = "author_loyality";
    
   
   public function author()
    {
        return $this->belongsTo(users ::class,'author_id');
    }

}