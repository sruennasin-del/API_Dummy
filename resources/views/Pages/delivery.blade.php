@extends('layouts.welcome')
@section('content')

<div style="max-width:900px; margin:50px auto; padding:25px; font-family:Arial;">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:35px;">
        
        <div>
            <h2 style="margin:0;">
                Order <span style="color:#ffb300;">#X3YHD45</span>
            </h2>
            <small style="color:#777;">Track your shipment status</small>
        </div>

        <div style="text-align:right;">
            <small><b>Courier:</b> USPS</small><br>
            <small><b>Tracking:</b> 23408324328239847363</small><br>
            <small><b>ETA:</b> 21/10/2021</small>
        </div>

    </div>

    {{-- TRACKING STEPS --}}
    <div style="position:relative; display:flex; justify-content:space-between; align-items:flex-start;">

        {{-- LINE BACKGROUND --}}
        <div style="
            position:absolute;
            top:18px;
            left:8%;
            right:8%;
            height:5px;
            background:#e9ecef;
            border-radius:10px;
            z-index:0;">
        </div>

        {{-- LINE PROGRESS (FILLED) --}}
        <div style="
            position:absolute;
            top:18px;
            left:8%;
            width:21%; /* line process */
            height:5px;
            background:#FF6B1A;
            border-radius:10px;
            z-index:0;">
        </div>

        {{-- STEP 1 --}}
        <div style="text-align:center; z-index:1;">
            <div style="
                width:42px;
                height:42px;
                border-radius:50%;
                background:#FF6B1A;
                color:#fff;
                display:flex;
                align-items:center;
                justify-content:center;
                margin:auto;
                font-weight:bold;">
                ✓
            </div>
            <small style="display:block; margin-top:10px;">Order Placed</small>
        </div>

        {{-- STEP 2 --}}
        <div style="text-align:center; z-index:1;">
            <div style="
                width:42px;
                height:42px;
                border-radius:50%;
                background:#FF6B1A;
                color:#fff;
                display:flex;
                align-items:center;
                justify-content:center;
                margin:auto;
                font-weight:bold;">
                ✓
            </div>
            <small style="display:block; margin-top:10px;">Processed</small>
        </div>

        {{-- STEP 3 --}}
        <div style="text-align:center; z-index:1;">
            <div style="
                width:42px;
                height:42px;
                border-radius:50%;
                background:#FF6B1A;
                color:#fff;
                display:flex;
                align-items:center;
                justify-content:center;
                margin:auto;
                font-weight:bold;">
                ✓
            </div>
            <small style="display:block; margin-top:10px;">Shipped</small>
        </div>

        {{-- STEP 4 (ARRIVED COMPANY) --}}
        <div style="text-align:center; z-index:1;">
            <div style="
                width:42px;
                height:42px;
                border-radius:50%;
                background:#FF6B1A;
                color:#fff;
                display:flex;
                align-items:center;
                justify-content:center;
                margin:auto;
                font-weight:bold;">
                📦
            </div>
            <small style="display:block; margin-top:10px;">Arrived Company</small>
        </div>

        {{-- STEP 5 (DELIVERED / COMPLETE) --}}
        <div style="text-align:center; z-index:1;">
            <div style="
                width:42px;
                height:42px;
                border-radius:50%;
                background:#FF6B1A;
                color:#fff;
                display:flex;
                align-items:center;
                justify-content:center;
                margin:auto;
                font-weight:bold;">
                🏠
            </div>
            <small style="display:block; margin-top:10px;">Delivered</small>
        </div>

    </div>

    {{-- STATUS CARD --}}
    <div style="
        margin-top:40px;
        padding:20px;
        border-radius:12px;
        background:#f8f9fa;
        border:1px solid #eee;
    ">
        <h4 style="margin-top:0;">Current Status</h4>
        <p style="margin:0; color:#555;">
            Your order has arrived at the company warehouse and is being prepared for final delivery.
        </p>
    </div>

</div>

@endsection