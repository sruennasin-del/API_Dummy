<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $categories = \App\Models\MainCategory::where('status', 'active')->get();
        foreach ($categories as $cat) {
            $subIds = \App\Models\Category::where('main_category_id', $cat->id)->pluck('id');
            $cat->latest_products = \App\Models\Product::whereIn('category_id', $subIds)
                                        ->where('status', 'active')
                                        ->latest()
                                        ->take(10)
                                        ->get();
        }

        $collections = \App\Models\Collection::where('status', 'active')
                        ->with(['products' => function($query) {
                            $query->where('status', 'active')->latest()->take(10);
                        }])->get();

        $banners = \App\Models\Banner::active()->get();
        $boomPromotion = \App\Models\BoomPromotion::where('status', 'active')->latest()->first();
                        
        return view('Pages.home', compact('categories', 'collections', 'banners', 'boomPromotion'));
    }

    public function collection($slug)
    {
        $collection = \App\Models\Collection::where('slug', $slug)->firstOrFail();
        
        $products = $collection->products()->where('status', 'active')->latest()->paginate(12);

        return view('Pages.collection', compact('collection', 'products'));
    }

    public function category($slug)
    {
        $mainCategory = \App\Models\MainCategory::where('slug', $slug)->firstOrFail();
        $subCategories = \App\Models\Category::where('main_category_id', $mainCategory->id)->where('status', 'active')->get();
        
        // Initial load gets all active products under this main category
        $products = \App\Models\Product::whereIn('category_id', $subCategories->pluck('id'))
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('Pages.category', compact('mainCategory', 'subCategories', 'products'));
    }

    public function categoryAjax(Request $request, $slug)
    {
        $mainCategory = \App\Models\MainCategory::where('slug', $slug)->firstOrFail();
        $query = \App\Models\Product::where('status', 'active');

        if ($request->has('sub_category_id') && $request->sub_category_id != 'all') {
            $query->where('category_id', $request->sub_category_id);
        } else {
            $subCategoryIds = \App\Models\Category::where('main_category_id', $mainCategory->id)->pluck('id');
            $query->whereIn('category_id', $subCategoryIds);
        }

        $products = $query->latest()->paginate(12);
        
        // Render a partial view for the product grid
        return view('Pages.partials.product_grid', compact('products'))->render();
    }

    public function product($slug)
    {
        // Find product with its color variants, sizes, and images!
        $product = \App\Models\Product::with(['colorVariants.color', 'colorVariants.sizes', 'colorVariants.images'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Get related products (same category)
        $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(5)
            ->get();

        return view('Pages.product_detail', compact('product', 'relatedProducts'));
    }
    public function shop(Request $request)
    {
        $query = \App\Models\Product::where('status', 'active');

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $products = $query->latest()->paginate(24);

        return view('Pages.shop', compact('products'));
    }

    public function liveSearch(Request $request)
    {
        $q = $request->query('q');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $products = \App\Models\Product::where('status', 'active')
            ->where('title', 'like', '%' . $q . '%')
            ->take(6)
            ->get(['id', 'title', 'slug', 'price', 'image']);

        $results = [];
        foreach ($products as $product) {
            $imageUrl = $product->image ?? 'https://via.placeholder.com/100';
            if ($imageUrl && 
                !str_starts_with($imageUrl, 'http://') && 
                !str_starts_with($imageUrl, 'https://') && 
                !str_starts_with($imageUrl, '/storage/') && 
                !str_starts_with($imageUrl, 'storage/')
            ) {
                $imageUrl = \Illuminate\Support\Facades\Storage::url($imageUrl);
            }
            $results[] = [
                'title' => $product->title,
                'url'   => route('frontend.product', $product->slug),
                'price' => '$' . number_format($product->price, 2),
                'image' => $imageUrl
            ];
        }

        return response()->json($results);
    }

    public function about()
    {
        return view('Pages.about');
    }

    public function contact()
    {
        return view('Pages.contact');
    }

    public function toggleWishlist(Request $request)
    {
        $productId = (int) $request->input('id');
        $product = \App\Models\Product::findOrFail($productId);

        $wishlist = session()->get('wishlist', []);
        $wishlist = array_map('intval', $wishlist);

        if (in_array($productId, $wishlist, true)) {
            $wishlist = array_values(array_filter($wishlist, function($id) use ($productId) {
                return $id !== $productId;
            }));
            session()->put('wishlist', $wishlist);
            $status = 'removed';
            $message = '"' . $product->title . '" removed from favorites.';
        } else {
            $wishlist[] = $productId;
            session()->put('wishlist', $wishlist);
            $status = 'added';
            $message = '"' . $product->title . '" added to favorites!';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'count' => count($wishlist)
        ]);
    }

    public function wishlist()
    {
        $wishlistIds = session()->get('wishlist', []);
        $products = \App\Models\Product::where('status', 'active')
            ->whereIn('id', $wishlistIds)
            ->latest()
            ->paginate(24);

        return view('Pages.wishlist', compact('products'));
    }
}
