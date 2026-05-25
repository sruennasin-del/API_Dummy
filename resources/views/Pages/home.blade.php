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

    <div class="products-grid" role="list">

<script>

$(document).ready(function(){

    $.ajax({
        url:'https://dummyjson.com/products?limit=100',
        type:'GET',

        success:function(response){

            let products = response.products;

            let html = '';

            products.forEach(function(product){

              html += `
                            <article class="product-card" role="listitem">

                                <div class="prod-img">
                                    <img 
                                        src="${product.thumbnail}" 
                                        alt="${product.title}"
                                        style="width:100%;height:100%;object-fit:cover;"
                                    >
                                </div>

                                <div class="prod-info">

                                    <div class="prod-name">
                                        ${product.title}
                                    </div>

                                    <div class="prod-desc">
                                        ${product.description}
                                    </div>

                                    <!-- Rating + Discount -->
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;font-size:12px;color:#666;">
                                        
                                        <span>⭐ ${product.rating}</span>

                                        <span style="color:#FF6B1A;font-weight:600;">
                                            -${product.discountPercentage}%
                                        </span>

                                    </div>

                                    <div class="prod-price">
                                        $${product.price}
                                    </div>

                                    <div class="prod-actions">

                                       <button class="btn-cart" data-id="${product.id}" data-title="${product.title}" data-price="${product.price}" data-thumbnail="${product.thumbnail}">
                                                    <i class="ti ti-shopping-cart-plus"></i>
                                                    Add
                                        </button>

                                        <button class="btn-wish">
                                            <i class="ti ti-heart"></i>
                                        </button>

                                    </div>

                                </div>

                            </article>
                            `;
            });

            $('.products-grid').html(html);

        },

        error:function(error){
            console.log(error);
        }

    });

});

</script> 
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
{{-- jQuery --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
{{-- Bootstrap JS (requires jQuery or Popper; bundle includes Popper) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).on('click', '.btn-cart', function () {

    let id = $(this).data('id');
    let title = $(this).data('title');
    let price = $(this).data('price');
    let thumbnail = $(this).data('thumbnail');

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
        success: function (res) {

            // ✅ update cart count instantly
            $('#cart-count').text(res.cart_count);

        },
        error: function (err) {
            console.log(err);
        }
    });

});
</script>
@endsection