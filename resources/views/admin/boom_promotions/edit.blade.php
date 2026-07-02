@extends('admin.layout.app')
@section('title', 'Edit Promotion - ZestShop')
@section('content')

<div class="mb-4">
    <a href="{{ url('/admin/boom-promotions') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2 px-3">
        <i class="ti ti-arrow-left"></i> Back
    </a>
    <h1 class="h3 fw-bold mb-1" style="font-family:'Syne',sans-serif;">Edit Boom Promotion</h1>
</div>

<form action="{{ url('/admin/boom-promotions/'.$promotion->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-premium mb-4">
                <div class="card-premium-header"><h2 class="card-premium-title">Promotion Content</h2></div>
                <div class="card-premium-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control rounded-3 @error('title') is-invalid @enderror" value="{{ old('title', $promotion->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Subtitle</label>
                            <input type="text" name="subtitle" class="form-control rounded-3" value="{{ old('subtitle', $promotion->subtitle) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Badge Shape <span class="text-danger">*</span></label>
                            <select name="shape" class="form-select rounded-3 @error('shape') is-invalid @enderror" required>
                                <option value="starburst" {{ old('shape', $promotion->shape) === 'starburst' ? 'selected' : '' }}>💥 Starburst (Spiky)</option>
                                <option value="circle" {{ old('shape', $promotion->shape) === 'circle' ? 'selected' : '' }}>● Circle</option>
                                <option value="heart" {{ old('shape', $promotion->shape) === 'heart' ? 'selected' : '' }}>❤ Heart</option>
                                <option value="square" {{ old('shape', $promotion->shape) === 'square' ? 'selected' : '' }}>■ Rounded Square</option>
                            </select>
                            @error('shape')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Link URL <small class="text-muted">(redirect when clicked)</small></label>
                            <input type="text" name="link_url" class="form-control rounded-3" value="{{ old('link_url', $promotion->link_url) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="3">{{ old('description', $promotion->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-premium mb-4">
                <div class="card-premium-header"><h2 class="card-premium-title">Promo Image</h2></div>
                <div class="card-premium-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Banner/Image</label>
                        <input type="file" name="image" class="form-control rounded-3" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted">Recommended: 600×400px or square, JPG/PNG/WebP</small>
                    </div>
                    <div id="img-preview" class="rounded-3 overflow-hidden border" style="height:160px;background:#f8f8f8;display:flex;align-items:center;justify-content:center;">
                        @if($promotion->image)
                            <img src="{{ Storage::url($promotion->image) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <span class="text-muted"><i class="ti ti-photo fs-2"></i></span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-premium">
                <div class="card-premium-header"><h2 class="card-premium-title">Publish</h2></div>
                <div class="card-premium-body">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select rounded-3">
                        <option value="active" {{ old('status', $promotion->status)==='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status', $promotion->status)==='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    <button type="submit" class="btn w-100 mt-3 rounded-pill text-white fw-bold" style="background:var(--primary);">
                        <i class="ti ti-check me-1"></i> Update Promotion
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
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        @if($promotion->image)
            preview.innerHTML = `<img src="{{ Storage::url($promotion->image) }}" style="width:100%;height:100%;object-fit:cover;">`;
        @else
            preview.innerHTML = `<span class="text-muted"><i class="ti ti-photo fs-2"></i></span>`;
        @endif
    }
}
</script>
@endsection
