@extends('layouts.welcome')
@section('content')



{{-- ════════════════════════════════
     CATEGORY TABS
════════════════════════════════ --}}
<div class="cat-bar" role="navigation" aria-label="Product categories">
    <ul class="cat-tabs">
        <li class="active"><a href="#"><i class="ti ti-layout-grid" aria-hidden="true"></i>All</a></li>
        <li><a href="#"><i class="ti ti-device-mobile" aria-hidden="true"></i>Electronics</a></li>
        <li><a href="#"><i class="ti ti-shirt" aria-hidden="true"></i>Fashion</a></li>
        <li><a href="#"><i class="ti ti-armchair" aria-hidden="true"></i>Home & Living</a></li>
        <li><a href="#"><i class="ti ti-leaf" aria-hidden="true"></i>Health</a></li>
        <li><a href="#"><i class="ti ti-dumbbell" aria-hidden="true"></i>Sports</a></li>
        <li><a href="#"><i class="ti ti-book" aria-hidden="true"></i>Books</a></li>
        <li><a href="#"><i class="ti ti-gift" aria-hidden="true"></i>Gifts</a></li>
    </ul>
</div>

{{-- ════════════════════════════════
     HERO SLIDER
════════════════════════════════ --}}
<section class="hero" aria-label="Featured promotions" aria-roledescription="carousel">

    <div class="slides-track" id="slidesTrack">

        {{-- Slide 1 --}}
        <div class="slide slide-1" role="group" aria-roledescription="slide" aria-label="Slide 1 of 3">
            <div class="slide-body">
                <span class="slide-tag">🔥 Limited Offer</span>
                <h1>Summer Sale<br>Up to 50% Off</h1>
                <p>Shop the hottest deals of the season. Electronics, fashion, home goods — all at unbeatable prices.</p>
                <div class="slide-btns">
                    <a href="#products" class="btn-hero btn-white"><i class="ti ti-shopping-bag" aria-hidden="true"></i>Shop Now</a>
                    <a href="#" class="btn-hero btn-outline">View Deals</a>
                </div>
            </div>
            <div class="slide-img-area" aria-hidden="true">
                <div class="slide-img-box">
                    <i class="ti ti-device-laptop"></i>
                    <span>Featured</span>
                </div>
            </div>
        </div>

        {{-- Slide 2 --}}
        <div class="slide slide-2" role="group" aria-roledescription="slide" aria-label="Slide 2 of 3">
            <div class="slide-body">
                <span class="slide-tag" style="color:#FFD6BB;border-color:rgba(255,166,100,.4);background:rgba(255,107,26,.18)">✨ New Arrivals</span>
                <h1>Next-Gen<br>Electronics</h1>
                <p>Discover the latest smartphones, laptops, and gadgets. Technology that moves with you.</p>
                <div class="slide-btns">
                    <a href="#products" class="btn-hero btn-orange"><i class="ti ti-device-mobile" aria-hidden="true"></i>Explore Tech</a>
                    <a href="#" class="btn-hero btn-outline">Learn More</a>
                </div>
            </div>
            <div class="slide-img-area" aria-hidden="true">
                <div class="slide-img-box" style="border-color:rgba(255,107,26,.4)">
                    <i class="ti ti-device-mobile"></i>
                    <span>New Collection</span>
                </div>
            </div>
        </div>

        {{-- Slide 3 --}}
        <div class="slide slide-3" role="group" aria-roledescription="slide" aria-label="Slide 3 of 3">
            <div class="slide-body">
                <span class="slide-tag">🎁 Gift Ideas</span>
                <h1>Find the Perfect<br>Gift Today</h1>
                <p>Curated gift sets for every occasion. Free wrapping on orders above $50.</p>
                <div class="slide-btns">
                    <a href="#products" class="btn-hero btn-orange"><i class="ti ti-gift" aria-hidden="true"></i>Shop Gifts</a>
                </div>
            </div>
            <div class="slide-img-area" aria-hidden="true">
                <div class="slide-img-box">
                    <i class="ti ti-gift"></i>
                    <span>Gift Picks</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Arrows --}}
    <div class="slider-arrows" aria-hidden="true">
        <button class="arrow-btn" id="prevBtn" aria-label="Previous slide">
            <i class="ti ti-chevron-left"></i>
        </button>
        <button class="arrow-btn" id="nextBtn" aria-label="Next slide">
            <i class="ti ti-chevron-right"></i>
        </button>
    </div>

    {{-- Dots --}}
    <div class="slider-dots" role="tablist" aria-label="Slide navigation">
        <button class="dot active" role="tab" aria-selected="true" aria-label="Go to slide 1" data-slide="0"></button>
        <button class="dot" role="tab" aria-selected="false" aria-label="Go to slide 2" data-slide="1"></button>
        <button class="dot" role="tab" aria-selected="false" aria-label="Go to slide 3" data-slide="2"></button>
    </div>

