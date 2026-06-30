@extends('layouts.welcome')

@section('content')
<div class="container py-5 mt-5">
    <!-- Collection Header -->
    <div class="text-center mb-5">
        <h1 class="fw-bold" style="font-size: 36px; text-transform: uppercase;">{{ $collection->name }}</h1>
        <p class="text-muted">{{ $collection->description ?? 'Shop our latest ' . strtolower($collection->name) . ' collection.' }}</p>
    </div>

    <!-- Product Grid Container -->
    <div id="product-grid-container" class="position-relative">
        <div id="product-grid-content">
            @if($products->count() > 0)
                @include('Pages.partials.product_grid', ['products' => $products])
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <h4>No products found in this collection.</h4>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
