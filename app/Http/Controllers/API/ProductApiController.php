<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductApiController extends Controller
{
    /**
     * Return paginated product list with optional filters.
     *
     * GET /api/mobile/products
     *
     * Query parameters:
     *  - q           : search term (title)
     *  - category_id : filter by sub-category ID
     *  - min_price   : filter by minimum price
     *  - max_price   : filter by maximum price
     *  - sort        : 'latest' | 'price_asc' | 'price_desc' | 'popular' | 'top_rated'
     *  - per_page    : results per page (default 20, max 50)
     */
    public function index(Request $request)
    {
        $query = Product::where('status', 'active');

        // ── Filters ──────────────────────────────────────────────────
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // ── Sorting ───────────────────────────────────────────────────
        match ($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderBy('sales', 'desc'),
            'top_rated'  => $query->orderBy('rating', 'desc'),
            default      => $query->latest(),
        };

        $perPage = min((int) ($request->per_page ?? 20), 50);
        $products = $query->paginate($perPage);

        return response()->json([
            'data'       => $products->getCollection()->map(fn($p) => $this->formatProduct($p)),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
                'next_page_url'=> $products->nextPageUrl(),
                'prev_page_url'=> $products->previousPageUrl(),
            ],
        ]);
    }

    /**
     * Return detailed product info including color variants, sizes, images.
     *
     * GET /api/mobile/products/{slug}
     */
    public function show(string $slug)
    {
        $product = Product::with([
            'colorVariants.color',
            'colorVariants.sizes',
            'colorVariants.images',
            'category.mainCategory',
        ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Related products (same category, different product)
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(10)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        // Format color variants
        $colorVariants = $product->colorVariants->map(function ($variant) {
            return [
                'id'        => $variant->id,
                'color_id'  => $variant->color_id,
                'color'     => $variant->color ? [
                    'id'   => $variant->color->id,
                    'name' => $variant->color->name,
                    'hex'  => $variant->color->hex ?? null,
                ] : null,
                'price'     => (float) ($variant->price ?? 0),
                'sizes'     => $variant->sizes->map(fn($s) => [
                    'id'   => $s->id,
                    'name' => $s->name,
                ]),
                'images'    => $variant->images->map(fn($img) => [
                    'id'        => $img->id,
                    'image_url' => $this->resolveImageUrl($img->image),
                ]),
            ];
        });

        return response()->json([
            'data' => [
                'id'             => $product->id,
                'title'          => $product->title,
                'slug'           => $product->slug,
                'price'          => (float) $product->price,
                'stock'          => $product->stock,
                'sales'          => $product->sales,
                'rating'         => $product->rating,
                'description'    => $product->description,
                'image_url'      => $this->resolveImageUrl($product->image),
                'status'         => $product->status,
                'category'       => $product->category ? [
                    'id'            => $product->category->id,
                    'name'          => $product->category->name,
                    'slug'          => $product->category->slug,
                    'main_category' => $product->category->mainCategory ? [
                        'id'   => $product->category->mainCategory->id,
                        'name' => $product->category->mainCategory->name,
                        'slug' => $product->category->mainCategory->slug,
                    ] : null,
                ] : null,
                'color_variants' => $colorVariants,
                'related'        => $related,
            ],
        ]);
    }

    /**
     * Live search products by title.
     *
     * GET /api/mobile/products/search?q=keyword
     */
    public function search(Request $request)
    {
        $q = $request->query('q', '');

        if (strlen($q) < 2) {
            return response()->json(['data' => []]);
        }

        $products = Product::where('status', 'active')
            ->where('title', 'like', '%' . $q . '%')
            ->take(10)
            ->get(['id', 'title', 'slug', 'price', 'image', 'rating']);

        $results = $products->map(function ($product) {
            return [
                'id'        => $product->id,
                'title'     => $product->title,
                'slug'      => $product->slug,
                'price'     => (float) $product->price,
                'rating'    => $product->rating,
                'image_url' => $this->resolveImageUrl($product->image),
            ];
        });

        return response()->json(['data' => $results]);
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
