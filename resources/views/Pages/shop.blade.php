@extends('layouts.welcome')

@section('content')
<div class="container py-5 mt-4" style="max-width: 1400px; margin: auto;">
    
    <div class="mb-5">
        <h2 class="text-uppercase mb-4 border-bottom pb-2" style="font-weight: 900; font-size: clamp(20px, 5vw, 32px); font-family: 'Syne', sans-serif;">
            {{ request()->filled('q') ? 'Search Results for "' . request('q') . '"' : 'All Products' }}
        </h2>
        
        <div id="product-grid-content">
            @if($products->count() > 0)
                @include('Pages.partials.product_grid', ['products' => $products])
            @else
                <div class="text-muted text-center py-5">
                    <i class="ti ti-search" style="font-size: 40px; color: #ddd; margin-bottom: 15px; display: block;"></i>
                    <p>No products found matching your search.</p>
                    <a href="{{ url('/shop') }}" class="btn btn-outline-dark mt-3 rounded-pill px-4">View All Products</a>
                </div>
            @endif
        </div>
        
        @if($products->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('css')
<style>
    /* Pull in elegant card styles so they render properly outside of home */
    .elegant-card {
        display: block;
        width: 100%;
    }
    .elegant-img-wrapper {
        background-color: #F8F8F8;
        width: 100%;
        aspect-ratio: 3 / 4;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .elegant-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .elegant-card:hover .elegant-img-wrapper img {
        transform: scale(1.03);
    }
    .elegant-title {
        font-size: 14px;
        font-weight: 500;
        color: #888;
        font-family: 'DM Sans', sans-serif;
    }
    .elegant-price {
        font-size: 12px;
        font-weight: 500;
        color: #666;
    }
    .elegant-wishlist-btn i {
        font-size: 16px;
        color: #ccc;
        transition: color 0.2s;
    }
    .elegant-wishlist-btn:hover i {
        color: #f44336;
    }
</style>
@endpush
