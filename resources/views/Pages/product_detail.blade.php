@extends('layouts.welcome')

@section('content')
<div class="container py-5 mt-5">
    <div class="row">
        <!-- Left Column: Images -->
        <div class="col-md-6 mb-4">
            <div class="row">
                <!-- Thumbnails (Left side) -->
                <div class="col-2 d-flex flex-column gap-2" id="product-thumbnails">
                    <!-- Thumbnails will be injected here by JS -->
                </div>
                <!-- Main Image -->
                <div class="col-10">
                    <div class="main-image-container position-relative overflow-hidden rounded-3" style="background: #F8F8F8; aspect-ratio: 3/4; display: flex; align-items: center; justify-content: center;">
                        <img id="main-product-image" src="" alt="Main Product" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Details -->
        <div class="col-md-6 px-md-5">
            <h2 class="mb-2" style="font-weight: 400; font-size: 28px;">{{ $product->title }}</h2>
            <h4 class="mb-4 fw-bold" id="product-price" style="font-size: 20px;">
                <!-- Price injected by JS -->
            </h4>

            <!-- Color Selection -->
            <div class="mb-4">
                <label class="form-label text-muted mb-2" style="font-size: 14px;">Color</label>
                <div class="d-flex flex-wrap gap-3" id="color-selector">
                    @foreach($product->colorVariants as $variant)
                        @php
                            $firstImage = optional($variant->images->first())->image_path ?? 'https://via.placeholder.com/100';
                        @endphp
                        <div class="color-box text-center cursor-pointer" 
                             data-variant-id="{{ $variant->id }}"
                             onclick="selectVariant({{ $variant->id }})"
                             style="width: 70px; border: 1px solid #ddd; padding: 5px; cursor: pointer;">
                            <img src="{{ $firstImage }}" alt="{{ $variant->color->name }}" class="img-fluid mb-1" style="height: 40px; object-fit: cover;">
                            <div style="font-size: 11px; text-transform: lowercase;">{{ $variant->color->name }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Size Selection -->
            <div class="mb-4">
                <label class="form-label text-muted mb-2" style="font-size: 14px;">Size</label>
                <div class="d-flex flex-wrap gap-2" id="size-selector">
                    <!-- Sizes injected by JS based on selected color -->
                </div>
            </div>

            <!-- Quantity & Add to Cart -->
            <div class="d-flex align-items-center gap-3 mt-4 mb-4">
                <div class="input-group" style="width: 130px;">
                    <button class="btn btn-outline-secondary" type="button" onclick="updateQty(-1)">-</button>
                    <input type="text" class="form-control text-center" id="qty-input" value="1" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="updateQty(1)">+</button>
                </div>
                <button class="btn px-4 py-2 text-white fw-bold w-100" onclick="addToCart()" style="background-color: #222; border-radius: 30px; letter-spacing: 1px;">
                    ADD TO CART
                </button>
            </div>

            <!-- Social Share -->
            <div class="d-flex gap-3 text-muted mt-5">
                <i class="ti ti-heart cursor-pointer" id="wishlist-btn" onclick="toggleWishlist({{ $product->id }})" style="font-size: 20px; transition: color 0.2s; {{ in_array($product->id, session()->get('wishlist', [])) ? 'color: #f44336;' : '' }}"></i>
                <i class="ti ti-brand-facebook cursor-pointer" style="font-size: 20px;"></i>
                <i class="ti ti-brand-twitter cursor-pointer" style="font-size: 20px;"></i>
                <i class="ti ti-brand-google cursor-pointer" style="font-size: 20px;"></i>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5 pt-5 border-top">
        <h3 class="mb-4 fw-bold" style="font-size: 24px;">Related Products</h3>
        
        <div class="swiper related-swiper position-relative" style="padding: 0 10px;">
            <div class="swiper-wrapper">
                @foreach($relatedProducts as $related)
                <div class="swiper-slide">
                    <article class="elegant-card">
                        <a href="{{ route('frontend.product', $related->slug) }}" class="text-decoration-none">
                            <div class="elegant-img-wrapper">
                                <img src="{{ $related->image ?? 'https://via.placeholder.com/400x600' }}" alt="{{ $related->title }}">
                            </div>
                        </a>
                        <div class="elegant-info mt-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h3 class="elegant-title m-0 text-truncate" style="max-width: 85%;">{{ $related->title }}</h3>
                                <button class="elegant-wishlist-btn bg-transparent border-0 p-0 text-muted">
                                    <i class="ti ti-heart"></i>
                                </button>
                            </div>
                            <div class="elegant-price text-muted">
                                ${{ number_format($related->price, 2) }}
                            </div>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
            
            <!-- Navigation Buttons -->
            <div class="swiper-button-next elegant-swiper-btn related-next"></div>
            <div class="swiper-button-prev elegant-swiper-btn related-prev"></div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Elegant Slider Styles */
    .elegant-card { display: block; width: 100%; }
    .elegant-img-wrapper { background-color: #F8F8F8; width: 100%; aspect-ratio: 3/4; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .elegant-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .elegant-card:hover .elegant-img-wrapper img { transform: scale(1.03); }
    .elegant-title { font-size: 14px; font-weight: 500; color: #888; font-family: 'DM Sans', sans-serif; }
    .elegant-price { font-size: 12px; font-weight: 500; color: #666; }
    .elegant-wishlist-btn i { font-size: 16px; color: #ccc; transition: color 0.2s; }
    .elegant-wishlist-btn:hover i { color: #f44336; }
    
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
    .elegant-swiper-btn.swiper-button-prev { left: 0px !important; }
    .elegant-swiper-btn.swiper-button-next { right: 0px !important; }
    .elegant-swiper-btn:after { font-size: 12px !important; font-weight: bold; }
    .elegant-swiper-btn:hover { background-color: #222; opacity: 1; }

    .color-box.active {
        border: 2px solid #222 !important;
    }
    .size-btn {
        min-width: 40px;
        height: 40px;
        border: 1px solid #f0f0f0;
        background: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 13px;
        color: #555;
        transition: all 0.2s;
    }
    .size-btn.active {
        background: #222;
        color: white;
        border-color: #222;
    }
    .thumb-img {
        width: 100%;
        aspect-ratio: 3 / 4;
        object-fit: cover;
        border: 1px solid #ddd;
        cursor: pointer;
        border-radius: 4px;
    }
    .thumb-img.active {
        border: 2px solid #222;
    }
</style>
@endpush

@push('js')
<script>
    // Load variants data cleanly from backend without upsetting the IDE
    const variants = JSON.parse('{!! addslashes(json_encode($product->colorVariants)) !!}');
    let currentVariantId = null;
    let currentSizeId = null;

    function selectVariant(variantId) {
        currentVariantId = variantId;
        const variant = variants.find(v => v.id === variantId);
        if(!variant) return;

        // Highlight selected color box
        document.querySelectorAll('.color-box').forEach(el => el.classList.remove('active'));
        document.querySelector(`.color-box[data-variant-id="${variantId}"]`).classList.add('active');

        // Update Price safely
        document.getElementById('product-price').innerText = '$ ' + parseFloat('{{ $product->price ?? 0 }}').toFixed(2);

        // Update Images
        const thumbsContainer = document.getElementById('product-thumbnails');
        thumbsContainer.innerHTML = '';
        
        if (variant.images && variant.images.length > 0) {
            // Set main image to the first image
            document.getElementById('main-product-image').src = variant.images[0].image_path;
            
            variant.images.forEach((img, index) => {
                const imgEl = document.createElement('img');
                imgEl.src = img.image_path;
                imgEl.className = 'thumb-img ' + (index === 0 ? 'active' : '');
                imgEl.onclick = function() {
                    document.getElementById('main-product-image').src = this.src;
                    document.querySelectorAll('.thumb-img').forEach(el => el.classList.remove('active'));
                    this.classList.add('active');
                };
                thumbsContainer.appendChild(imgEl);
            });
        }

        // Update Sizes
        const sizeContainer = document.getElementById('size-selector');
        sizeContainer.innerHTML = '';
        currentSizeId = null;

        if (variant.sizes && variant.sizes.length > 0) {
            variant.sizes.forEach((size, index) => {
                const btn = document.createElement('div');
                btn.className = 'size-btn ' + (index === 0 ? 'active' : '');
                btn.innerText = size.name;
                
                if(index === 0) currentSizeId = size.id; // Default select first size

                btn.onclick = function() {
                    currentSizeId = size.id;
                    document.querySelectorAll('.size-btn').forEach(el => el.classList.remove('active'));
                    this.classList.add('active');
                };
                sizeContainer.appendChild(btn);
            });
        } else {
            sizeContainer.innerHTML = '<span class="text-muted">One Size</span>';
        }
    }

    function updateQty(change) {
        const input = document.getElementById('qty-input');
        let val = parseInt(input.value) + change;
        if(val < 1) val = 1;
        input.value = val;
    }

    function toggleWishlist(productId) {
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: productId })
        })
        .then(response => response.json())
        .then(data => {
            const btn = document.getElementById('wishlist-btn');
            if(data.status === 'added') {
                btn.style.color = '#f44336';
            } else {
                btn.style.color = '';
            }
            alert(data.message);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function addToCart() {
        if (!currentVariantId) {
            alert('Please select a color.');
            return;
        }
        if (!currentSizeId) {
            alert('Please select a size.');
            return;
        }

        const variant = variants.find(v => v.id === currentVariantId);
        const size = variant.sizes.find(s => s.id === currentSizeId);
        const qty = parseInt(document.getElementById('qty-input').value);

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: {{ $product->id }},
                variant_id: currentVariantId,
                size_id: currentSizeId,
                title: '{{ addslashes($product->title) }}',
                price: {{ (float)($product->price ?? 0) }},
                thumbnail: variant.images && variant.images.length > 0 ? variant.images[0].image_path : '{{ $product->image }}',
                color_name: variant.color.name,
                size_name: size.name,
                qty: qty
            })
        })
        .then(response => response.json())
        .then(data => {
            // Update cart count icon in the top navbar
            const cartCountEl = document.getElementById('cart-count');
            if (cartCountEl) {
                cartCountEl.innerText = data.cart_count;
            }
            const cartCountBottom = document.getElementById('cart-count-bottom');
            if (cartCountBottom) {
                cartCountBottom.innerText = data.cart_count;
            }
            alert(data.message);
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            alert('Failed to add to cart.');
        });
    }

    // Initialize first variant on load
    if (variants.length > 0) {
        selectVariant(variants[0].id);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if(document.querySelector('.related-swiper')) {
            new Swiper('.related-swiper', {
                slidesPerView: 5,
                slidesPerGroup: 1,
                spaceBetween: 24,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.related-next',
                    prevEl: '.related-prev',
                },
                breakpoints: {
                    320: { slidesPerView: 2, spaceBetween: 10 },
                    576: { slidesPerView: 3, spaceBetween: 16 },
                    768: { slidesPerView: 4, spaceBetween: 24 },
                    1024: { slidesPerView: 5, spaceBetween: 30 }
                }
            });
        }
    });
</script>
@endpush
