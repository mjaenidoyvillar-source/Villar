<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];


    // Relationship with transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    // Accessor to get total transactions count
    public function getTotalTransactionsAttribute()
    {
        return $this->transactions()->count();
    }

    // Accessor to get total amount spent
    public function getTotalSpentAttribute()
    {
        return $this->transactions()->where('status', 'completed')->sum('total_amount');
    }

    // Accessor to get formatted total spent
    public function getFormattedTotalSpentAttribute()
    {
        return '₱' . number_format($this->total_spent, 2);
    }
}
