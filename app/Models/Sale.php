<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'total_amount',
        'tax_amount',
        'net_amount',
        'customer_name',
        'loyalty_points',
        'payment_method'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
} 