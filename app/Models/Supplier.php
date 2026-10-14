<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
    ];

    /**
     * Many-to-Many: Supplier supplies many Products (Slide 5 & 8)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_suppliers')
            ->withPivot('supplier_price')
            ->withTimestamps();
    }
}
