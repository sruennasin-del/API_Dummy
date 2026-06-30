@extends('layouts.welcome')
@section('content')



{{-- ════════════════════════════════
     CATEGORY TABS
════════════════════════════════ --}}
<div class="cat-bar" role="navigation" aria-label="Product categories">
    <ul class="cat-tabs">
        <li class="active"><a href="{{ url('/all-sub-products') }}"><i class="ti ti-layout-grid" aria-hidden="true"></i>All Shop</a></li>
        @php
        $icons = [
        'mens-wear' => 'ti ti-shirt',
        'womens-wear' => 'ti ti-hanger',
        'kids-baby' => 'ti ti-baby-carriage',
        'shoes-accessories' => 'ti ti-shoe',
        ];
        @endphp
        @foreach($categories as $cat)
        <li>
            <a href="{{ url('/sub-product/' . $cat->slug) }}">
                <i class="{{ $icons[$cat->slug] ?? 'ti ti-tag' }}" aria-hidden="true"></i>{{ $cat->name }}
            </a>
        </li>
        @endforeach
    </ul>
</div>

{{-- ════════════════════════════════
     HERO SLIDER
════════════════════════════════ --}}
<section class="hero" aria-label="Featured promotions" aria-roledescription="carousel">

    <div class="slides-track" id="slidesTrack">

        @if($banners->count() > 0)
            @foreach($banners as $i => $banner)
            <div class="slide" role="group" aria-roledescription="slide" aria-label="Slide {{ $i+1 }} of {{ $banners->count() }}"
                 style="{{ $banner->image ? 'background: #222;' : 'background: ' . ($banner->bg_gradient ?? 'linear-gradient(130deg,#FF6B1A 0%,#FF9C5B 55%,#FFD6BB 100%)') . ';' }} min-height:420px;">
                <div class="slide-body">
                    @if($banner->tag)<span class="slide-tag">{{ $banner->tag }}</span>@endif
                    <h1>{{ $banner->title }}@if($banner->subtitle)<br>{{ $banner->subtitle }}@endif</h1>
                    @if($banner->description)<p>{{ $banner->description }}</p>@endif
                    <div class="slide-btns">
                        @if($banner->btn_primary_label)
                            <a href="{{ $banner->btn_primary_url ?? '/shop' }}" class="btn-hero btn-white">
                                <i class="ti ti-shopping-bag" aria-hidden="true"></i>{{ $banner->btn_primary_label }}
                            </a>
                        @endif
                        @if($banner->btn_secondary_label)
                            <a href="{{ $banner->btn_secondary_url ?? '#' }}" class="btn-hero btn-outline">
                                {{ $banner->btn_secondary_label }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="slide-img-area" aria-hidden="true">
                    @if($banner->image)
                        <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title }}"
                             style="width:100%;height:100%;object-fit:cover;border-radius:14px;max-height:340px;">
                    @else
                        <div class="slide-img-box">
                            <i class="ti ti-photo"></i>
                            <span>Banner</span>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            {{-- Fallback: hard-coded slides if no DB banners yet --}}
            <div class="slide slide-1" role="group">
                <div class="slide-body">
                    <span class="slide-tag">🔥 Limited Offer</span>
                    <h1>Summer Sale<br>Up to 50% Off</h1>
                    <p>Shop the hottest deals of the season. Electronics, fashion, home goods — all at unbeatable prices.</p>
                    <div class="slide-btns">
                        <a href="/shop" class="btn-hero btn-white"><i class="ti ti-shopping-bag"></i>Shop Now</a>
                        <a href="#" class="btn-hero btn-outline">View Deals</a>
                    </div>
                </div>
                <div class="slide-img-area" aria-hidden="true"><div class="slide-img-box"><i class="ti ti-device-laptop"></i><span>Featured</span></div></div>
            </div>
            <div class="slide slide-2" role="group">
                <div class="slide-body">
                    <span class="slide-tag" style="color:#FFD6BB;border-color:rgba(255,166,100,.4);background:rgba(255,107,26,.18)">✨ New Arrivals</span>
                    <h1>Next-Gen<br>Electronics</h1>
                    <p>Discover the latest smartphones, laptops, and gadgets.</p>
                    <div class="slide-btns"><a href="/shop" class="btn-hero btn-orange"><i class="ti ti-device-mobile"></i>Explore Tech</a></div>
                </div>
                <div class="slide-img-area" aria-hidden="true"><div class="slide-img-box" style="border-color:rgba(255,107,26,.4)"><i class="ti ti-device-mobile"></i><span>New Collection</span></div></div>
            </div>
        @endif

    </div>

    {{-- Arrows --}}
    <div class="slider-arrows" aria-hidden="true">
        <button class="arrow-btn" id="prevBtn" aria-label="Previous slide"><i class="ti ti-chevron-left"></i></button>
        <button class="arrow-btn" id="nextBtn" aria-label="Next slide"><i class="ti ti-chevron-right"></i></button>
    </div>

    {{-- Dots: generated dynamically by JS --}}
    <div class="slider-dots" role="tablist" aria-label="Slide navigation" id="sliderDots"></div>