</section>

{{-- ════════════════════════════════
     FEATURED PRODUCTS
════════════════════════════════ --}}
<section class="section" id="products" aria-label="Featured products">
    <div class="section-header">
        <h2 class="section-title">Featured <span>Products</span></h2>
        <p class="section-sub">Handpicked just for you · Updated daily</p>
    </div>

<!-- MENU -->

<div class="menu-wrapper">

    <div class="menu-grid">

        <!-- Skin Care -->
        <a href="{{ url('sub-product/1') }}" class="menu-card">
            <div class="menu-icon">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <div class="menu-name">
                Skin Care
            </div>
        </a>

        <!-- Furniture -->
        <a href="{{ url('sub-product/2') }}"class="menu-card">
            <div class="menu-icon">
                <i class="bi bi-lamp-fill"></i>
            </div>
            <div class="menu-name">
                Furniture
            </div>
        </a>

        <!-- Food -->
        <a href="{{ url('sub-product/3') }}"class="menu-card">
            <div class="menu-icon">
                <i class="bi bi-cup-hot-fill"></i>
            </div>
            <div class="menu-name">
                Food
            </div>
        </a>

        <!-- Kitchen -->
        <a href="{{ url('sub-product/4') }}"class="menu-card">
            <div class="menu-icon">
                <i class="bi bi-egg-fried"></i>
            </div>
            <div class="menu-name">
                Kitchen
            </div>
        </a>

        <!-- Shirt -->
        <a href="{{ url('sub-product/5') }}"class="menu-card">
            <div class="menu-icon">
                <i class="bi bi-dribbble"></i>
            </div>
            <div class="menu-name">
             sport
            </div>
        </a>

        <!-- Shoes -->
        <a href="{{ url('sub-product/6') }}"class="menu-card">
            <div class="menu-icon">
                <i class="ti ti-shoe"></i>
            </div>
            <div class="menu-name">
                Shoes
            </div>
        </a>

        <!-- Watch -->
        <a href="{{ url('sub-product/7') }}"class="menu-card">
            <div class="menu-icon">
                <i class="bi bi-watch"></i>
            </div>
            <div class="menu-name">
                Watch
            </div>
        </a>

        <!-- Accessories -->
        <a href="{{ url('sub-product/8') }}"class="menu-card">
            <div class="menu-icon">
                <i class="bi bi-gem"></i>
            </div>
            <div class="menu-name">
                Accessories
            </div>
        </a>

        <!-- Motor -->
        <a href="{{ url('sub-product/9') }}"class="menu-card">
            <div class="menu-icon">
                <i class="bi bi-bicycle"></i>
            </div>
            <div class="menu-name">
                Motor
            </div>
        </a>

        <!-- Phone -->
        <a href="{{ url('sub-product/10') }}" class="menu-card">
            <div class="menu-icon">
                <i class="bi bi-phone-fill"></i>
            </div>
            <div class="menu-name">
                Phone
            </div>
        </a>

    </div>

</div>

<!-- MOBILE BOTTOM NAV -->

<div class="mobile-nav">

    <a href="#" class="active">
        <i class="bi bi-house-fill"></i>
    </a>

    <a href="#">
        <i class="bi bi-search"></i>
    </a>

    <a href="#">
        <i class="bi bi-cart-fill"></i>
    </a>

    <a href="#">
        <i class="bi bi-person-fill"></i>
    </a>

</div>


</section>

