<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BoomPromotion;
use App\Models\Collection;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class HomeApiController extends Controller
{
    /**
     * Return all home-screen data in a single request.
     *
     * GET /api/mobile/home
     *
     * Response includes:
     *  - banners        : active banners (sorted)
     *  - boom_promotion : active boom promotion (latest)
     *  - main_categories: active main categories, each with latest products
     *  - collections    : active collections, each with latest products
     */
    public function index()
    {
        // ── Banners ─────────────────────────────────────────────────
        $banners = Banner::active()->get()->map(function ($banner) {
            return [
                'id'                  => $banner->id,
                'tag'                 => $banner->tag,
                'title'               => $banner->title,
                'subtitle'            => $banner->subtitle,
                'description'         => $banner->description,
                'btn_primary_label'   => $banner->btn_primary_label,
                'btn_primary_url'     => $banner->btn_primary_url,
                'btn_secondary_label' => $banner->btn_secondary_label,
                'btn_secondary_url'   => $banner->btn_secondary_url,
                'image_url'           => $this->resolveImageUrl($banner->image),
                'bg_gradient'         => $banner->bg_gradient,
                'sort_order'          => $banner->sort_order,
            ];
        });

        // ── Boom Promotion ───────────────────────────────────────────
        $promotion = BoomPromotion::where('status', 'active')->latest()->first();
        $boomPromotion = $promotion ? [
            'id'          => $promotion->id,
            'title'       => $promotion->title,
            'subtitle'    => $promotion->subtitle,
            'description' => $promotion->description,
            'image_url'   => $this->resolveImageUrl($promotion->image),
            'shape'       => $promotion->shape,
            'link_url'    => $promotion->link_url,
        ] : null;

        // ── Main Categories with Products ────────────────────────────
        $mainCategories = MainCategory::where('status', 'active')->get()->map(function ($cat) {
            $subIds = Category::where('main_category_id', $cat->id)->pluck('id');
            $products = Product::whereIn('category_id', $subIds)
                ->where('status', 'active')
                ->latest()
                ->take(10)
                ->get()
                ->map(fn($p) => $this->formatProduct($p));

            return [
                'id'          => $cat->id,
                'name'        => $cat->name,
                'slug'        => $cat->slug,
                'description' => $cat->description,
                'image_url'   => $this->resolveImageUrl($cat->image),
                'layout_type' => $cat->layout_type,
                'products'    => $products,
            ];
        });

        // ── Collections with Products ────────────────────────────────
        $collections = Collection::where('status', 'active')
            ->with(['products' => function ($query) {
                $query->where('status', 'active')->latest()->take(10);
            }])
            ->get()
            ->map(function ($col) {
                return [
                    'id'          => $col->id,
                    'name'        => $col->name,
                    'slug'        => $col->slug,
                    'description' => $col->description,
                    'image_url'   => $this->resolveImageUrl($col->image),
                    'products'    => $col->products->map(fn($p) => $this->formatProduct($p)),
                ];
            });

        return response()->json([
            'banners'          => $banners,
            'boom_promotion'   => $boomPromotion,
            'main_categories'  => $mainCategories,
            'collections'      => $collections,
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
