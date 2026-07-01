@extends('layouts.welcome')
@section('content')

<div class="container my-5" style="max-width: 900px; font-family: 'DM Sans', sans-serif;">
    <div class="text-center mb-5">
        <h1 class="fw-bold" style="font-family: 'Syne', sans-serif; font-size: 40px; background: linear-gradient(135deg, var(--orange) 30%, #ff8c52 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            About ZestShop
        </h1>
        <p class="text-muted mt-2 fs-5" style="max-width: 600px; margin: 0 auto;">
            We craft fashion experiences that blend premium quality with modern aesthetics.
        </p>
    </div>

    {{-- OUR VISION & MISSION --}}
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4" style="background: #fff; border: 1px solid #FFE8D8 !important;">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 50px; height: 50px; background: var(--orange-pale); color: var(--orange);">
                    <i class="ti ti-eye fs-3"></i>
                </div>
                <h4 class="fw-bold" style="font-family: 'Syne', sans-serif; color: var(--text-dark);">Our Vision</h4>
                <p class="text-muted m-0" style="font-size: 14.5px; line-height: 1.6;">
                    To become the leading modern e-commerce destination in Southeast Asia by delivering an exceptional shopping experience and curating only high-quality products.
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4" style="background: #fff; border: 1px solid #FFE8D8 !important;">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 50px; height: 50px; background: var(--orange-pale); color: var(--orange);">
                    <i class="ti ti-target fs-3"></i>
                </div>
                <h4 class="fw-bold" style="font-family: 'Syne', sans-serif; color: var(--text-dark);">Our Mission</h4>
                <p class="text-muted m-0" style="font-size: 14.5px; line-height: 1.6;">
                    We are dedicated to providing seamless digital shopping, lightning-fast delivery options, and customer support that actually cares about you.
                </p>
            </div>
        </div>
    </div>

    {{-- CORE VALUE CARDS --}}
    <div class="card border-0 shadow-sm rounded-4 p-5 mb-5" style="background: #fff;">
        <h3 class="fw-bold text-center mb-4" style="font-family: 'Syne', sans-serif;">Our Core Values</h3>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px; background: var(--orange-pale); color: var(--orange);">
                    <i class="ti ti-shield-check fs-2"></i>
                </div>
                <h6 class="fw-bold">Trust & Integrity</h6>
                <p class="text-muted" style="font-size: 13.5px;">We guarantee authenticity in every single product we sell.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px; background: var(--orange-pale); color: var(--orange);">
                    <i class="ti ti-bolt fs-2"></i>
                </div>
                <h6 class="fw-bold">Speedy Delivery</h6>
                <p class="text-muted" style="font-size: 13.5px;">Your order is processed instantly and shipped fast.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px; background: var(--orange-pale); color: var(--orange);">
                    <i class="ti ti-heart fs-2"></i>
                </div>
                <h6 class="fw-bold">Customer First</h6>
                <p class="text-muted" style="font-size: 13.5px;">24/7 dedicated support ready to help you anytime.</p>
            </div>
        </div>
    </div>

    {{-- TIMELINE OR HISTORY --}}
    <div class="text-center">
        <h4 class="fw-bold mb-3" style="font-family: 'Syne', sans-serif;">The ZestShop Story</h4>
        <p class="text-muted mx-auto" style="max-width: 700px; font-size: 15px; line-height: 1.7;">
            Founded in 2026, ZestShop was born out of a desire to create a boutique online shopping experience that doesn't sacrifice performance or design. We represent the "zest" for life, fashion, and technology. What started as a small project has quickly grown into a community of modern style lovers.
        </p>
    </div>
</div>

@endsection
