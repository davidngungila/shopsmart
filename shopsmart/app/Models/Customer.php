<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'address',
        'loyalty_points', 'total_purchases', 'last_purchase_date', 'is_active'
    ];

    protected $casts = [
        'loyalty_points' => 'decimal:2',
        'total_purchases' => 'decimal:2',
        'last_purchase_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
