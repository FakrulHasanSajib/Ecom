<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentCampaign extends Model
{
    use HasFactory;

    // ডাটাবেস টেবিলের নাম যদি 'recruitment_campaigns' হয়, লারাভেল অটোমেটিক চিনে নিবে।
    // তবে সুরক্ষার জন্য ফিল্ডগুলো ডিফাইন করে দেওয়া ভালো।
    
    protected $guarded = []; 
    
    // অথবা আপনি চাইলে fillable ব্যবহার করতে পারেন:
    /*
    protected $fillable = [
        'title',
        'slug',
        'referral_code',
        'creator_type',
        'creator_id',
        'banner',
        'video_url',
        'description',
        'status'
    ];
    */
}