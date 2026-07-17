<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class WishlistApiController extends Controller
{
    /**
     * Get the authenticated user's wishlist.
     *
     * GET /api/mobile/wishlist
     * Header: Authorization: Bearer <token>
     */
    public function index(Request $request)
    {
        $userId    = $request->user()->id;
        $wishlist  = $this->getWishlist($userId);

        if (empty($wishlist)) {
            return response()->json(['data' => [], 'count' => 0]);
        }

        $products = Product::where('status', 'active')
            ->whereIn('id', $wishlist)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        return response()->json([
            'data'  => $products,
            'count' => count($wishlist),
        ]);
    }

    /**
     * Toggle a product in/out of the wishlist.
     *
     * POST /api/mobile/wishlist/toggle
     * Header: Authorization: Bearer <token>
     * Body: { "product_id": 5 }
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:ec_products,id'],
        ]);

        $userId    = $request->user()->id;
        $productId = (int) $request->product_id;
        $product   = Product::findOrFail($productId);
        $wishlist  = $this->getWishlist($userId);

        if (in_array($productId, $wishlist, true)) {
            // Remove
            $wishlist = array_values(array_filter($wishlist, fn($id) => $id !== $productId));
            $status  = 'removed';
            $message = '"' . $product->title . '" removed from wishlist.';
        } else {
            // Add
            $wishlist[] = $productId;
            $status  = 'added';
            $message = '"' . $product->title . '" added to wishlist!';
        }

        $this->saveWishlist($userId, $wishlist);

        return response()->json([
            'status'  => $status,
            'message' => $message,
            'count'   => count($wishlist),
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    /**
     * Get wishlist from cache for a user (persistent 30 days).
     */
    private function getWishlist(int $userId): array
    {
        return Cache::get("wishlist_user_{$userId}", []);
    }

    /**
     * Save wishlist to cache (persistent 30 days).
     */
    private function saveWishlist(int $userId, array $wishlist): void
    {
        Cache::put("wishlist_user_{$userId}", $wishlist, now()->addDays(30));
    }

    private function formatProduct(Product $product): array
    {
        return [
            'id'        => $product->id,
            'title'     => $product->title,
            'slug'      => $product->slug,
            'price'     => (float) $product->price,
            'stock'     => $product->stock,
            'sales'     => $product->sales,
            'rating'    => $product->rating,
            'image_url' => $this->resolveImageUrl($product->image),
            'status'    => $product->status,
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
