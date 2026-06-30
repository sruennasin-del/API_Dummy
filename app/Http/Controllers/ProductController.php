<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Handle category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Handle status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'In Stock') {
                $query->where('stock', '>', 5);
            } elseif ($status === 'Low Stock') {
                $query->whereBetween('stock', [1, 5]);
            } elseif ($status === 'Out of Stock') {
                $query->where('stock', 0);
            } else {
                $query->where('status', $status);
            }
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::whereNotNull('main_category_id')->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mainCategories = \App\Models\MainCategory::orderBy('name')->get();
        $categories = Category::with('mainCategory')->orderBy('name')->get();
        $colors = Color::where('status', 'active')->orderBy('name')->get();
        $sizes = Size::where('status', 'active')->orderBy('name')->get();
        $collections = \App\Models\Collection::where('status', 'active')->orderBy('name')->get();

        return view('admin.products.create', compact('mainCategories', 'categories', 'colors', 'sizes', 'collections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ec_products,slug',
            'category_id' => 'required|exists:ec_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|max:2048',
            'image' => 'nullable|string|max:2048',
            'status' => 'required|in:active,inactive',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:ec_colors,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:ec_sizes,id',
            'detail_image_file_0' => 'nullable|image|max:2048',
            'detail_image_file_1' => 'nullable|image|max:2048',
            'detail_image_file_2' => 'nullable|image|max:2048',
            'detail_image_0' => 'nullable|string|max:2048',
            'detail_image_1' => 'nullable|string|max:2048',
            'detail_image_2' => 'nullable|string|max:2048',
        ]);

        $data = $request->only(['title', 'category_id', 'price', 'stock', 'description', 'status']);

        // Generate unique slug
        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $data['slug'] = $slug;

        // Default rating for new products
        $data['rating'] = 5.00;

        // Handle Image Upload or URL
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image')) {
            $data['image'] = $request->input('image');
        }

        $product = Product::create($data);

        // Sync colors & collections
        $product->colors()->sync($request->input('colors', []));
        $product->collections()->sync($request->input('collections', []));

        // Fallback: If no colors selected, assign to first color to hold sizes/images
        $colorVariants = $product->colorVariants()->get();
        if ($colorVariants->isEmpty() && \App\Models\Color::exists()) {
            $product->colors()->sync([\App\Models\Color::first()->id]);
            $colorVariants = $product->colorVariants()->get();
        }

        // Sync sizes to all variants
        $sizeIds = $request->input('sizes', []);
        foreach ($colorVariants as $variant) {
            $variant->sizes()->sync($sizeIds);
        }

        // Handle 3 detail images (attach to the first active variant)
        $firstVariant = $colorVariants->first();
        if ($firstVariant) {
            for ($i = 0; $i < 3; $i++) {
                $imagePath = null;
                if ($request->hasFile("detail_image_file_{$i}")) {
                    $path = $request->file("detail_image_file_{$i}")->store('products/details', 'public');
                    $imagePath = '/storage/' . $path;
                } elseif ($request->filled("detail_image_{$i}")) {
                    $imagePath = $request->input("detail_image_{$i}");
                }

                if ($imagePath) {
                    \App\Models\ProductImage::create([
                        'product_color_id' => $firstVariant->id,
                        'image_path' => $imagePath,
                    ]);
                }
            }
        }

        return redirect('/admin/products')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return redirect('/admin/products/' . $product->id . '/edit');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['colors', 'collections', 'colorVariants.sizes', 'colorVariants.images']);
        $mainCategories = \App\Models\MainCategory::orderBy('name')->get();
        $categories = Category::with('mainCategory')->orderBy('name')->get();
        $colors = Color::where('status', 'active')->orderBy('name')->get();
        $sizes = Size::where('status', 'active')->orderBy('name')->get();
        $collections = \App\Models\Collection::where('status', 'active')->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'mainCategories', 'categories', 'colors', 'sizes', 'collections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ec_products,slug,' . $product->id,
            'category_id' => 'required|exists:ec_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|max:2048',
            'image' => 'nullable|string|max:2048',
            'status' => 'required|in:active,inactive',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:ec_colors,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:ec_sizes,id',
            'detail_image_file_0' => 'nullable|image|max:2048',
            'detail_image_file_1' => 'nullable|image|max:2048',
            'detail_image_file_2' => 'nullable|image|max:2048',
            'detail_image_0' => 'nullable|string|max:2048',
            'detail_image_1' => 'nullable|string|max:2048',
            'detail_image_2' => 'nullable|string|max:2048',
        ]);

        $data = $request->only(['title', 'category_id', 'price', 'stock', 'description', 'status']);

        // Generate unique slug
        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);
        if ($slug !== $product->slug) {
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $data['slug'] = $slug;
        }

        // Handle Image Upload or URL
        if ($request->hasFile('image_file')) {
            // Delete old stored file if exists
            if ($product->image && str_starts_with($product->image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image_file')->store('products', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image')) {
            $data['image'] = $request->input('image');
        }

        $product->update($data);

        // Sync colors & collections
        $product->colors()->sync($request->input('colors', []));
        $product->collections()->sync($request->input('collections', []));

        // Ensure at least one color variant exists to hold sizes/images
        $colorVariants = $product->colorVariants()->get();
        if ($colorVariants->isEmpty() && \App\Models\Color::exists()) {
            $product->colors()->sync([\App\Models\Color::first()->id]);
            $colorVariants = $product->colorVariants()->get();
        }

        // Sync sizes across all variants
        $sizeIds = $request->input('sizes', []);
        foreach ($colorVariants as $variant) {
            $variant->sizes()->sync($sizeIds);
        }

        // Handle 3 detail images update
        $existingImages = $product->images; // This works because of our new getImagesAttribute
        $firstVariant = $colorVariants->first();
        
        for ($i = 0; $i < 3; $i++) {
            $imagePath = null;
            $hasNewInput = false;

            if ($request->hasFile("detail_image_file_{$i}")) {
                $path = $request->file("detail_image_file_{$i}")->store('products/details', 'public');
                $imagePath = '/storage/' . $path;
                $hasNewInput = true;
            } elseif ($request->filled("detail_image_{$i}")) {
                $imagePath = $request->input("detail_image_{$i}");
                
                // Only consider it new input if the URL actually changed!
                $existingPath = $existingImage ? $existingImage->image_path : null;
                if ($imagePath !== $existingPath) {
                    $hasNewInput = true;
                }
            }

            $existingImage = $existingImages->get($i);

            if ($hasNewInput) {
                if ($existingImage) {
                    if (str_starts_with($existingImage->image_path, '/storage/')) {
                        $oldPath = str_replace('/storage/', '', $existingImage->image_path);
                        Storage::disk('public')->delete($oldPath);
                    }
                    $existingImage->update(['image_path' => $imagePath]);
                } elseif ($firstVariant) {
                    \App\Models\ProductImage::create([
                        'product_color_id' => $firstVariant->id,
                        'image_path' => $imagePath,
                    ]);
                }
            } else {
                if ($request->boolean("delete_detail_{$i}")) {
                    if ($existingImage) {
                        if (str_starts_with($existingImage->image_path, '/storage/')) {
                            $oldPath = str_replace('/storage/', '', $existingImage->image_path);
                            Storage::disk('public')->delete($oldPath);
                        }
                        $existingImage->delete();
                    }
                }
            }
        }

        return redirect('/admin/products')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete stored main image file if exists
        if ($product->image && str_starts_with($product->image, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $product->image);
            Storage::disk('public')->delete($oldPath);
        }

        // Delete stored detail images from disk
        foreach ($product->images as $detailImage) {
            if (str_starts_with($detailImage->image_path, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $detailImage->image_path);
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Manually clean up associated variants to avoid foreign key constraints
        foreach ($product->colorVariants as $variant) {
            $variant->sizes()->detach();
            $variant->images()->delete();
        }

        // Safely detach all many-to-many relationships
        $product->categories()->detach();
        $product->collections()->detach();
        $product->colors()->detach();

        $product->delete();

        return redirect('/admin/products')->with('success', 'Product deleted successfully.');
    }
}