</section>

{{-- ════════════════════════════════
         MAIN CATEGORY BANNERS
    ════════════════════════════════ --}}
<div class="category-banners-container my-5 px-2" style="max-width: 1400px; margin: auto;">
    <div class="row g-4">
        @foreach($categories as $cat)
        @if($cat->layout_type == 'landscape')
        {{-- Landscape: 2 per row on desktop (col-md-6) --}}
        <div class="col-12 col-md-6">
            <a href="{{ url('/sub-product/' . $cat->slug) }}" class="category-banner-card landscape">
                <img src="{{ $cat->image ?? 'https://via.placeholder.com/800x400' }}" alt="{{ $cat->name }}">
                <div class="banner-overlay">
                    <h3>{{ $cat->name }}</h3>
                    <span>Shop Now <i class="ti ti-arrow-right"></i></span>
                </div>
            </a>
        </div>
        @else
        <div class="col-12 col-md-4">
            <a href="{{ route('frontend.category', $cat->slug) }}" class="category-banner-card portrait">
                <img src="{{ $cat->image ?? 'https://via.placeholder.com/400x600' }}" alt="{{ $cat->name }}">
                <div class="banner-overlay">
                    <h3>{{ $cat->name }}</h3>
                    <span>Shop Now <i class="ti ti-arrow-right"></i></span>
                </div>
            </a>
        </div>
        @endif
        @endforeach
    </div>
</div>

{{-- ════════════════════════════════
         PRODUCT COLLECTIONS
    ════════════════════════════════ --}}
@foreach($collections as $index => $collection)
@if($collection->products->count() > 0)
<div class="collection-section mt-5 mb-5 px-2" style="max-width: 1400px; margin: auto;">
    
    {{-- Minimalist Header --}}
    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2" style="border-color: #eee !important;">
        <h2 class="m-0 text-dark text-uppercase" style="font-weight: 900; font-size: clamp(18px, 4vw, 26px); letter-spacing: 0.5px; font-family: 'Syne', sans-serif;">
            {{ $collection->name }}
        </h2>
        <a href="{{ route('frontend.collection', $collection->slug) }}" class="text-decoration-none" style="font-size: 13px; color: #666; font-weight: 500;">
            Shop more &rsaquo;
        </a>
    </div>
    
    {{-- Swiper Slider --}}
    <div class="swiper collection-swiper-{{ $index }} position-relative" style="padding: 0 10px;">
        <div class="swiper-wrapper">
            @foreach($collection->products as $product)
            <div class="swiper-slide">
                <article class="elegant-card">
                    <a href="{{ route('frontend.product', $product->slug) }}" class="text-decoration-none">
                        <div class="elegant-img-wrapper">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/400x600' }}" alt="{{ $product->title }}">
                        </div>
                    </a>
                    <div class="elegant-info mt-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h3 class="elegant-title m-0 text-truncate" style="max-width: 85%;">{{ $product->title }}</h3>
                            <button class="elegant-wishlist-btn bg-transparent border-0 p-0 text-muted">
                                <i class="ti ti-heart"></i>
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
        
        <!-- Navigation Buttons -->
        <div class="swiper-button-next elegant-swiper-btn next-{{ $index }}"></div>
        <div class="swiper-button-prev elegant-swiper-btn prev-{{ $index }}"></div>
    </div>
