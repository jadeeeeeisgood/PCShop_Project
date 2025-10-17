<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Get products with filters and pagination
     */
    public function getProducts($filters, ?Category $category = null): LengthAwarePaginator
    {
        // Convert Request to array if needed
        if ($filters instanceof Request) {
            $filterArray = [
                'category' => $filters->get('category'),
                'search' => $filters->get('search'),
                'min_price' => $filters->get('min_price'),
                'max_price' => $filters->get('max_price'),
                'price_range' => $filters->get('price_range'),
                'in_stock' => $filters->get('in_stock'),
                'sort' => $filters->get('sort', 'newest'),
            ];
        } else {
            $filterArray = $filters;
        }

        $query = Product::with('category')->where('is_active', true);

        // Apply category filter
        if ($category) {
            $query->where('category_id', $category->id);
        } elseif (!empty($filterArray['category'])) {
            $query->where('category_id', $filterArray['category']);
        }

        // Apply search filter
        if (!empty($filterArray['search'])) {
            $searchTerm = $filterArray['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('specifications', 'like', "%{$searchTerm}%");
            });
        }

        // Apply price range filter (predefined ranges)
        if (!empty($filterArray['price_range'])) {
            $priceRange = explode('-', $filterArray['price_range']);
            if (count($priceRange) == 2) {
                $minPrice = (int) $priceRange[0];
                $maxPrice = (int) $priceRange[1];

                $query->where('price', '>=', $minPrice);
                if ($maxPrice < 999999999) { // Not the "above" range
                    $query->where('price', '<=', $maxPrice);
                }
            }
        } else {
            // Apply custom price filters (only if no predefined range is selected)
            if (!empty($filterArray['min_price'])) {
                $query->where('price', '>=', $filterArray['min_price']);
            }

            if (!empty($filterArray['max_price'])) {
                $query->where('price', '<=', $filterArray['max_price']);
            }
        }

        // Apply stock filter
        if (!empty($filterArray['in_stock'])) {
            $query->where('stock', '>', 0);
        }

        // Apply sorting
        $sort = $filterArray['sort'] ?? 'newest';
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
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default: // newest
                $query->latest();
        }

        return $query->paginate(config('shop.pagination.products_per_page', 12))->withQueryString();
    }

    /**
     * Get featured products
     */
    public function getFeaturedProducts(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('category')
            ->where('is_featured', true)
            ->where('stock', '>', 0)
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get new products
     */
    public function getNewProducts(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('category')
            ->where('stock', '>', 0)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get popular products
     */
    public function getPopularProducts(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('category')
            ->where('stock', '>', 0)
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get related products
     */
    public function getRelatedProducts(Product $product, int $limit = 4): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }    /**
         * Increment product views
         */
    public function incrementViews(Product $product): void
    {
        $product->increment('views');
    }

    /**
     * Prepare product data from request
     */
    public function prepareProductData(\Illuminate\Http\Request $request): array
    {
        $data = $request->only([
            'name',
            'category_id',
            'description',
            'price',
            'stock',
            'is_featured'
        ]);

        $data['slug'] = \Illuminate\Support\Str::slug($request->name);
        $data['is_featured'] = $request->boolean('is_featured');

        // Handle main image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle additional images
        $additionalImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $additionalImages[] = $image->store('products', 'public');
            }
        }
        $data['images'] = $additionalImages;

        // Process specifications
        $specifications = [];
        if ($request->filled('spec_keys') && $request->filled('spec_values')) {
            $keys = $request->spec_keys;
            $values = $request->spec_values;

            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $specifications[$key] = $values[$index];
                }
            }
        }
        $data['specifications'] = $specifications;

        return $data;
    }

    /**
     * Create new product
     */
    public function createProduct(array $data): Product
    {
        $product = Product::create($data);

        if (isset($data['image']) && $data['image']) {
            $this->handleImageUpload($product, $data['image']);
        }

        return $product;
    }

    /**
     * Update product
     */
    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);

        if (isset($data['image']) && $data['image']) {
            $this->handleImageUpload($product, $data['image']);
        }

        return $product;
    }

    /**
     * Delete product
     */
    public function deleteProduct(Product $product): bool
    {
        // Delete product images
        if ($product->image) {
            Storage::delete($product->image);
        }

        if ($product->additional_images) {
            $additionalImages = json_decode($product->additional_images, true);
            foreach ($additionalImages as $image) {
                Storage::delete($image);
            }
        }

        return $product->delete();
    }

    /**
     * Handle image upload
     */
    public function handleImageUpload(Product $product, $imageFile): string
    {
        // Delete old image if exists
        if ($product->image) {
            Storage::delete($product->image);
        }

        // Store new image
        $path = $imageFile->store('products', 'public');

        $product->update(['image' => $path]);

        return $path;
    }

    /**
     * Handle additional images upload
     */
    public function handleAdditionalImagesUpload(Product $product, array $imageFiles): array
    {
        $paths = [];

        foreach ($imageFiles as $file) {
            $path = $file->store('products', 'public');
            $paths[] = $path;
        }

        // Merge with existing images
        $existingImages = $product->additional_images
            ? json_decode($product->additional_images, true)
            : [];

        $allImages = array_merge($existingImages, $paths);

        $product->update(['additional_images' => json_encode($allImages)]);

        return $paths;
    }

    /**
     * Delete additional image
     */
    public function deleteAdditionalImage(Product $product, string $imagePath): bool
    {
        $additionalImages = $product->additional_images
            ? json_decode($product->additional_images, true)
            : [];

        $key = array_search($imagePath, $additionalImages);

        if ($key !== false) {
            unset($additionalImages[$key]);
            Storage::delete($imagePath);

            $product->update(['additional_images' => json_encode(array_values($additionalImages))]);

            return true;
        }

        return false;
    }

    /**
     * Get product statistics
     */
    public function getProductStats(): array
    {
        return [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)
                ->where('stock', '<=', 10)
                ->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
            'total_value' => Product::sum(\DB::raw('price * stock')),
        ];
    }

    /**
     * Search products with suggestions
     */
    public function searchSuggestions(string $query, int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name', 'price', 'image']);
    }
}