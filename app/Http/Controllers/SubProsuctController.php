<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SubProsuctController extends Controller
{
    /**
     * Display products belonging to a specific category.
     */
    public function show($id)
    {
        $category = null;
        $isMainCategory = false;
        
        if (is_numeric($id)) {
            $category = \App\Models\MainCategory::find($id);
            if ($category) {
                $isMainCategory = true;
            } else {
                $category = Category::find($id);
            }
        } else {
            $category = \App\Models\MainCategory::where('slug', $id)->first();
            if ($category) {
                $isMainCategory = true;
            } else {
                $category = Category::where('slug', $id)->first();
            }
        }

        if (!$category) {
            abort(404);
        }

        if ($isMainCategory) {
            // It's a Main Category, get products from all child categories
            $subCategoryIds = Category::where('main_category_id', $category->id)->pluck('id');
            $productsQuery = Product::whereIn('category_id', $subCategoryIds);
        } else {
            // It's a sub-category
            $productsQuery = Product::where('category_id', $category->id);
        }

        $products = $productsQuery->where('status', 'active')
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'description' => $product->description,
                    'price' => $product->price,
                    'rating' => $product->rating ?? 4.5,
                    'thumbnail' => $product->image,
                    'discountPercentage' => 10,
                    'category' => $product->category ? $product->category->name : 'General'
                ];
            });

        return view('Pages.sub_product', [
            'products' => $products,
            'category' => $category->name
        ]);
    }

    /**
     * Display all products grouped by their sub-category name.
     */
    public function showAll()
    {
        $products = Product::with('category')
            ->where('status', 'active')
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'description' => $product->description,
                    'price' => $product->price,
                    'rating' => $product->rating ?? 4.5,
                    'thumbnail' => $product->image,
                    'discountPercentage' => 10,
                    'category' => $product->category ? $product->category->name : 'General'
                ];
            });

        // Group products by their sub-category name
        $grouped = $products->groupBy('category');

        return view('Pages.all-sub-product', compact('grouped'));
    }
}
