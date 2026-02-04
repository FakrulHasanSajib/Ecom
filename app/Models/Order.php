<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;
    
    // মডেলে একটি গার্ডেড প্রোপার্টি যোগ করা হলো।
    protected $guarded = []; 

    /**
     * আইটেম যোগ/বিয়োগ করার পর অর্ডারের মোট টাকা আপডেট করার মেথড
     */
    public function updateOrderTotal()
    {
        // ১. অর্ডারের সব আইটেমের (sale_price * qty) যোগফল বের করা
        $subTotal = $this->orderdetails()->sum(DB::raw('sale_price * qty'));
        
        // ২. শিপিং চার্জ যোগ করা এবং ডিসকাউন্ট বাদ দেওয়া
        // আপনার ডাটাবেস কলাম অনুযায়ী shipping_charge এবং discount ব্যবহার করুন
        $shipping = $this->shipping_charge ?? 0;
        $discount = $this->discount ?? 0;

        $grandTotal = ($subTotal + $shipping) - $discount;

        // ৩. মেইন অর্ডার টেবিলের 'amount' কলাম আপডেট করা
        $this->update([
            'amount' => $grandTotal
        ]);
        
        return $grandTotal;
    }

    // Relational Methods

    public function orderdetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }
    
    public function product()
    {
        return $this->belongsTo(OrderDetails::class, 'id', 'order_id')->select('id','order_id','product_id');
    }
    
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status');
    }
    
    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'id', 'order_id');
    }
    
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'id', 'order_id');
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    
    public function wholesaler()
    { 
        return $this->belongsTo(Wholesaler::class,'customer_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'assign_user_id');
    }
    public function assignedUser()
{
    return $this->belongsTo(User::class, 'assign_user_id');
}
public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}