{{-- ════════════════════════════════
     PROMO BANNER
════════════════════════════════ --}}
<aside class="promo-banner" aria-label="Promotion: Free shipping">
    <div class="banner-text">
        <h2>Free Shipping on Orders Over $50 🚚</h2>
        <p>Shop more, save more. Delivered to your door, fast and free.</p>
    </div>
    <button class="btn-banner">Shop Now →</button>
</aside>

{{-- ════════════════════════════════
     FOOTER
════════════════════════════════ --}}
<footer role="contentinfo">
    <p>&copy; {{ date('Y') }} <a href="{{ url('/') }}">ZestShop</a> — All rights reserved.</p>
</footer>

{{-- ════════════════════════════════
     BOTTOM MOBILE NAV
════════════════════════════════ --}}
<nav class="bottom-nav" aria-label="Mobile bottom navigation" role="navigation">
    <ul>
        <li class="active">
            <a href="{{ url('/') }}" aria-label="Home">
                <i class="ti ti-home" aria-hidden="true"></i>Home
            </a>
        </li>
        <li>
            <a href="#" aria-label="Search">
                <i class="ti ti-search" aria-hidden="true"></i>Search
            </a>
        </li>
        <li>
            <a href="#" aria-label="Cart">
                <i class="ti ti-shopping-cart" aria-hidden="true"></i>Cart
            </a>
        </li>
        <li>
            <a href="#" aria-label="Wishlist">
                <i class="ti ti-heart" aria-hidden="true"></i>Saved
            </a>
        </li>
        <li>
            <a href="#" aria-label="Profile">
                <i class="ti ti-user" aria-hidden="true"></i>Profile
            </a>
        </li>
    </ul>
</nav>


@push('css')
    


<style>

    /* =========================
        MENU SECTION
    ========================== */

    .menu-wrapper{
        padding:30px 15px 100px;
        max-width:1400px;
        margin:auto;
    }

    .menu-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:22px;
    }

    .menu-card{
        background:white;
        border-radius:24px;
        padding:30px 20px;
        text-align:center;
        text-decoration:none;
        color:#222;
        border:2px solid #ffe3c4;
        transition:.3s;
        box-shadow:0 5px 18px rgba(0,0,0,.05);
    }

    .menu-card:hover{
        transform:translateY(-6px);
        border-color:#ff7a00;
        box-shadow:0 12px 25px rgba(255,122,0,.18);
    }

    .menu-icon{
        width:85px;
        height:85px;
        background:#fff2e5;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:auto;
        margin-bottom:18px;
        color:#ff7a00;
        font-size:38px;
    }

    .menu-name{
        font-size:18px;
        font-weight:700;
    }

    /* =========================
        DESKTOP TOP NAV
    ========================== */

    .top-nav{
        background:white;
        padding:14px 25px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        box-shadow:0 2px 10px rgba(0,0,0,.06);
        position:sticky;
        top:0;
        z-index:999;
    }

    .logo{
        color:#ff7a00;
        font-size:28px;
        font-weight:800;
    }

    .nav-links{
        display:flex;
        gap:25px;
    }

    .nav-links a{
        text-decoration:none;
        color:#333;
        font-weight:600;
        transition:.3s;
    }

    .nav-links a:hover{
        color:#ff7a00;
    }

    /* =========================
        MOBILE BOTTOM NAV
    ========================== */

    .mobile-nav{
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
        background:white;
        border-top:1px solid #eee;
        display:none;
        justify-content:space-around;
        padding:12px 0;
        z-index:999;
    }

    .mobile-nav a{
        text-decoration:none;
        color:#999;
        font-size:24px;
    }

    .mobile-nav a.active{
        color:#ff7a00;
    }

    /* =========================
        MOBILE
    ========================== */

    @media(max-width:768px){

        .top-nav{
            padding:14px 18px;
        }

        .nav-links{
            display:none;
        }

        .main-header h1{
            font-size:24px;
        }

        .menu-grid{
            grid-template-columns:repeat(2,1fr);
            gap:16px;
        }

        .menu-card{
            padding:22px 12px;
            border-radius:18px;
        }

        .menu-icon{
            width:65px;
            height:65px;
            font-size:28px;
        }

        .menu-name{
            font-size:15px;
        }

        .mobile-nav{
            display:flex;
        }
    }

</style>

@endpush


@endsection