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
                        
        return view('Pages.home', compact('categories', 'collections', 'banners'));
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
}
