<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'price',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Method to check if product has enough stock
    public function hasStock($quantity)
    {
        return $this->quantity >= $quantity;
    }

    // Method to reduce stock after purchase
    public function reduceStock($quantity)
    {
        if ($this->hasStock($quantity)) {
            $this->quantity -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    // Method to get total value of remaining stock
    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->price;
    }

    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    public function getFormattedTotalValueAttribute()
    {
        return '₱' . number_format($this->total_value, 2);
    }
}
