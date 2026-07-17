<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BoomPromotion;
use Illuminate\Support\Facades\Storage;

class BannerApiController extends Controller
{
    /**
     * Return all active banners.
     *
     * GET /api/mobile/banners
     */
    public function index()
    {
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

        return response()->json([
            'data' => $banners,
        ]);
    }

    /**
     * Return the active boom promotion.
     *
     * GET /api/mobile/promotions
     */
    public function promotions()
    {
        $promotion = BoomPromotion::where('status', 'active')->latest()->first();

        if (! $promotion) {
            return response()->json([
                'data' => null,
                'message' => 'No active promotion found.',
            ]);
        }

        return response()->json([
            'data' => [
                'id'          => $promotion->id,
                'title'       => $promotion->title,
                'subtitle'    => $promotion->subtitle,
                'description' => $promotion->description,
                'image_url'   => $this->resolveImageUrl($promotion->image),
                'shape'       => $promotion->shape,
                'link_url'    => $promotion->link_url,
                'status'      => $promotion->status,
            ],
        ]);
    }

    // ── Helper ───────────────────────────────────────────────────────

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
