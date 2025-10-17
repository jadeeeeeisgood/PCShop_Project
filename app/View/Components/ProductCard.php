<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductCard extends Component
{
    public Product $product;
    public bool $showStock;
    public bool $showViews;
    public ?int $titleLimit;
    public bool $featured;

    /**
     * Create a new component instance.
     */
    public function __construct(
        Product $product,
        bool $showStock = false,
        bool $showViews = false,
        ?int $titleLimit = null,
        bool $featured = false
    ) {
        $this->product = $product;
        $this->showStock = $showStock;
        $this->showViews = $showViews;
        $this->titleLimit = $titleLimit;
        $this->featured = $featured;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-card');
    }

    /**
     * Get the product title with optional limit
     */
    public function getTitle(): string
    {
        if ($this->titleLimit) {
            return Str::limit($this->product->name, $this->titleLimit);
        }

        return $this->product->name;
    }

    /**
     * Get the product image URL
     */
    public function getImageUrl(): string
    {
        if ($this->product->image) {
            return Storage::url($this->product->image);
        }

        return 'https://via.placeholder.com/300x250?text=No+Image';
    }

    /**
     * Get the formatted price
     */
    public function getFormattedPrice(): string
    {
        return number_format((float) $this->product->price, 0, ',', '.') . 'đ';
    }

    /**
     * Check if product should show sticker
     */
    public function shouldShowSticker(): bool
    {
        return ($this->featured && $this->product->is_featured) || !$this->featured;
    }

    /**
     * Get sticker text
     */
    public function getStickerText(): string
    {
        if ($this->featured && $this->product->is_featured) {
            return 'Nổi bật';
        }

        return 'Mới';
    }
}
