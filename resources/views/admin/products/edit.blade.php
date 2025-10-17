@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Chỉnh sửa sản phẩm</h2>
                <p class="text-gray-600 dark:text-gray-400">Cập nhật thông tin chi tiết về sản phẩm</p>
            </div>

                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên sản phẩm *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Danh mục *</label>
                                <select name="category_id" id="category_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                        required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string)old('category_id', $product->category_id) == (string)$category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả sản phẩm</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Nhập mô tả chi tiết về sản phẩm...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price and Stock -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Giá (VNĐ) *</label>
                                <input type="number" step="1000" name="price" id="price" value="{{ old('price', $product->price) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       required>
                                @error('price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Số lượng tồn kho *</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       required>
                                @error('stock')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                                    Sản phẩm nổi bật
                                </label>
                            </div>
                        </div>

                        <!-- Current Images -->
                        @if($product->image || $product->images)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ảnh hiện tại</h3>
                            <div class="grid grid-cols-5 gap-4">
                                @if($product->image)
                                    <div class="relative">
                                        <img src="{{ $product->image_url }}" alt="Main image" class="h-24 w-24 object-cover rounded-lg border">
                                        <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Chính</span>
                                    </div>
                                @endif
                                @if($product->images)
                                    @foreach($product->images as $image)
                                        <div class="relative">
                                            <img src="{{ asset($image) }}" alt="Additional image" class="h-24 w-24 object-cover rounded-lg border">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Images Section -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Cập nhật hình ảnh sản phẩm</h3>
                            
                            <!-- Main Image -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Ảnh chính mới</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Nhấp để thay thế</span> ảnh chính</p>
                                            <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 2MB)</p>
                                        </div>
                                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewMainImage(event)">
                                    </label>
                                </div>
                                <div id="main-image-preview" class="mt-2 hidden">
                                    <img id="main-preview" class="h-32 w-32 object-cover rounded-lg border">
                                </div>
                                @error('image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Additional Images -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Thêm ảnh bổ sung</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Nhấp để thêm</span> ảnh mới</p>
                                            <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 2MB mỗi ảnh)</p>
                                        </div>
                                        <input type="file" name="images[]" id="images" class="hidden" accept="image/*" multiple onchange="previewAdditionalImages(event)">
                                    </label>
                                </div>
                                <div id="additional-images-preview" class="mt-2 hidden">
                                    <div class="grid grid-cols-5 gap-2" id="additional-previews"></div>
                                </div>
                                @error('images.*')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Thông số kỹ thuật</h3>
                            <div id="specifications-container" class="space-y-3">
                                @if($product->specifications && count($product->specifications) > 0)
                                    @foreach($product->specifications as $key => $value)
                                        <div class="flex gap-3 specification-row">
                                            <input type="text" name="spec_keys[]" value="{{ $key }}" placeholder="Tên thông số" 
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <input type="text" name="spec_values[]" value="{{ $value }}" placeholder="Giá trị" 
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex gap-3 specification-row">
                                        <input type="text" name="spec_keys[]" placeholder="Tên thông số (VD: CPU)" 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <input type="text" name="spec_values[]" placeholder="Giá trị (VD: Intel Core i7)" 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addSpecification()" class="mt-3 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                Thêm thông số
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                            <a href="{{ route('admin.products.index') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Hủy
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Cập nhật sản phẩm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewMainImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('main-image-preview').classList.remove('hidden');
                    document.getElementById('main-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function previewAdditionalImages(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('additional-previews');
            const parentContainer = document.getElementById('additional-images-preview');
            
            if (files.length > 0) {
                parentContainer.classList.remove('hidden');
                previewContainer.innerHTML = '';
                
                for (let i = 0; i < Math.min(files.length, 5); i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-20 w-20 object-cover rounded-lg border';
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }

        function addSpecification() {
            const container = document.getElementById('specifications-container');
            const newRow = document.createElement('div');
            newRow.className = 'flex gap-3 specification-row';
            newRow.innerHTML = `
                <input type="text" name="spec_keys[]" placeholder="Tên thông số (VD: CPU)" 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="text" name="spec_values[]" placeholder="Giá trị (VD: Intel Core i7)" 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            container.appendChild(newRow);
        }

        function removeSpecification(button) {
            const container = document.getElementById('specifications-container');
            if (container.children.length > 1) {
                button.parentElement.remove();
            }
        }
    </script>
</div>
</div>
@endsection