</div>
@endif
@endforeach

{{-- ════════════════════════════════
         MAIN CATEGORY SLIDERS
    ════════════════════════════════ --}}
@foreach($categories as $index => $cat)
@if(isset($cat->latest_products) && $cat->latest_products->count() > 0)
<div class="collection-section mt-5 mb-5 px-2" style="max-width: 1400px; margin: auto;">
    
    {{-- Minimalist Header --}}
    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2" style="border-color: #eee !important;">
        <h2 class="m-0 text-dark text-uppercase" style="font-weight: 900; font-size: clamp(18px, 4vw, 26px); letter-spacing: 0.5px; font-family: 'Syne', sans-serif;">
            {{ $cat->name }}
        </h2>
        <a href="{{ url('/category/' . $cat->slug) }}" class="text-decoration-none" style="font-size: 13px; color: #666; font-weight: 500;">
            Shop more &rsaquo;
        </a>
    </div>
    
    {{-- Swiper Slider --}}
    <div class="swiper collection-swiper-99{{ $index }} position-relative" style="padding: 0 10px;">
        <div class="swiper-wrapper">
            @foreach($cat->latest_products as $product)
            <div class="swiper-slide">
                <article class="elegant-card">
                    <a href="{{ route('frontend.product', $product->slug) }}" class="text-decoration-none">
                        <div class="elegant-img-wrapper">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/400x600' }}" alt="{{ $product->title }}">
                        </div>
                    </a>
                    <div class="elegant-info mt-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h3 class="elegant-title m-0 text-truncate" style="max-width: 85%;">{{ $product->title }}</h3>
                            <button class="elegant-wishlist-btn bg-transparent border-0 p-0 text-muted">
                                <i class="ti ti-heart"></i>
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
        
        <!-- Navigation Buttons -->
        <div class="swiper-button-next elegant-swiper-btn next-99{{ $index }}"></div>
        <div class="swiper-button-prev elegant-swiper-btn prev-99{{ $index }}"></div>
    </div>
</div>
@endif
@endforeach

@push('js')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swipers = document.querySelectorAll('.swiper[class*="collection-swiper-"]');
        swipers.forEach(swiperEl => {
            const classList = Array.from(swiperEl.classList);
            const swiperClass = classList.find(c => c.startsWith('collection-swiper-'));
            if (!swiperClass) return;
            const index = swiperClass.split('-')[2];
            new Swiper('.' + swiperClass, {
                slidesPerView: 5,
                slidesPerGroup: 1,
                spaceBetween: 24,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.next-' + index,
                    prevEl: '.prev-' + index,
                },
                breakpoints: {
                    320: { slidesPerView: 2, spaceBetween: 10 },
                    576: { slidesPerView: 3, spaceBetween: 16 },
                    768: { slidesPerView: 4, spaceBetween: 24 },
                    1024: { slidesPerView: 5, spaceBetween: 30 }
                }
            });
        });
    });

    $(document).on('click', '.btn-cart', function() {
        let btn = $(this);
        let id = btn.data('id');
        let title = btn.data('title');
        let price = btn.data('price');
        let thumbnail = btn.data('thumbnail');

        // Add loading state
        let originalText = btn.html();
        btn.html('<i class="ti ti-loader ti-spin"></i> Adding').prop('disabled', true);

        $.ajax({
            url: '/cart/add',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
                title: title,
                price: price,
                thumbnail: thumbnail
            },
            success: function(res) {
                // Update cart count instantly
                $('#cart-count').text(res.cart_count);

                // Show success feedback
                btn.html('<i class="ti ti-check text-success"></i> Added');
                setTimeout(() => {
                    btn.html(originalText).prop('disabled', false);
                }, 2000);
            },
            error: function(err) {
                console.log(err);
                btn.html(originalText).prop('disabled', false);
            }
        });
    });
