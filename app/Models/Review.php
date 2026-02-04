<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer()
    {
        // যদি কাস্টমার তথ্য 'User' টেবিল থেকে আসে
        return $this->belongsTo(User::class, 'customer_id'); 
        
        // নোট: যদি আপনার ডাটাবেজে কলামের নাম 'user_id' হয়, 
        // তবে 'customer_id' এর জায়গায় 'user_id' লিখুন।
    }
}
