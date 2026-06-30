@extends('layouts.welcome')

@section('content')
<div class="container py-5 mt-4" style="max-width: 1400px; margin: auto;">
    
    <!-- SUB CATEGORIES SECTION -->
    <div class="mb-5">
        <h2 class="text-uppercase mb-4 border-bottom pb-2" style="font-weight: 900; font-size: clamp(20px, 5vw, 32px); font-family: 'Syne', sans-serif;">
            Sub Categories
        </h2>
        
        @if($subCategories->count() > 0)
        <div class="swiper sub-category-swiper position-relative" style="padding: 0 30px;">
            <div class="swiper-wrapper">
                @foreach($subCategories as $sub)
                <div class="swiper-slide">
                    <a href="#" class="text-decoration-none text-center d-block category-filter-link" data-id="{{ $sub->id }}">
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-3" style="aspect-ratio: 1/1; overflow: hidden; border: 1px solid #f0f0f0;">
                            <img src="{{ $sub->image ?? 'https://via.placeholder.com/200' }}" alt="{{ $sub->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <span class="text-dark" style="font-size: 14px; font-weight: 600;">{{ $sub->name }}</span>
                    </a>
                </div>
                @endforeach
            </div>
            <!-- Navigation Buttons -->
            <div class="swiper-button-next sub-cat-next" style="color: #222; transform: scale(0.6);"></div>
            <div class="swiper-button-prev sub-cat-prev" style="color: #222; transform: scale(0.6);"></div>
        </div>
        @else
        <div class="text-muted">
            <p>No sub-categories available.</p>
        </div>
        @endif
    </div>

    <!-- ALL PRODUCTS SECTION -->
    <div class="mb-5">
        <h2 class="text-uppercase mb-4 border-bottom pb-2" style="font-weight: 900; font-size: clamp(20px, 5vw, 32px); font-family: 'Syne', sans-serif;">
            All Products
        </h2>
        
        <!-- Product Grid Container (AJAX Target) -->
        <div id="product-grid-container" class="position-relative">
            <div id="loading-spinner" class="position-absolute w-100 h-100 d-none justify-content-center align-items-center" style="background: rgba(255,255,255,0.7); z-index: 10; min-height: 200px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            
            <div id="product-grid-content">
                @include('Pages.partials.product_grid', ['products' => $products])
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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
    .category-filter-link img {
        transition: transform 0.3s ease;
    }
    .category-filter-link:hover img {
        transform: scale(1.1);
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Sub Category Slider
        if(document.querySelector('.sub-category-swiper')) {
            new Swiper('.sub-category-swiper', {
                slidesPerView: 6,
                spaceBetween: 30,
                loop: false,
                navigation: {
                    nextEl: '.sub-cat-next',
                    prevEl: '.sub-cat-prev',
                },
                breakpoints: {
                    320: { slidesPerView: 2, spaceBetween: 15 },
                    576: { slidesPerView: 3, spaceBetween: 20 },
                    768: { slidesPerView: 4, spaceBetween: 20 },
                    1024: { slidesPerView: 5, spaceBetween: 25 },
                    1200: { slidesPerView: 6, spaceBetween: 30 }
                }
            });
        }

        const links = document.querySelectorAll('.category-filter-link');
        const spinner = document.getElementById('loading-spinner');
        const content = document.getElementById('product-grid-content');

        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const subCatId = this.getAttribute('data-id');

                // Show spinner
                spinner.classList.remove('d-none');
                spinner.classList.add('d-flex');

                // Fetch new products via AJAX
                fetch(`{{ route('frontend.category.ajax', $mainCategory->slug) }}?sub_category_id=${subCatId}`)
                    .then(response => response.text())
                    .then(html => {
                        content.innerHTML = html;
                    })
                    .catch(error => console.error('Error fetching products:', error))
                    .finally(() => {
                        // Hide spinner
                        spinner.classList.add('d-none');
                        spinner.classList.remove('d-flex');
                    });
            });
        });
    });
</script>
@endpush
