<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'buyer_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'product_id',
        'quantity_purchased',
        'unit_price',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    // Accessor to get formatted total amount
    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    // Accessor to get formatted unit price
    public function getFormattedUnitPriceAttribute()
    {
        return '₱' . number_format($this->unit_price, 2);
    }

    // Scope for completed transactions
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Scope for pending transactions
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
