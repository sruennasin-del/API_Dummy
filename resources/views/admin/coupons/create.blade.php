@extends('admin.layout.app')
@section('title', 'Create Coupon - ZestShop')
@section('content')

<div class="mb-4">
    <a href="{{ url('/admin/coupons') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2 px-3">
        <i class="ti ti-arrow-left"></i> Back to Coupons
    </a>
    <h1 class="h3 fw-bold mb-1" style="font-family:'Syne',sans-serif;">Create New Coupon</h1>
</div>

<form action="{{ url('/admin/coupons') }}" method="POST">
    @csrf
    <div class="row g-4">
        {{-- LEFT: Main Fields --}}
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
                                       value="{{ old('code') }}" required placeholder="e.g. SUMMER20"
                                       style="font-family:monospace;font-size:15px;letter-spacing:1px;text-transform:uppercase;">
                                <button type="button" class="btn btn-outline-secondary rounded-end-3 px-3" onclick="generateCode()" title="Generate random code">
                                    <i class="ti ti-refresh me-1"></i> Generate
                                </button>
                            </div>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Will be auto-uppercased. No spaces.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Description</label>
                            <input type="text" name="description" class="form-control rounded-3" value="{{ old('description') }}" placeholder="e.g. 20% off summer sale">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Discount Type <span class="text-danger">*</span></label>
                            <select name="type" id="discount_type" class="form-select rounded-3" onchange="toggleDiscountType(this.value)">
                                <option value="percent" {{ old('type','percent')==='percent'?'selected':'' }}>Percentage (%)</option>
                                <option value="fixed"   {{ old('type')==='fixed'?'selected':'' }}>Fixed Amount ($)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" id="value_label">Discount Value (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="value" class="form-control rounded-start-3 @error('value') is-invalid @enderror"
                                       value="{{ old('value') }}" required step="0.01" min="0.01" placeholder="20">
                                <span class="input-group-text rounded-end-3" id="value_suffix">%</span>
                            </div>
                            @error('value')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6" id="max_discount_group">
                            <label class="form-label fw-bold">Max Discount Cap ($)</label>
                            <input type="number" name="max_discount" class="form-control rounded-3" value="{{ old('max_discount') }}" step="0.01" min="0" placeholder="e.g. 50.00 (leave blank for no cap)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Minimum Order Amount ($)</label>
                            <input type="number" name="min_order" class="form-control rounded-3" value="{{ old('min_order', 0) }}" step="0.01" min="0">
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
                            <input type="number" name="usage_limit" class="form-control rounded-3" value="{{ old('usage_limit') }}" min="1" placeholder="Leave blank = unlimited">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Start Date</label>
                            <input type="date" name="starts_at" class="form-control rounded-3" value="{{ old('starts_at') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Expiry Date</label>
                            <input type="date" name="expires_at" class="form-control rounded-3 @error('expires_at') is-invalid @enderror" value="{{ old('expires_at') }}">
                            @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Publish --}}
        <div class="col-lg-4">
            <div class="card-premium sticky-top" style="top:80px;">
                <div class="card-premium-header"><h2 class="card-premium-title">Publish</h2></div>
                <div class="card-premium-body">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select rounded-3 mb-4">
                        <option value="active"   {{ old('status','active')==='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>Inactive (Draft)</option>
                    </select>
                    <button type="submit" class="btn w-100 rounded-pill text-white fw-bold py-2" style="background:var(--primary);">
                        <i class="ti ti-check me-1"></i> Save Coupon
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
        label.textContent  = 'Discount Value ($) *';
        suffix.textContent = '$';
        maxGrp.style.display = 'none';
    } else {
        label.textContent  = 'Discount Value (%) *';
        suffix.textContent = '%';
        maxGrp.style.display = '';
    }
}
toggleDiscountType('{{ old("type", "percent") }}');
</script>
@endsection
