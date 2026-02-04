<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    // এই অংশটিই আপনার এররের মূল কারণ ছিল
    protected $fillable = [
        'filename',
        'path',       // <--- এই লাইনটি আগে মিসিং ছিল বা আপডেট হয়নি
        'extension',  // <--- এটিও যোগ করতে হবে
        'mime_type',
        'size',
    ];

    // ফ্রন্টএন্ডে সহজে লিংক পাওয়ার জন্য
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}