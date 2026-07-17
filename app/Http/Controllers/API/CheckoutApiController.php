<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CheckoutApiController extends Controller
{
    /**
     * Place an order from the authenticated user's cart.
     *
     * POST /api/mobile/checkout
     * Header: Authorization: Bearer <token>
     * Body:
     * {
     *   "customer_name"    : "John Doe",
     *   "customer_email"   : "john@example.com",
     *   "customer_phone"   : "012345678",
     *   "customer_address" : "123 Street, Phnom Penh",
     *   "payment_method"   : "ABA"   // ABA | ACLEDA | Wing | Cash
     * }
     */
    public function checkout(Request $request)
    {
        $userId = $request->user()->id;
        $cart   = Cache::get("cart_user_{$userId}", []);

        if (empty($cart)) {
            return response()->json([
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $request->validate([
            'customer_name'    => ['required', 'string', 'max:255'],
            'customer_email'   => ['required', 'email', 'max:255'],
            'customer_phone'   => ['required', 'string', 'max:50'],
            'customer_address' => ['required', 'string', 'max:1000'],
            'payment_method'   => ['required', 'string', 'in:ABA,ACLEDA,Wing,Cash'],
        ]);

        // ── Calculate totals ─────────────────────────────────────────
        $subtotal = collect($cart)->sum(fn($item) => ($item['price'] ?? 2.50) * ($item['qty'] ?? 1));
        $service  = 1.50;
        $delivery = 2.00;
        $tax      = round($subtotal * 0.10, 2);

        // ── Coupon ───────────────────────────────────────────────────
        $appliedCoupon  = Cache::get("coupon_user_{$userId}");
        $couponCode     = null;
        $couponDiscount = 0;

        if ($appliedCoupon) {
            $couponCode = $appliedCoupon['code'];

            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $couponDiscount = $coupon->discountAmount($subtotal);
                $coupon->increment('used_count');
            } else {
                $couponCode     = null;
                $couponDiscount = 0;
            }
        }

        $grandTotal = max(0, round($subtotal + $service + $delivery + $tax - $couponDiscount, 2));

        // ── Generate unique order number ──────────────────────────────
        do {
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        // ── Create Order ──────────────────────────────────────────────
        $order = Order::create([
            'user_id'          => $userId,
            'order_number'     => $orderNumber,
            'customer_name'    => $request->customer_name,
            'customer_email'   => $request->customer_email,
            'customer_phone'   => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'payment_method'   => $request->payment_method,
            'coupon_code'      => $couponCode,
            'discount'         => $couponDiscount,
            'subtotal'         => $subtotal,
            'service_fee'      => $service,
            'delivery_fee'     => $delivery,
            'tax'              => $tax,
            'total'            => $grandTotal,
            'status'           => 'pending',
            'courier'          => 'ZestShop Courier',
            'eta'              => now()->addDays(2)->format('d/m/Y'),
        ]);

        // ── Create Order Items & Update Stock ─────────────────────────
        foreach ($cart as $item) {
            $productId = $item['id'] ?? null;

            OrderItem::create([
                'order_id'          => $order->id,
                'product_id'        => $productId,
                'product_title'     => $item['title'],
                'product_thumbnail' => $item['thumbnail'] ?? null,
                'price'             => $item['price'] ?? 2.50,
                'qty'               => $item['qty'],
            ]);

            if ($productId) {
                $product = Product::find($productId);
                if ($product) {
                    $product->decrement('stock', $item['qty']);
                    $product->increment('sales', $item['qty']);
                }
            }
        }

        // ── Clear cart & coupon ───────────────────────────────────────
        Cache::forget("cart_user_{$userId}");
        Cache::forget("coupon_user_{$userId}");

        // ── Return response ───────────────────────────────────────────
        return response()->json([
            'message' => 'Order placed successfully!',
            'order'   => [
                'id'               => $order->id,
                'order_number'     => $order->order_number,
                'status'           => $order->status,
                'customer_name'    => $order->customer_name,
                'customer_email'   => $order->customer_email,
                'customer_phone'   => $order->customer_phone,
                'customer_address' => $order->customer_address,
                'payment_method'   => $order->payment_method,
                'coupon_code'      => $order->coupon_code,
                'discount'         => $order->discount,
                'subtotal'         => $order->subtotal,
                'service_fee'      => $order->service_fee,
                'delivery_fee'     => $order->delivery_fee,
                'tax'              => $order->tax,
                'total'            => $order->total,
                'courier'          => $order->courier,
                'eta'              => $order->eta,
                'created_at'       => $order->created_at,
            ],
        ], 201);
    }
}
