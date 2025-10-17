<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'images',
        'specifications',
        'is_featured',
        'is_active',
        'views'
    ];

    protected $casts = [
        'images' => 'array',
        'specifications' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the product's formatted price.
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($this->price, 0, ',', '.') . ' VNĐ',
        );
    }

    /**
     * Get the product's image URL.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->image ? asset('img/' . basename($this->image)) : asset('img/placeholder.jpg'),
        );
    }

    /**
     * Get the product's additional images URLs.
     */
    protected function imagesUrls(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->images ? array_map(fn($img) => asset('img/' . basename($img)), $this->images) : [],
        );
    }

    /**
     * Get all product images including the main image.
     */
    protected function allImages(): Attribute
    {
        return Attribute::make(
            get: function () {
                $images = $this->images ?: [];
                if ($this->image) {
                    array_unshift($images, $this->image);
                }
                return array_unique($images);
            }
        );
    }

    /**
     * Check if product is in stock.
     */
    protected function inStock(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->stock > 0,
        );
    }

    /**
     * Increment product views.
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Scope for featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for products in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category_name' => $this->category->name ?? '',
            'specifications' => $this->specifications ?? [],
        ];
    }

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs()
    {
        return 'products_index';
    }
}
