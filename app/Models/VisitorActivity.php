<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorActivity extends Model
{
    use HasFactory;

    // এই লাইনটি না থাকলে ডাটা সেভ হবে না এবং 500 Error দিবে
    protected $guarded = [];
}