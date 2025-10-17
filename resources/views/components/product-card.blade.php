<div class="product-item">
    <div class="pi-img-wrapper">
        <img src="{{ $getImageUrl() }}" class="img-fluid" alt="{{ $product->name }}">

        <div class="overlay">
            <a href="{{ $getImageUrl() }}" class="btn btn-light btn-sm me-2" data-fancybox="gallery">
                <i class="fa fa-search-plus"></i> Zoom
            </a>
            <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">
                <i class="fa fa-eye"></i> Xem
            </a>
        </div>

        @if($shouldShowSticker())
            <div class="sticker {{ $featured && $product->is_featured ? 'sticker-featured' : 'sticker-new' }}">
                {{ $getStickerText() }}
            </div>
        @endif
    </div>

    <div class="p-3">
        @if($titleLimit)
            <h6><a href="{{ route('products.show', $product) }}"
                    class="text-decoration-none text-dark">{{ $getTitle() }}</a></h6>
        @else
            <h5><a href="{{ route('products.show', $product) }}"
                    class="text-decoration-none text-dark">{{ $getTitle() }}</a></h5>
        @endif

        <div class="pi-price">{{ $getFormattedPrice() }}</div>

        @if($showStock || $showViews)
            <div class="d-flex justify-content-between align-items-center mt-2">
                @if($showStock)
                    <small class="text-muted">Còn: {{ $product->stock }} sản phẩm</small>
                @elseif($showViews)
                    <small class="text-muted">
                        <i class="fa fa-eye"></i> {{ $product->views }} lượt xem
                    </small>
                @endif

                <button onclick="addToCart({{ $product->id }})" class="btn add2cart btn-sm">
                    <i class="fa fa-shopping-cart"></i> {{ $showStock || $showViews ? '' : 'Thêm' }}
                </button>
            </div>
        @else
            <button onclick="addToCart({{ $product->id }})" class="btn add2cart mt-2">
                <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
            </button>
        @endif
    </div>
</div>