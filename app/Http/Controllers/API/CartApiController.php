<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CartApiController extends Controller
{
    /**
     * Get the authenticated user's cart.
     *
     * GET /api/mobile/cart
     * Header: Authorization: Bearer <token>
     */
    public function index(Request $request)
    {
        $cart    = $this->getCart($request->user()->id);
        $summary = $this->calcSummary($cart);

        return response()->json([
            'data'    => array_values($cart),
            'summary' => $summary,
            'count'   => count($cart),
        ]);
    }

    /**
     * Add an item to the cart (or increase qty if same variant exists).
     *
     * POST /api/mobile/cart/add
     * Header: Authorization: Bearer <token>
     * Body:
     * {
     *   "id"         : 5,       // product_id
     *   "variant_id" : 2,       // product_color id
     *   "size_id"    : 3,
     *   "title"      : "Nike Air",
     *   "price"      : 49.99,
     *   "thumbnail"  : "storage/...",
     *   "color_name" : "Red",
     *   "size_name"  : "M",
     *   "qty"        : 1        // optional, defaults to 1
     * }
     */
    public function add(Request $request)
    {
        $request->validate([
            'id'         => ['required', 'integer'],
            'variant_id' => ['required', 'integer'],
            'size_id'    => ['required', 'integer'],
            'title'      => ['required', 'string'],
            'price'      => ['required', 'numeric', 'min:0'],
            'qty'        => ['sometimes', 'integer', 'min:1'],
        ]);

        $userId = $request->user()->id;
        $cart   = $this->getCart($userId);

        $cartKey = $request->id . '_' . $request->variant_id . '_' . $request->size_id;
        $qty     = (int) ($request->qty ?? 1);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $qty;
        } else {
            $cart[$cartKey] = [
                'id'          => $request->id,
                'variant_id'  => $request->variant_id,
                'size_id'     => $request->size_id,
                'title'       => $request->title,
                'price'       => (float) $request->price,
                'thumbnail'   => $request->thumbnail,
                'color_name'  => $request->color_name,
                'size_name'   => $request->size_name,
                'qty'         => $qty,
            ];
        }

        $this->saveCart($userId, $cart);

        return response()->json([
            'message' => 'Item added to cart.',
            'data'    => array_values($cart),
            'summary' => $this->calcSummary($cart),
            'count'   => count($cart),
        ]);
    }

    /**
     * Increase item quantity by 1.
     *
     * POST /api/mobile/cart/increase
     * Body: { "cart_key": "5_2_3" }
     */
    public function increase(Request $request)
    {
        $request->validate(['cart_key' => ['required', 'string']]);

        $userId = $request->user()->id;
        $cart   = $this->getCart($userId);

        if (! isset($cart[$request->cart_key])) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        $cart[$request->cart_key]['qty']++;
        $this->saveCart($userId, $cart);

        return response()->json([
            'message' => 'Quantity increased.',
            'data'    => array_values($cart),
            'summary' => $this->calcSummary($cart),
            'count'   => count($cart),
        ]);
    }

    /**
     * Decrease item quantity by 1 (minimum 1).
     *
     * POST /api/mobile/cart/decrease
     * Body: { "cart_key": "5_2_3" }
     */
    public function decrease(Request $request)
    {
        $request->validate(['cart_key' => ['required', 'string']]);

        $userId = $request->user()->id;
        $cart   = $this->getCart($userId);

        if (! isset($cart[$request->cart_key])) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        if ($cart[$request->cart_key]['qty'] > 1) {
            $cart[$request->cart_key]['qty']--;
        }

        $this->saveCart($userId, $cart);

        return response()->json([
            'message' => 'Quantity decreased.',
            'data'    => array_values($cart),
            'summary' => $this->calcSummary($cart),
            'count'   => count($cart),
        ]);
    }

    /**
     * Remove a specific item from the cart.
     *
     * DELETE /api/mobile/cart/remove
     * Body: { "cart_key": "5_2_3" }
     */
    public function remove(Request $request)
    {
        $request->validate(['cart_key' => ['required', 'string']]);

        $userId = $request->user()->id;
        $cart   = $this->getCart($userId);

        unset($cart[$request->cart_key]);
        $this->saveCart($userId, $cart);

        return response()->json([
            'message' => 'Item removed from cart.',
            'data'    => array_values($cart),
            'summary' => $this->calcSummary($cart),
            'count'   => count($cart),
        ]);
    }

    /**
     * Clear all items from the cart.
     *
     * DELETE /api/mobile/cart/clear
     */
    public function clear(Request $request)
    {
        $userId = $request->user()->id;
        $this->saveCart($userId, []);
        Cache::forget("coupon_user_{$userId}");

        return response()->json([
            'message' => 'Cart cleared.',
            'data'    => [],
            'summary' => $this->calcSummary([]),
            'count'   => 0,
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    private function getCart(int $userId): array
    {
        return Cache::get("cart_user_{$userId}", []);
    }

    private function saveCart(int $userId, array $cart): void
    {
        Cache::put("cart_user_{$userId}", $cart, now()->addDays(7));
    }

    private function calcSummary(array $cart): array
    {
        $subtotal = collect($cart)->sum(fn($item) => ($item['price'] ?? 0) * ($item['qty'] ?? 1));
        $service  = 1.50;
        $delivery = 2.00;
        $tax      = round($subtotal * 0.10, 2);
        $total    = round($subtotal + $service + $delivery + $tax, 2);

        return [
            'subtotal'     => round($subtotal, 2),
            'service_fee'  => $service,
            'delivery_fee' => $delivery,
            'tax'          => $tax,
            'total'        => $total,
        ];
    }
}
