<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;

    public function __construct(ProductService $productService, ProductRepository $productRepository)
    {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->getPaginated(
            relations: ['category'],
            orderBy: ['created_at' => 'desc'],
            perPage: config('shop.pagination.admin_per_page', 10)
        );

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'spec_keys.*' => 'nullable|string',
            'spec_values.*' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        try {
            $productData = $this->productService->prepareProductData($request);
            $this->productRepository->create($productData);

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được tạo thành công.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo sản phẩm: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'spec_keys.*' => 'nullable|string',
            'spec_values.*' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $additionalImages = $product->images ?: [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $additionalImages[] = $image->store('products', 'public');
            }
        }

        // Process specifications from separate arrays
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

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
            'images' => $additionalImages,
            'specifications' => $specifications,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function destroy(Product $product)
    {
        // Delete images
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }

    public function frontendIndex(Request $request)
    {
        $filters = $request->only(['search', 'category', 'min_price', 'max_price', 'price_range', 'in_stock', 'sort']);

        try {
            $products = $this->productService->getProducts($filters);
            $categories = Category::withCount('products')->orderBy('name')->get();

            return view('products.index', compact('products', 'categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi tải danh sách sản phẩm.');
        }
    }

    public function frontendShow(Product $product)
    {
        try {
            $this->productService->incrementViews($product);
            $product->load('category');

            $relatedProducts = $this->productRepository->getRelatedProducts($product, 4);

            return view('products.show', compact('product', 'relatedProducts'));
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi tải thông tin sản phẩm.');
        }
    }

    public function frontendByCategory(Category $category, Request $request)
    {
        $filters = $request->only(['search', 'min_price', 'max_price', 'price_range', 'in_stock', 'sort']);
        $filters['category'] = $category->id;

        try {
            $products = $this->productService->getProducts($filters);
            $categories = Category::withCount('products')->orderBy('name')->get();
            $selectedCategory = $category;

            return view('products.index', compact('products', 'categories', 'selectedCategory'));
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi tải danh sách sản phẩm.');
        }
    }

    public function manageImages(Product $product)
    {
        return view('admin.products.images', compact('product'));
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Upload main image
        if ($request->hasFile('main_image')) {
            // Delete old main image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('main_image')->store('products', 'public');
        }

        // Upload additional images
        if ($request->hasFile('additional_images')) {
            $additionalImages = $product->images ?: [];

            foreach ($request->file('additional_images') as $image) {
                $additionalImages[] = $image->store('products', 'public');
            }

            $product->images = $additionalImages;
        }

        $product->save();

        return redirect()->route('admin.products.images', $product)
            ->with('success', 'Ảnh đã được tải lên thành công.');
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:main,additional',
            'index' => 'nullable|integer',
        ]);

        $product = Product::findOrFail($request->product_id);
        $type = $request->input('type');
        $index = $request->input('index');

        try {
            if ($type === 'main') {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                    $product->image = null;
                }
            } elseif ($type === 'additional') {
                $images = $product->images ?: [];

                if (isset($images[$index])) {
                    Storage::disk('public')->delete($images[$index]);
                    unset($images[$index]);
                    $product->images = array_values($images); // Re-index array
                }
            }

            $product->save();

            return response()->json(['success' => true, 'message' => 'Ảnh đã được xóa thành công.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa ảnh.'], 500);
        }
    }
}
