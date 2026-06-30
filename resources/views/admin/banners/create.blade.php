@extends('admin.layout.app')
@section('title', 'Add Banner - ZestShop')
@section('content')

<div class="mb-4">
    <a href="{{ url('/admin/banners') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2 px-3">
        <i class="ti ti-arrow-left"></i> Back
    </a>
    <h1 class="h3 fw-bold mb-1" style="font-family:'Syne',sans-serif;">Add New Banner</h1>
</div>

<form action="{{ url('/admin/banners') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-premium mb-4">
                <div class="card-premium-header"><h2 class="card-premium-title">Slide Content</h2></div>
                <div class="card-premium-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tag Label <small class="text-muted">(optional)</small></label>
                            <input type="text" name="tag" class="form-control rounded-3" value="{{ old('tag') }}" placeholder="e.g. 🔥 Limited Offer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control rounded-3" value="{{ old('sort_order', 0) }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control rounded-3 @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Summer Sale">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Subtitle</label>
                            <input type="text" name="subtitle" class="form-control rounded-3" value="{{ old('subtitle') }}" placeholder="e.g. Up to 50% Off">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Short promotional text...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-premium">
                <div class="card-premium-header"><h2 class="card-premium-title">Buttons</h2></div>
                <div class="card-premium-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Primary Button Label</label>
                            <input type="text" name="btn_primary_label" class="form-control rounded-3" value="{{ old('btn_primary_label') }}" placeholder="e.g. Shop Now">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Primary Button URL</label>
                            <input type="text" name="btn_primary_url" class="form-control rounded-3" value="{{ old('btn_primary_url', '/shop') }}" placeholder="/shop">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Secondary Button Label</label>
                            <input type="text" name="btn_secondary_label" class="form-control rounded-3" value="{{ old('btn_secondary_label') }}" placeholder="e.g. View Deals">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Secondary Button URL</label>
                            <input type="text" name="btn_secondary_url" class="form-control rounded-3" value="{{ old('btn_secondary_url', '#') }}" placeholder="#">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-premium mb-4">
                <div class="card-premium-header"><h2 class="card-premium-title">Slide Image</h2></div>
                <div class="card-premium-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Image</label>
                        <input type="file" name="image" class="form-control rounded-3" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted">Recommended: 800×400px, JPG/PNG/WebP</small>
                    </div>
                    <div id="img-preview" class="rounded-3 overflow-hidden border" style="height:160px;background:#f8f8f8;display:flex;align-items:center;justify-content:center;">
                        <span class="text-muted"><i class="ti ti-photo fs-2"></i></span>
                    </div>

                    <hr>
                    <label class="form-label fw-bold">Background Gradient <small class="text-muted">(if no image)</small></label>
                    <input type="text" name="bg_gradient" class="form-control rounded-3" value="{{ old('bg_gradient', 'linear-gradient(130deg, #FF6B1A 0%, #FF9C5B 55%, #FFD6BB 100%)') }}">
                </div>
            </div>

            <div class="card-premium">
                <div class="card-premium-header"><h2 class="card-premium-title">Publish</h2></div>
                <div class="card-premium-body">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select rounded-3">
                        <option value="active" {{ old('status','active')==='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    <button type="submit" class="btn w-100 mt-3 rounded-pill text-white fw-bold" style="background:var(--primary);">
                        <i class="ti ti-check me-1"></i> Save Banner
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function previewImage(input) {
    const preview = document.getElementById('img-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
