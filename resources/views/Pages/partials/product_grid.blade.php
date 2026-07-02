@if($products->count() > 0)
<div class="row">
    @foreach($products as $product)
    <div class="col-6 col-md-3 mb-5">
        <article class="elegant-card">
            <a href="{{ route('frontend.product', $product->slug) }}" class="text-decoration-none">
                <div class="elegant-img-wrapper">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/400x600' }}" alt="{{ $product->title }}">
                </div>
            </a>
            <div class="elegant-info mt-3">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h3 class="elegant-title m-0 text-truncate" style="max-width: 85%;">{{ $product->title }}</h3>
                    <button class="elegant-wishlist-btn bg-transparent border-0 p-0" onclick="toggleWishlist(event, {{ $product->id }}, this)">
                        <i class="ti {{ in_array($product->id, session()->get('wishlist', [])) ? 'ti-heart-filled text-danger' : 'ti-heart text-muted' }}"></i>
                    </button>
                </div>
                <div class="elegant-price text-muted">
                    ${{ number_format($product->price, 2) }}
                </div>
            </div>
        </article>
    </div>
    @endforeach
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>
@else
<div class="text-center py-5">
    <i class="ti ti-package text-muted" style="font-size: 48px;"></i>
    <h3 class="mt-3 text-muted">No products found.</h3>
</div>
@endif
