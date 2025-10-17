<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Get products by category
     */
    public function getByCategory(Category $category): Collection
    {
        return $this->model->where('category_id', $category->id)->get();
    }

    /**
     * Get featured products
     */
    public function getFeatured(int $limit = 10): Collection
    {
        return $this->model
            ->where('is_featured', true)
            ->where('stock', '>', 0)
            ->limit($limit)
            ->get();
    }

    /**
     * Get products by price range
     */
    public function getByPriceRange(float $minPrice, float $maxPrice): Collection
    {
        return $this->model
            ->where('price', '>=', $minPrice)
            ->where('price', '<=', $maxPrice)
            ->get();
    }

    /**
     * Get in stock products
     */
    public function getInStock(): Collection
    {
        return $this->model->where('stock', '>', 0)->get();
    }

    /**
     * Get out of stock products
     */
    public function getOutOfStock(): Collection
    {
        return $this->model->where('stock', '<=', 0)->get();
    }

    /**
     * Get low stock products
     */
    public function getLowStock(int $threshold = 10): Collection
    {
        return $this->model
            ->where('stock', '>', 0)
            ->where('stock', '<=', $threshold)
            ->get();
    }

    /**
     * Search products
     */
    public function search(string $query): Collection
    {
        return $this->model
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('specifications', 'like', "%{$query}%")
            ->get();
    }

    /**
     * Get related products
     */
    public function getRelated(Product $product, int $limit = 4): Collection
    {
        return $this->model
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get popular products
     */
    public function getPopular(int $limit = 8): Collection
    {
        return $this->model
            ->where('stock', '>', 0)
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get new products
     */
    public function getNew(int $limit = 8): Collection
    {
        return $this->model
            ->where('stock', '>', 0)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get products with filters
     */
    public function getWithFilters(array $filters): LengthAwarePaginator
    {
        $query = $this->model->with('category');

        // Category filter
        if (isset($filters['category_id']) && $filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }

        // Search filter
        if (isset($filters['search']) && $filters['search']) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('specifications', 'like', "%{$searchTerm}%");
            });
        }

        // Price range filter
        if (isset($filters['min_price']) && $filters['min_price']) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price']) && $filters['max_price']) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Stock filter
        if (isset($filters['in_stock']) && $filters['in_stock']) {
            $query->where('stock', '>', 0);
        }

        // Sorting
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest();
        }

        return $query->paginate($filters['per_page'] ?? 12);
    }

    /**
     * Get paginated products with relations and ordering
     */
    public function getPaginated(array $relations = [], array $orderBy = [], int $perPage = 15)
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        foreach ($orderBy as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get filtered products
     */
    public function getFiltered(array $filters = [])
    {
        $query = $this->model->with('category');

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        // Apply price filters
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Apply stock filter
        if (!empty($filters['in_stock'])) {
            $query->where('stock', '>', 0);
        }

        // Apply sorting
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest();
        }

        return $query->paginate(config('shop.pagination.products_per_page', 12))->withQueryString();
    }

    /**
     * Get related products
     */
    public function getRelatedProducts(Product $product, int $limit = 4)
    {
        return $this->model->with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }

    /**
     * Increment product views
     */
    public function incrementViews(Product $product): bool
    {
        return $product->increment('views');
    }

    /**
     * Update stock quantity
     */
    public function updateStock(Product $product, int $quantity): bool
    {
        return $product->update(['stock' => $quantity]);
    }

    /**
     * Decrease stock quantity
     */
    public function decreaseStock(Product $product, int $quantity): bool
    {
        if ($product->stock >= $quantity) {
            return $product->decrement('stock', $quantity);
        }

        return false;
    }

    /**
     * Increase stock quantity
     */
    public function increaseStock(Product $product, int $quantity): bool
    {
        return $product->increment('stock', $quantity);
    }
}