</script>
@endpush
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    /* =========================
        ELEGANT SLIDER
    ========================== */
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
    
    .elegant-swiper-btn {
        background-color: #999;
        width: 26px !important;
        height: 26px !important;
        border-radius: 50%;
        color: #ffffff !important;
        transition: all 0.2s ease;
        top: 40% !important; 
        margin-top: -13px !important;
        z-index: 10;
        opacity: 0.8;
    }
    
    .elegant-swiper-btn.swiper-button-prev {
        left: 0px !important;
    }
    
    .elegant-swiper-btn.swiper-button-next {
        right: 0px !important;
    }
    
    .elegant-swiper-btn:after {
        font-size: 10px !important;
        font-weight: bold;
    }
    
    .elegant-swiper-btn:hover {
        background-color: #555 !important;
        opacity: 1;
    }

    /* =========================
        CATEGORY BANNERS
    ========================== */
    .category-banner-card {
        display: block;
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #f1f5f9;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .category-banner-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(255, 107, 26, 0.15);
    }

    .category-banner-card img {
        width: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .category-banner-card.landscape img {
        height: 240px;
    }

    .category-banner-card.portrait img {
        height: 320px;
    }

    .category-banner-card:hover img {
        transform: scale(1.05);
    }

    .banner-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 30px 20px 20px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 100%);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-end;
    }

    .banner-overlay h3 {
        margin: 0 0 8px 0;
        font-family: 'Syne', sans-serif;
        font-size: 24px;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .banner-overlay span {
        font-size: 14px;
        font-weight: 600;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        transition: background 0.2s, color 0.2s;
    }

    .category-banner-card:hover .banner-overlay span {
        background: var(--primary);
        color: white;
    }

    /* =========================
        MENU SECTION
    ========================== */

    .menu-wrapper {
        padding: 30px 15px 100px;
        max-width: 1400px;
        margin: auto;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 22px;
    }

    .menu-card {
        background: white;
        border-radius: 24px;
        padding: 30px 20px;
        text-align: center;
        text-decoration: none;
        color: #222;
        border: 2px solid #ffe3c4;
        transition: .3s;
        box-shadow: 0 5px 18px rgba(0, 0, 0, .05);
    }

    .menu-card:hover {
        transform: translateY(-6px);
        border-color: #ff7a00;
        box-shadow: 0 12px 25px rgba(255, 122, 0, .18);
    }

    .menu-icon {
        width: 85px;
        height: 85px;
        background: #fff2e5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        margin-bottom: 18px;
        color: #ff7a00;
        font-size: 38px;
    }

    .menu-name {
        font-size: 18px;
        font-weight: 700;
    }

    /* =========================
        DESKTOP TOP NAV
    ========================== */

    .top-nav {
        background: white;
        padding: 14px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .logo {
        color: #ff7a00;
        font-size: 28px;
        font-weight: 800;
    }

    .nav-links {
        display: flex;
        gap: 25px;
    }

    .nav-links a {
        text-decoration: none;
        color: #333;
        font-weight: 600;
        transition: .3s;
    }

    .nav-links a:hover {
        color: #ff7a00;
    }

    /* =========================
        MOBILE BOTTOM NAV
    ========================== */

    .mobile-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        border-top: 1px solid #eee;
        display: none;
        justify-content: space-around;
        padding: 12px 0;
        z-index: 999;
    }

    .mobile-nav a {
        text-decoration: none;
        color: #999;
        font-size: 24px;
    }

    .mobile-nav a.active {
        color: #ff7a00;
    }

    /* =========================
        MOBILE
    ========================== */

    @media(max-width:768px) {

        .top-nav {
            padding: 14px 18px;
        }

        .nav-links {
            display: none;
        }

        .main-header h1 {
            font-size: 24px;
        }

        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .menu-card {
            padding: 22px 12px;
            border-radius: 18px;
        }

        .menu-icon {
            width: 65px;
            height: 65px;
            font-size: 28px;
        }

        .menu-name {
            font-size: 15px;
        }

        .mobile-nav {
            display: flex;
        }
    }
</style>

@endpush


@endsection