@extends('layouts.welcome')
@section('content')


<div class="section-header my-4">
    <h2 class="section-title">Featured <span>Products</span></h2>
    <p class="section-sub">Handpicked just for you · Updated daily</p>
</div>
@foreach($grouped as $category => $items)

<p class="mt-4 mb-3 text-capitalize font-bold text-lg">
    {{ $category }}
</p>
<div class="products-grid mb-5">

    @foreach($items as $product)

    <article class="product-card" role="listitem">

        <div class="prod-img">

            <img
                src="{{ $product['thumbnail'] }}"
                alt="{{ $product['title'] }}"
                style="width:100%;height:100%;object-fit:cover;">

        </div>

        <div class="prod-info">

            <div class="prod-name">
                {{ $product['title'] }}
            </div>

            <div class="prod-desc">
                {{ $product['description'] }}
            </div>

            <div class="prod-meta">

                <span>
                    ⭐ {{ $product['rating'] }}
                </span>

                <span class="discount">
                    -{{ $product['discountPercentage'] }}%
                </span>

            </div>

            <div class="prod-price">
                ${{ $product['price'] }}
            </div>

            <div class="prod-actions">
                <button
                    class="btn-cart"
                    data-id="{{ $product['id'] }}"
                    data-title="{{ $product['title'] }}"
                    data-price="{{ $product['price'] }}"
                    data-thumbnail="{{ $product['thumbnail'] }}">
                    <i class="ti ti-shopping-cart-plus"></i>
                    Add
                </button>
                <button class="btn-wish">
                    ❤
                </button>

            </div>

        </div>

    </article>

    @endforeach

</div>
@endforeach
<script>
    $(document).on('click', '.btn-cart', function() {

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
            success: function(res) {

                // ✅ update cart count instantly
                $('#cart-count').text(res.cart_count);
                $('#cart-count-bottom').text(res.cart_count);

            },
            error: function(err) {
                console.log(err);
            }
        });

    });
</script>
@endsection