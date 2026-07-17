<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CouponApiController extends Controller
{
    /**
     * Apply a coupon code to the current cart.
     *
     * POST /api/mobile/coupon/apply
     * Header: Authorization: Bearer <token>
     * Body: { "code": "SAVE20", "subtotal": 100.00 }
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code'     => ['required', 'string'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $userId   = $request->user()->id;
        $subtotal = (float) $request->subtotal;

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (! $coupon) {
            return response()->json([
                'message' => 'Invalid coupon code.',
            ], 422);
        }

        if (! $coupon->isValid($subtotal)) {
            $reason = 'This coupon cannot be applied.';

            if ($coupon->status !== 'active') {
                $reason = 'This coupon is no longer active.';
            } elseif ($coupon->expires_at && $coupon->expires_at->isPast()) {
                $reason = 'This coupon has expired.';
            } elseif ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                $reason = 'This coupon has reached its usage limit.';
            } elseif ($subtotal < $coupon->min_order) {
                $reason = 'Minimum order amount of $' . number_format($coupon->min_order, 2) . ' required.';
            }

            return response()->json(['message' => $reason], 422);
        }

        $discount = $coupon->discountAmount($subtotal);

        // Save applied coupon in cache per user
        Cache::put("coupon_user_{$userId}", [
            'code'     => $coupon->code,
            'discount' => $discount,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
        ], now()->addHours(2));

        return response()->json([
            'message'  => 'Coupon applied successfully!',
            'coupon'   => [
                'code'        => $coupon->code,
                'description' => $coupon->description,
                'type'        => $coupon->type,
                'value'       => $coupon->value,
            ],
            'discount' => $discount,
            'new_total'=> round($subtotal - $discount, 2),
        ]);
    }

    /**
     * Remove the applied coupon.
     *
     * POST /api/mobile/coupon/remove
     * Header: Authorization: Bearer <token>
     */
    public function remove(Request $request)
    {
        $userId = $request->user()->id;
        Cache::forget("coupon_user_{$userId}");

        return response()->json([
            'message' => 'Coupon removed.',
        ]);
    }
}
