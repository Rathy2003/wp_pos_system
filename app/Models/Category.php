<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'store_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id')->where('store_id', auth()->user()->store_id);
    }
} 