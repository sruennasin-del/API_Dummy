<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderApiController extends Controller
{
    /**
     * Return paginated order history for the authenticated user.
     *
     * GET /api/mobile/orders
     * Header: Authorization: Bearer <token>
     *
     * Query parameters:
     *  - status   : filter by status (pending|processing|shipped|delivered|cancelled|refund_requested|refunded)
     *  - per_page : results per page (default 15, max 50)
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $query = Order::where('user_id', $userId)->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = min((int) ($request->per_page ?? 15), 50);
        $orders  = $query->paginate($perPage);

        return response()->json([
            'data' => $orders->getCollection()->map(fn($o) => $this->formatOrder($o)),
            'pagination' => [
                'current_page'  => $orders->currentPage(),
                'last_page'     => $orders->lastPage(),
                'per_page'      => $orders->perPage(),
                'total'         => $orders->total(),
                'next_page_url' => $orders->nextPageUrl(),
                'prev_page_url' => $orders->previousPageUrl(),
            ],
        ]);
    }

    /**
     * Return order detail with items.
     *
     * GET /api/mobile/orders/{id}
     * Header: Authorization: Bearer <token>
     */
    public function show(Request $request, int $id)
    {
        $userId = $request->user()->id;

        $order = Order::with('items')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $items = $order->items->map(function ($item) {
            return [
                'id'                => $item->id,
                'product_id'        => $item->product_id,
                'product_title'     => $item->product_title,
                'product_thumbnail' => $this->resolveImageUrl($item->product_thumbnail),
                'price'             => (float) $item->price,
                'qty'               => $item->qty,
                'line_total'        => round($item->price * $item->qty, 2),
            ];
        });

        return response()->json([
            'data' => array_merge($this->formatOrder($order), ['items' => $items]),
        ]);
    }

    /**
     * Cancel an order (only if status is 'pending').
     *
     * POST /api/mobile/orders/{id}/cancel
     * Header: Authorization: Bearer <token>
     */
    public function cancel(Request $request, int $id)
    {
        $userId = $request->user()->id;

        $order = Order::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending orders can be cancelled.',
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Order cancelled successfully.',
            'data'    => $this->formatOrder($order->fresh()),
        ]);
    }

    /**
     * Request a refund for a delivered order.
     *
     * POST /api/mobile/orders/{id}/refund
     * Header: Authorization: Bearer <token>
     */
    public function refund(Request $request, int $id)
    {
        $userId = $request->user()->id;

        $order = Order::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($order->status !== 'delivered') {
            return response()->json([
                'message' => 'Refund requests can only be made for delivered orders.',
            ], 422);
        }

        $order->update(['status' => 'refund_requested']);

        return response()->json([
            'message' => 'Refund request submitted. Our team will review it shortly.',
            'data'    => $this->formatOrder($order->fresh()),
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    private function formatOrder(Order $order): array
    {
        return [
            'id'               => $order->id,
            'order_number'     => $order->order_number,
            'status'           => $order->status,
            'customer_name'    => $order->customer_name,
            'customer_email'   => $order->customer_email,
            'customer_phone'   => $order->customer_phone,
            'customer_address' => $order->customer_address,
            'payment_method'   => $order->payment_method,
            'coupon_code'      => $order->coupon_code,
            'discount'         => (float) $order->discount,
            'subtotal'         => (float) $order->subtotal,
            'service_fee'      => (float) $order->service_fee,
            'delivery_fee'     => (float) $order->delivery_fee,
            'tax'              => (float) $order->tax,
            'total'            => (float) $order->total,
            'courier'          => $order->courier,
            'tracking_number'  => $order->tracking_number,
            'eta'              => $order->eta,
            'created_at'       => $order->created_at,
            'updated_at'       => $order->updated_at,
        ];
    }

    private function resolveImageUrl(?string $image): ?string
    {
        if (! $image) return null;

        if (
            str_starts_with($image, 'http://') ||
            str_starts_with($image, 'https://')
        ) {
            return $image;
        }

        return Storage::url($image);
    }
}
