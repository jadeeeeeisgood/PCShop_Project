<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'low_stock':
                    $query->where('stock', '<=', 10);
                    break;
            }
        }

        // Sorting
        $sort = $request->get('sort', 'created_at_desc');
        switch ($sort) {
            case 'created_at_asc':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        // Export functionality
        if ($request->has('export')) {
            return $this->exportProducts($query);
        }

        $products = $query->paginate(15)->appends($request->query());
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Custom validation for image files based on extension
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'specifications' => 'nullable|array',
            'is_featured' => 'boolean'
        ]);

        // Validate image separately with custom logic
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($extension, $allowedExtensions)) {
                return back()->withErrors(['image' => 'Ảnh chính phải có định dạng: JPG, JPEG, PNG hoặc GIF.'])->withInput();
            }

            if ($file->getSize() > 2048 * 1024) { // 2MB in bytes
                return back()->withErrors(['image' => 'Ảnh chính không được vượt quá 2MB.'])->withInput();
            }
        }

        // Validate additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($extension, $allowedExtensions)) {
                    return back()->withErrors(["images.{$index}" => 'Ảnh bổ sung phải có định dạng: JPG, JPEG, PNG hoặc GIF.'])->withInput();
                }

                if ($file->getSize() > 2048 * 1024) { // 2MB in bytes
                    return back()->withErrors(["images.{$index}" => 'Mỗi ảnh bổ sung không được vượt quá 2MB.'])->withInput();
                }
            }
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle main image upload
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $uploadedImages = [];
            foreach ($request->file('images') as $image) {
                $uploadedImages[] = $this->uploadImage($image);
            }
            $data['images'] = $uploadedImages;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Basic validation
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'specifications' => 'nullable|array',
            'is_featured' => 'boolean'
        ]);

        // Validate image separately with custom logic
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($extension, $allowedExtensions)) {
                return back()->withErrors(['image' => 'Ảnh chính phải có định dạng: JPG, JPEG, PNG hoặc GIF.'])->withInput();
            }

            if ($file->getSize() > 2048 * 1024) { // 2MB in bytes
                return back()->withErrors(['image' => 'Ảnh chính không được vượt quá 2MB.'])->withInput();
            }
        }

        // Validate additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($extension, $allowedExtensions)) {
                    return back()->withErrors(["images.{$index}" => 'Ảnh bổ sung phải có định dạng: JPG, JPEG, PNG hoặc GIF.'])->withInput();
                }

                if ($file->getSize() > 2048 * 1024) { // 2MB in bytes
                    return back()->withErrors(["images.{$index}" => 'Mỗi ảnh bổ sung không được vượt quá 2MB.'])->withInput();
                }
            }
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                $oldImagePath = public_path($product->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            // Delete old images
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    $oldImagePath = public_path($oldImage);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }

            $uploadedImages = [];
            foreach ($request->file('images') as $image) {
                $uploadedImages[] = $this->uploadImage($image);
            }
            $data['images'] = $uploadedImages;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    public function destroy(Product $product)
    {
        // Delete images
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::delete('public/' . $image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }

    /**
     * Upload image and return the path
     */
    private function uploadImage($image)
    {
        // Create img directory if it doesn't exist
        $imgPath = public_path('img');
        if (!file_exists($imgPath)) {
            mkdir($imgPath, 0755, true);
        }

        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

        // Move file to public/img directory
        $image->move($imgPath, $filename);

        // Return relative path from public directory
        return 'img/' . $filename;
    }

    /**
     * Remove a specific image from product
     */
    public function removeImage(Request $request, Product $product)
    {
        $imageIndex = $request->input('image_index');
        $imageType = $request->input('image_type'); // 'main' or 'gallery'

        if ($imageType === 'main' && $product->image) {
            // Delete file from public/img directory
            $imagePath = public_path($product->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $product->update(['image' => null]);
        } elseif ($imageType === 'gallery' && $product->images && isset($product->images[$imageIndex])) {
            $images = $product->images;
            // Delete file from public/img directory
            $imagePath = public_path($images[$imageIndex]);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            unset($images[$imageIndex]);
            $product->update(['images' => array_values($images)]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Bulk actions for products
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'activate':
                $products->update(['is_active' => true]);
                $message = 'Đã kích hoạt ' . count($request->ids) . ' sản phẩm';
                break;
            case 'deactivate':
                $products->update(['is_active' => false]);
                $message = 'Đã vô hiệu hóa ' . count($request->ids) . ' sản phẩm';
                break;
            case 'delete':
                // Delete images first
                foreach ($products->get() as $product) {
                    if ($product->image) {
                        Storage::delete('public/' . $product->image);
                    }
                    if ($product->images) {
                        foreach ($product->images as $image) {
                            Storage::delete('public/' . $image);
                        }
                    }
                }
                $products->delete();
                $message = 'Đã xóa ' . count($request->ids) . ' sản phẩm';
                break;
        }

        return redirect()->route('admin.products.index')
            ->with('success', $message);
    }

    /**
     * Export products to Excel
     */
    private function exportProducts($query)
    {
        $products = $query->get();

        $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($file, [
                'ID',
                'Tên sản phẩm',
                'Danh mục',
                'Giá (VNĐ)',
                'Tồn kho',
                'Trạng thái',
                'Nổi bật',
                'Ngày tạo',
                'Ngày cập nhật'
            ]);

            // Data rows
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->category->name,
                    $product->price,
                    $product->stock,
                    $product->is_active ? 'Hoạt động' : 'Không hoạt động',
                    $product->is_featured ? 'Có' : 'Không',
                    $product->created_at->format('d/m/Y H:i'),
                    $product->updated_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}