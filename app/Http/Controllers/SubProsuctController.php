<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class SubProsuctController extends Controller
{
    //
    public function show($id)
        {
          $response = Http::get('https://dummyjson.com/products', [
                'limit' => 195,
                'skip'  => 0
            ]);

        $products = collect($response->json()['products']);

        // 👇 FIX: map your menu ID to real API categories
        $map = [
            1  => 'beauty',
            2  => 'furniture',
            3  => 'groceries',
            4  => 'kitchen-accessories',
            5  => 'sports-accessories',
            6  => 'mens-shoes',
            7  => 'mens-watches',
            8  => 'mobile-accessories',
            9  => 'motorcycle',
            10 => 'smartphones',
        ];

        $category = $map[$id] ?? null;

        if (!$category) {
            abort(404);
        }

        $filtered = $products->where('category', $category)->values();

        return view('Pages.sub_product', [
            'products' => $filtered,
            'category' => $category
        ]);
    }
   public function showAll()
    {
        $response = Http::get('https://dummyjson.com/products', [
            'limit' => 195,
            'skip'  => 0
        ]);

        $products = $response->json()['products'];

        // group by category
        $grouped = collect($products)->groupBy('category');

        return view('Pages.all-sub-product', compact('grouped'));
    }
}
