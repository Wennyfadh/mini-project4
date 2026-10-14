<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'price',
        'stock',
        'minimum_stock',
        'description',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
    ];

    /**
     * Many-to-One: Product belongs to Category (Slide 33)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Many-to-Many: Product has many Suppliers (Slide 5 & 8)
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers')
            ->withPivot('supplier_price')
            ->withTimestamps();
    }

    /**
     * One-to-Many: Product has many StockMovements (Slide 10)
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class)->latest();
    }

    /**
     * Stock status helper
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        }
        if ($this->stock <= $this->minimum_stock) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    /**
     * Formatted Rupiah price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Image URL helper
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('uploads/default_cloth.svg');
        }

        if (file_exists(public_path('uploads/' . $this->image))) {
            return asset('uploads/' . $this->image);
        }

        if (file_exists(public_path('assets/images/products/' . $this->image))) {
            return asset('assets/images/products/' . $this->image);
        }

        return asset('uploads/default_cloth.svg');
    }
}
