<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class AdminQuickTab extends Model
{
    protected $table = 'admin_quick_tabs';
    
    protected $fillable = [
        'user_id',
        'tab_name',
        'tab_link',
        'is_active',
        'order',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}