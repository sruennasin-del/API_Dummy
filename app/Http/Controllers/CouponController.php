<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $coupons = $query->latest()->paginate(15)->withQueryString();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|max:50|unique:coupons,code',
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|numeric|min:0.01',
            'min_order'   => 'nullable|numeric|min:0',
            'max_discount'=> 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at'   => 'nullable|date',
            'expires_at'  => 'nullable|date|after_or_equal:starts_at',
            'status'      => 'required|in:active,inactive',
        ]);

        Coupon::create([
            'code'         => strtoupper(trim($request->code)),
            'description'  => $request->description,
            'type'         => $request->type,
            'value'        => $request->value,
            'min_order'    => $request->min_order ?? 0,
            'max_discount' => $request->max_discount,
            'usage_limit'  => $request->usage_limit,
            'starts_at'    => $request->starts_at,
            'expires_at'   => $request->expires_at,
            'status'       => $request->status,
        ]);

        return redirect('/admin/coupons')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'        => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|numeric|min:0.01',
            'min_order'   => 'nullable|numeric|min:0',
            'max_discount'=> 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at'   => 'nullable|date',
            'expires_at'  => 'nullable|date|after_or_equal:starts_at',
            'status'      => 'required|in:active,inactive',
        ]);

        $coupon->update([
            'code'         => strtoupper(trim($request->code)),
            'description'  => $request->description,
            'type'         => $request->type,
            'value'        => $request->value,
            'min_order'    => $request->min_order ?? 0,
            'max_discount' => $request->max_discount,
            'usage_limit'  => $request->usage_limit,
            'starts_at'    => $request->starts_at,
            'expires_at'   => $request->expires_at,
            'status'       => $request->status,
        ]);

        return redirect('/admin/coupons')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect('/admin/coupons')->with('success', 'Coupon deleted.');
    }

    /** AJAX: apply coupon code from cart page */
    public function apply(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();
        $subtotal = $request->subtotal ?? 0;

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.']);
        }

        if (!$coupon->isValid($subtotal)) {
            $msg = 'This coupon is not valid.';
            if ($coupon->expires_at && $coupon->expires_at->isPast()) $msg = 'This coupon has expired.';
            if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) $msg = 'This coupon has reached its usage limit.';
            if ($subtotal < $coupon->min_order) $msg = 'Minimum order of $' . number_format($coupon->min_order, 2) . ' required.';
            return response()->json(['success' => false, 'message' => $msg]);
        }

        $discount = $coupon->discountAmount($subtotal);

        // Store coupon in session
        session(['applied_coupon' => ['code' => $coupon->code, 'discount' => $discount, 'id' => $coupon->id]]);

        return response()->json([
            'success'  => true,
            'message'  => 'Coupon applied! You save $' . number_format($discount, 2),
            'discount' => $discount,
            'code'     => $coupon->code,
        ]);
    }

    /** Remove applied coupon from session */
    public function remove()
    {
        session()->forget('applied_coupon');
        return response()->json(['success' => true]);
    }
}
