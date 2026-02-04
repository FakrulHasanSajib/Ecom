<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Childcategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function subcategory() {
    return $this->belongsTo(Subcategory::class, 'subcategory_id')->withDefault([
        'subcategoryName' => 'N/A' 
    ]);
}
}
