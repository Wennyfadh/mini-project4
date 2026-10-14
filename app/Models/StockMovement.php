<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Many-to-One: StockMovement belongs to Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Many-to-One: StockMovement belongs to User who recorded the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
