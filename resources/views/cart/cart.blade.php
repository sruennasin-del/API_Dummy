@extends('layouts.welcome')
@section('content')
<div class="container my-4">

    {{-- header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">

        <h1 class="cart-title">
            Your order
        </h1>

        @if(count($cart) > 0)

        <form action="/cart/clear" method="POST">
            @csrf

            <button type="submit" class="btn-clear">
                <i class="bi bi-trash"></i>
                Clear all
            </button>
        </form>

        @endif

    </div>


    @php

        $subtotal = collect($cart)->sum(function($item){
            return ($item['price'] ?? 2.50) * $item['qty'];
        });

        $service = 1.50;
        $delivery = 2.00;
        $tax = $subtotal * 0.10;

        $grandTotal = $subtotal + $service + $delivery + $tax;

    @endphp


    @if(count($cart) === 0)

        {{-- empty --}}
        <div class="cart-empty">

            <i class="bi bi-cart2"></i>

            <p>
                Your order is empty
            </p>

            <a href="/" class="btn-back">
                Browse menu
            </a>

        </div>

    @else

    <div class="cart-layout">

        {{-- LEFT --}}
        <div class="cart-left">

            <div class="cart-list">

                @foreach($cart as $index => $item)

                <div class="cart-item">

                    {{-- number --}}
                    <div class="cart-item-left">

                        <div class="cart-item-num">
                            {{ $index + 1 }}
                        </div>

                    </div>


                    {{-- info --}}
                    <div class="cart-item-info">
                        <div class="cart-item-name d-flex align-items-center gap-2">
                            <img width="100px" src="{{ $item['thumbnail'] }}" alt="{{ $item['title'] }}">
                            <p>{{ $item['title'] }}</p>
                      
                        </div>
                    </div>


                    {{-- qty --}}
                    <div class="cart-qty-box">

                        {{-- decrease --}}      
                        <form action="/cart/decrease" method="POST">
                            @csrf

                            <input type="hidden" name="index" value="{{ $index }}">

                            <button type="submit" class="qty-btn">
                                -
                            </button>
                        </form>


                        <span class="qty-text">
                            {{ $item['qty'] }}
                        </span>


                        {{-- increase --}}
                        <form action="/cart/increase" method="POST">
                            @csrf

                            <input type="hidden" name="index" value="{{ $index }}">

                            <button type="submit" class="qty-btn">
                                +
                            </button>
                        </form>

                    </div>


                    {{-- remove --}}
                    <form action="/cart/remove" method="POST">
                        @csrf

                        <input type="hidden" name="index" value="{{ $index }}">

                        <button type="submit" class="cart-item-remove">
                            <i class="bi bi-trash"></i>
                        </button>

                    </form>

                </div>

                @endforeach

            </div>

        </div>


        {{-- RIGHT --}}
        <div class="cart-right  sticky-top" style="top: 90px">

            <div class="summary-card">

                <h4 class="summary-title">
                    Order Summary
                </h4>


                <div class="summary-row">
                    <span>Total items</span>

                    <span>
                        {{ collect($cart)->sum('qty') }}
                    </span>
                </div>


                <div class="summary-row">
                    <span>Subtotal</span>

                    <span>
                        ${{ number_format($subtotal, 2) }}
                    </span>
                </div>


                <div class="summary-row">
                    <span>Service fee</span>

                    <span>
                        ${{ number_format($service, 2) }}
                    </span>
                </div>


                <div class="summary-row">
                    <span>Delivery</span>

                    <span>
                        ${{ number_format($delivery, 2) }}
                    </span>
                </div>


                <div class="summary-row">
                    <span>Tax</span>

                    <span>
                        ${{ number_format($tax, 2) }}
                    </span>
                </div>


                <div class="summary-divider"></div>


                <div class="summary-total">

                    <span>Total</span>

                    <span>
                        ${{ number_format($grandTotal, 2) }}
                    </span>

                </div>


                {{-- payment --}}
                <div class="payment-section">

                    <h5 class="payment-title">
                        Payment Method
                    </h5>


                    <div class="payment-list">

                        {{-- ABA --}}
                        <label class="payment-card">

                            <input type="radio"
                                   name="payment_method"
                                   value="ABA"
                                   checked>

                            <div class="payment-content">

                                <img src="https://www.ababank.com/typo3conf/ext/boxmodel/Resources/Private/Templates/ABA/images/aba-web-top-logo.png">

                                <div>
                                    <div class="payment-name">
                                        ABA Bank
                                    </div>

                                    <div class="payment-desc">
                                        Pay with ABA Mobile
                                    </div>
                                </div>

                            </div>

                        </label>


                        {{-- ACLEDA --}}
                        <label class="payment-card">

                            <input type="radio"
                                   name="payment_method"
                                   value="ACLEDA">

                            <div class="payment-content">

                                <img src="https://companieslogo.com/img/orig/ABC.KH-3aa8d94f.png?t=1720244490">

                                <div>
                                    <div class="payment-name">
                                        ACLEDA
                                    </div>

                                    <div class="payment-desc">
                                        ACLEDA payment
                                    </div>
                                </div>

                            </div>

                        </label>


                        {{-- Wing --}}
                        <label class="payment-card">

                            <input type="radio"
                                   name="payment_method"
                                   value="Wing">

                            <div class="payment-content">

                                <img src="https://eu-images.contentstack.com/v3/assets/blt7dacf616844cf077/bltf5f103d9d932958a/67998395c3d5893a67957346/Wing-Bank.png.jpg?width=960&auto=webp&quality=80&format=jpg&disable=upscale">

                                <div>
                                    <div class="payment-name">
                                        Wing Bank
                                    </div>

                                    <div class="payment-desc">
                                        Wing transfer
                                    </div>
                                </div>

                            </div>

                        </label>


                        {{-- Cash --}}
                        <label class="payment-card">

                            <input type="radio"
                                   name="payment_method"
                                   value="Cash">

                            <div class="payment-content">

                                <div class="cash-icon">
                                    <i class="bi bi-cash-stack"></i>
                                </div>

                                <div>

                                    <div class="payment-name">
                                        Cash
                                    </div>

                                    <div class="payment-desc">
                                        Pay on delivery
                                    </div>

                                </div>

                            </div>

                        </label>

                    </div>

                </div>


                {{-- button --}}
                <button class="btn-place-order">

                    <i class="bi bi-check-circle"></i>

                    Place order

                </button>

            </div>

        </div>

    </div>

    @endif

</div>


@push('css')
    

<style>

/* layout */
.cart-layout{
    display:grid;
    grid-template-columns:1fr 340px;
    gap:24px;
    align-items:start;
}

/* title */
.cart-title{
    font-size:22px;
    font-weight:700;
}

/* clear */
.btn-clear{
    border:none;
    background:#FCEBEB;
    color:#E24B4A;
    padding:10px 16px;
    border-radius:10px;
    cursor:pointer;
    font-size:13px;
}

/* empty */
.cart-empty{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    gap:14px;
    padding:6rem 1rem;
}

.cart-empty i{
    font-size:60px;
    color:#ddd;
}

.cart-empty p{
    color:#999;
}

.btn-back{
    padding:10px 20px;
    border:1px solid #D85A30;
    border-radius:10px;
    text-decoration:none;
    color:#D85A30;
}

/* list */
.cart-list{
    display:flex;
    flex-direction:column;
    gap:14px;
}

/* item */
.cart-item{
    background:#fff;
    border:1px solid #eee;
    border-radius:14px;
    padding:1rem;
    display:flex;
    align-items:center;
    gap:14px;
}

/* number */
.cart-item-num{
    width:36px;
    height:36px;
    border-radius:50%;
    background:#FAECE7;
    color:#993C1D;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:13px;
    font-weight:600;
}

/* info */
.cart-item-info{
    flex:1;
}

.cart-item-name{
    font-size:15px;
    font-weight:600;
    margin-bottom:6px;
}

.cart-item-meta{
    display:flex;
    gap:6px;
}

.meta-badge{
    background:#FAECE7;
    color:#993C1D;
    padding:4px 9px;
    border-radius:6px;
    font-size:11px;
}

/* qty */
.cart-qty-box{
    display:flex;
    align-items:center;
    gap:8px;
}

.qty-btn{
    width:30px;
    height:30px;
    border:none;
    border-radius:8px;
    background:#FAECE7;
    color:#993C1D;
    cursor:pointer;
    font-size:16px;
    font-weight:700;
}

.qty-btn:hover{
    background:#D85A30;
    color:#fff;
}

.qty-text{
    min-width:24px;
    text-align:center;
    font-weight:600;
}

/* remove */
.cart-item-remove{
    width:34px;
    height:34px;
    border:none;
    border-radius:50%;
    background:#f5f5f5;
    cursor:pointer;
}

.cart-item-remove:hover{
    background:#FCEBEB;
    color:#E24B4A;
}

/* right */
.summary-card{
    background:#fff;
    border:1px solid #eee;
    border-radius:16px;
    padding:1.5rem;
    position:sticky;
    top:20px;
}

.summary-title{
    font-size:18px;
    font-weight:700;
    margin-bottom:1.2rem;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    padding:8px 0;
    font-size:14px;
    color:#666;
}

.summary-divider{
    height:1px;
    background:#eee;
    margin:14px 0;
}

.summary-total{
    display:flex;
    justify-content:space-between;
    font-size:18px;
    font-weight:700;
    margin-bottom:1rem;
}

/* payment */
.payment-section{
    margin-bottom:1.5rem;
}

.payment-title{
    font-size:15px;
    font-weight:600;
    margin-bottom:12px;
}

.payment-list{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.payment-card{
    border:1px solid #eee;
    border-radius:12px;
    padding:12px;
    cursor:pointer;
    transition:.2s;
}

/* hover */
.payment-card:hover{
    border-color:#D85A30;
    background:#fff8f5;
}

/* active selected card */
.payment-card:has(input:checked){
    border:1px solid #D85A30;
    background:#fff8f5;
}

/* text color */
.payment-card input:checked + .payment-content .payment-name{
    color:#D85A30;
}


.payment-card input{
    display:none;
}

.payment-content{
    display:flex;
    align-items:center;
    gap:12px;
}

.payment-content img{
    width:42px;
    height:42px;
    object-fit:contain;
}

.payment-name{
    font-size:14px;
    font-weight:600;
}

.payment-desc{
    font-size:12px;
    color:#888;
}

.cash-icon{
    width:42px;
    height:42px;
    border-radius:10px;
    background:#FAECE7;
    color:#D85A30;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
}

/* button */
.btn-place-order{
    width:100%;
    border:none;
    background:#D85A30;
    color:#fff;
    padding:14px;
    border-radius:12px;
    cursor:pointer;
    font-size:15px;
    font-weight:600;
}

.btn-place-order:hover{
    background:#c24b24;
}

/* mobile */
@media(max-width:991px){

    .cart-layout{
        grid-template-columns:1fr;
    }

    .summary-card{
        position:static;
    }
    .cart-item-name img{
        width: 34px;
        display: block;
    }
    .cart-item-name p{
        font-size: 10px;      
    }


}

</style>
@endpush

@endsection