<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $guarded = ['id'];
    protected $table = "payment_history";
    use HasFactory;
    
   public function wholesaler(){
        return $this->belongsTo(Wholesaler::class, 'whosales_id');
    }
    
    public function author(){
        return $this->belongsTo(User::class, 'author_id');
    }

}