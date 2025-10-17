@extends('layouts.admin')

@section('title', 'Quản lý ảnh sản phẩm')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Quản lý ảnh sản phẩm: {{ $product->name }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">Thêm, xóa và sắp xếp ảnh cho sản phẩm</p>
                </div>

                <!-- Current Images -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ảnh hiện tại</h3>

                    @if($product->image || $product->images)
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="current-images">
                            <!-- Main Image -->
                            @if($product->image)
                                <div class="relative group">
                                    <img src="{{ $product->image_url }}" alt="Main image"
                                        class="h-32 w-full object-cover rounded-lg border-2 border-blue-500">
                                    <span
                                        class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Chính</span>
                                    <button onclick="deleteImage('main', '')"
                                        class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            <!-- Additional Images -->
                            @if($product->images)
                                @foreach($product->images as $index => $image)
                                    <div class="relative group">
                                        <img src="{{ asset($image) }}" alt="Additional image {{ $index + 1 }}"
                                            class="h-32 w-full object-cover rounded-lg border">
                                        <button onclick="deleteImage('additional', '{{ $index }}')"
                                            class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2">Chưa có ảnh nào</p>
                        </div>
                    @endif
                </div>

                <!-- Upload New Images -->
                <div class="border-t pt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thêm ảnh mới</h3>

                    <form action="{{ route('admin.products.upload-images', $product) }}" method="POST"
                        enctype="multipart/form-data" id="upload-form">
                        @csrf

                        <!-- Main Image Upload -->
                        @if(!$product->image)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh chính *</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="main-image"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Nhấp để
                                                    tải lên</span> ảnh chính</p>
                                            <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 2MB)</p>
                                        </div>
                                        <input type="file" name="main_image" id="main-image" class="hidden" accept="image/*"
                                            onchange="previewMainImage(event)">
                                    </label>
                                </div>
                                <div id="main-image-preview" class="mt-2 hidden">
                                    <img id="main-preview" class="h-32 w-32 object-cover rounded-lg border">
                                </div>
                            </div>
                        @endif

                        <!-- Additional Images Upload -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh bổ sung</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="additional-images"
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Nhấp để
                                                tải lên</span> nhiều ảnh</p>
                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 2MB mỗi ảnh)</p>
                                    </div>
                                    <input type="file" name="additional_images[]" id="additional-images" class="hidden"
                                        accept="image/*" multiple onchange="previewAdditionalImages(event)">
                                </label>
                            </div>
                            <div id="additional-images-preview" class="mt-2 hidden">
                                <div class="grid grid-cols-5 gap-2" id="additional-previews"></div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.products.show', $product) }}"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Quay lại
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Tải lên ảnh
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        function previewMainImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
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
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-20 w-20 object-cover rounded-lg border';
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }

        function deleteImage(type, index) {
            if (!confirm('Bạn có chắc chắn muốn xóa ảnh này?')) return;

            fetch(`{{ route('admin.products.delete-image', $product) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    index: index
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra khi xóa ảnh');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa ảnh');
                });
        }
    </script>
    </div>
    </div>
@endsection
