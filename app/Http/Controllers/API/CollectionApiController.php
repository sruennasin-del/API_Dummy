<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CollectionApiController extends Controller
{
    /**
     * Return all active collections.
     *
     * GET /api/mobile/collections
     */
    public function index()
    {
        $collections = Collection::where('status', 'active')
            ->get()
            ->map(function ($col) {
                return [
                    'id'          => $col->id,
                    'name'        => $col->name,
                    'slug'        => $col->slug,
                    'description' => $col->description,
                    'image_url'   => $this->resolveImageUrl($col->image),
                    'status'      => $col->status,
                ];
            });

        return response()->json(['data' => $collections]);
    }

    /**
     * Return paginated products in a collection.
     *
     * GET /api/mobile/collections/{slug}/products
     *
     * Query parameters:
     *  - sort     : 'latest' | 'price_asc' | 'price_desc' | 'popular' | 'top_rated'
     *  - per_page : results per page (default 20, max 50)
     */
    public function products(Request $request, string $slug)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();

        $query = $collection->products()->where('status', 'active');

        match ($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderBy('sales', 'desc'),
            'top_rated'  => $query->orderBy('rating', 'desc'),
            default      => $query->latest('ec_products.created_at'),
        };

        $perPage = min((int) ($request->per_page ?? 20), 50);
        $products = $query->paginate($perPage);

        return response()->json([
            'collection' => [
                'id'          => $collection->id,
                'name'        => $collection->name,
                'slug'        => $collection->slug,
                'description' => $collection->description,
                'image_url'   => $this->resolveImageUrl($collection->image),
            ],
            'data' => $products->getCollection()->map(fn($p) => $this->formatProduct($p)),
            'pagination' => [
                'current_page'  => $products->currentPage(),
                'last_page'     => $products->lastPage(),
                'per_page'      => $products->perPage(),
                'total'         => $products->total(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────

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
