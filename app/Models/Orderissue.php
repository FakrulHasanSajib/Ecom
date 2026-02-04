<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderissue extends Model
{
    protected $guarded = ['id'];
    protected $table = "order_issue";
    use HasFactory;

    public function order()
 {
    return $this->hasMany(Order::class, 'invoice_id', 'invoice_id');
 }


}