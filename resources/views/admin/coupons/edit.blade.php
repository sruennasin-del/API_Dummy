@extends('admin.layout.app')
@section('title', 'Edit Coupon - ZestShop')
@section('content')

<div class="mb-4">
    <a href="{{ url('/admin/coupons') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2 px-3">
        <i class="ti ti-arrow-left"></i> Back
    </a>
    <h1 class="h3 fw-bold mb-1" style="font-family:'Syne',sans-serif;">Edit Coupon: <span style="color:var(--primary);font-family:monospace;">{{ $coupon->code }}</span></h1>
</div>

<form action="{{ url('/admin/coupons/'.$coupon->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-premium mb-4">
                <div class="card-premium-header"><h2 class="card-premium-title">Coupon Details</h2></div>
                <div class="card-premium-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Coupon Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="code" id="coupon_code_input"
                                       class="form-control rounded-start-3 @error('code') is-invalid @enderror"
                                       value="{{ old('code', $coupon->code) }}" required
                                       style="font-family:monospace;font-size:15px;letter-spacing:1px;text-transform:uppercase;">
                                <button type="button" class="btn btn-outline-secondary rounded-end-3 px-3" onclick="generateCode()" title="Generate random code">
                                    <i class="ti ti-refresh me-1"></i> Generate
                                </button>
                            </div>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Description</label>
                            <input type="text" name="description" class="form-control rounded-3" value="{{ old('description', $coupon->description) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Discount Type</label>
                            <select name="type" id="discount_type" class="form-select rounded-3" onchange="toggleDiscountType(this.value)">
                                <option value="percent" {{ old('type',$coupon->type)==='percent'?'selected':'' }}>Percentage (%)</option>
                                <option value="fixed"   {{ old('type',$coupon->type)==='fixed'?'selected':'' }}>Fixed Amount ($)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" id="value_label">Value <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="value" class="form-control rounded-start-3" value="{{ old('value', $coupon->value) }}" required step="0.01" min="0.01">
                                <span class="input-group-text rounded-end-3" id="value_suffix">%</span>
                            </div>
                        </div>
                        <div class="col-md-6" id="max_discount_group">
                            <label class="form-label fw-bold">Max Discount Cap ($)</label>
                            <input type="number" name="max_discount" class="form-control rounded-3" value="{{ old('max_discount', $coupon->max_discount) }}" step="0.01" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Minimum Order ($)</label>
                            <input type="number" name="min_order" class="form-control rounded-3" value="{{ old('min_order', $coupon->min_order) }}" step="0.01" min="0">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-premium">
                <div class="card-premium-header"><h2 class="card-premium-title">Validity & Limits</h2></div>
                <div class="card-premium-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Usage Limit</label>
                            <input type="number" name="usage_limit" class="form-control rounded-3" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1" placeholder="Unlimited">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Used Count</label>
                            <input type="text" class="form-control rounded-3 bg-light" value="{{ $coupon->used_count }}" readonly>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Start Date</label>
                            <input type="date" name="starts_at" class="form-control rounded-3" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Expiry Date</label>
                            <input type="date" name="expires_at" class="form-control rounded-3 @error('expires_at') is-invalid @enderror" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                            @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-premium sticky-top" style="top:80px;">
                <div class="card-premium-header"><h2 class="card-premium-title">Publish</h2></div>
                <div class="card-premium-body">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select rounded-3 mb-4">
                        <option value="active"   {{ old('status',$coupon->status)==='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status',$coupon->status)==='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    <button type="submit" class="btn w-100 rounded-pill text-white fw-bold py-2" style="background:var(--primary);">
                        <i class="ti ti-check me-1"></i> Update Coupon
                    </button>
                    <a href="{{ url('/admin/coupons') }}" class="btn w-100 btn-outline-secondary rounded-pill mt-2">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 6; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('coupon_code_input').value = code;
}

function toggleDiscountType(type) {
    const label  = document.getElementById('value_label');
    const suffix = document.getElementById('value_suffix');
    const maxGrp = document.getElementById('max_discount_group');
    if (type === 'fixed') {
        label.textContent  = 'Value ($) *';
        suffix.textContent = '$';
        maxGrp.style.display = 'none';
    } else {
        label.textContent  = 'Value (%) *';
        suffix.textContent = '%';
        maxGrp.style.display = '';
    }
}
toggleDiscountType('{{ old("type", $coupon->type) }}');
</script>
@endsection
