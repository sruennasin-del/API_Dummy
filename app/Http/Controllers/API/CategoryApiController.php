<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryApiController extends Controller
{
    /**
     * Return all active main categories with their sub-categories.
     *
     * GET /api/mobile/categories/main
     */
    public function mainCategories()
    {
        $mainCategories = MainCategory::where('status', 'active')
            ->with(['categories' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get()
            ->map(function ($cat) {
                return [
                    'id'             => $cat->id,
                    'name'           => $cat->name,
                    'slug'           => $cat->slug,
                    'description'    => $cat->description,
                    'image_url'      => $this->resolveImageUrl($cat->image),
                    'layout_type'    => $cat->layout_type,
                    'is_home'        => (bool) $cat->is_home,
                    'sub_categories' => $cat->categories->map(fn($sub) => [
                        'id'       => $sub->id,
                        'name'     => $sub->name,
                        'slug'     => $sub->slug,
                        'image_url'=> $this->resolveImageUrl($sub->image),
                    ]),
                ];
            });

        return response()->json(['data' => $mainCategories]);
    }

    /**
     * Return all active sub-categories (with their parent main category).
     *
     * GET /api/mobile/categories
     */
    public function categories()
    {
        $categories = Category::where('status', 'active')
            ->with('mainCategory')
            ->get()
            ->map(function ($cat) {
                return [
                    'id'            => $cat->id,
                    'name'          => $cat->name,
                    'slug'          => $cat->slug,
                    'description'   => $cat->description,
                    'image_url'     => $this->resolveImageUrl($cat->image),
                    'main_category' => $cat->mainCategory ? [
                        'id'   => $cat->mainCategory->id,
                        'name' => $cat->mainCategory->name,
                        'slug' => $cat->mainCategory->slug,
                    ] : null,
                ];
            });

        return response()->json(['data' => $categories]);
    }

    /**
     * Return paginated products for a given sub-category ID.
     *
     * GET /api/mobile/categories/{id}/products
     *
     * Query parameters:
     *  - sort     : 'latest' | 'price_asc' | 'price_desc' | 'popular' | 'top_rated'
     *  - per_page : results per page (default 20, max 50)
     */
    public function products(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $query = Product::where('category_id', $id)->where('status', 'active');

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
            'category' => [
                'id'   => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
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
