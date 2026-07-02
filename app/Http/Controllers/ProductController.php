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
            'variants' => 'nullable|array',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sizes' => 'nullable|array',
            'variants.*.sizes.*' => 'exists:ec_sizes,id',
            'variants.*.detail_image_file_0' => 'nullable|image|max:2048',
            'variants.*.detail_image_file_1' => 'nullable|image|max:2048',
            'variants.*.detail_image_file_2' => 'nullable|image|max:2048',
            'variants.*.detail_image_0' => 'nullable|string|max:2048',
            'variants.*.detail_image_1' => 'nullable|string|max:2048',
            'variants.*.detail_image_2' => 'nullable|string|max:2048',
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

        // Sync collections
        $product->collections()->sync($request->input('collections', []));

        $variantsData = $request->input('variants', []);

        // Fallback: If no colors selected/configured, assign to first color to hold sizes/images
        if (empty($variantsData) && \App\Models\Color::exists()) {
            $firstColorId = \App\Models\Color::first()->id;
            $variantsData[$firstColorId] = [
                'price' => null,
                'sizes' => []
            ];
        }

        // Create the product color variants and sync their sizes/images
        foreach ($variantsData as $colorId => $variantInfo) {
            $variant = \App\Models\ProductColor::create([
                'product_id' => $product->id,
                'color_id' => $colorId,
                'price' => !empty($variantInfo['price']) ? $variantInfo['price'] : null,
            ]);

            // Sync sizes for this variant
            $sizeIds = $variantInfo['sizes'] ?? [];
            $variant->sizes()->sync($sizeIds);

            // Handle 3 detail images for this variant
            for ($i = 0; $i < 3; $i++) {
                $imagePath = null;
                if ($request->hasFile("variants.{$colorId}.detail_image_file_{$i}")) {
                    $path = $request->file("variants.{$colorId}.detail_image_file_{$i}")->store('products/details', 'public');
                    $imagePath = '/storage/' . $path;
                } elseif (!empty($variantInfo["detail_image_{$i}"])) {
                    $imagePath = $variantInfo["detail_image_{$i}"];
                }

                if ($imagePath) {
                    \App\Models\ProductImage::create([
                        'product_color_id' => $variant->id,
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
            'variants' => 'nullable|array',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sizes' => 'nullable|array',
            'variants.*.sizes.*' => 'exists:ec_sizes,id',
            'variants.*.detail_image_file_0' => 'nullable|image|max:2048',
            'variants.*.detail_image_file_1' => 'nullable|image|max:2048',
            'variants.*.detail_image_file_2' => 'nullable|image|max:2048',
            'variants.*.detail_image_0' => 'nullable|string|max:2048',
            'variants.*.detail_image_1' => 'nullable|string|max:2048',
            'variants.*.detail_image_2' => 'nullable|string|max:2048',
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

        // Sync collections
        $product->collections()->sync($request->input('collections', []));

        $variantsData = $request->input('variants', []);

        // Fallback: If no colors selected, assign to first color to hold sizes/images
        if (empty($variantsData) && \App\Models\Color::exists()) {
            $firstColorId = \App\Models\Color::first()->id;
            $variantsData[$firstColorId] = [
                'price' => null,
                'sizes' => []
            ];
        }

        $currentVariantIds = [];

        foreach ($variantsData as $colorId => $variantInfo) {
            // Find or create variant
            $variant = \App\Models\ProductColor::updateOrCreate(
                ['product_id' => $product->id, 'color_id' => $colorId],
                ['price' => !empty($variantInfo['price']) ? $variantInfo['price'] : null]
            );
            $currentVariantIds[] = $variant->id;

            // Sync sizes for this variant
            $sizeIds = $variantInfo['sizes'] ?? [];
            $variant->sizes()->sync($sizeIds);

            // Handle 3 detail images for this variant
            $existingImages = $variant->images()->orderBy('id')->get();

            for ($i = 0; $i < 3; $i++) {
                $existingImage = $existingImages->get($i);
                
                // Check if delete was requested
                $shouldDelete = isset($variantInfo["delete_detail_{$i}"]) && $variantInfo["delete_detail_{$i}"] == '1';

                $newImagePath = null;
                if ($request->hasFile("variants.{$colorId}.detail_image_file_{$i}")) {
                    $path = $request->file("variants.{$colorId}.detail_image_file_{$i}")->store('products/details', 'public');
                    $newImagePath = '/storage/' . $path;
                } elseif (isset($variantInfo["detail_image_{$i}"])) {
                    $inputUrl = $variantInfo["detail_image_{$i}"];
                    // Only update if different or empty (if emptied and not deleted, we'll clear it)
                    if (empty($inputUrl)) {
                        $shouldDelete = true;
                    } elseif (!$existingImage || $existingImage->image_path !== $inputUrl) {
                        $newImagePath = $inputUrl;
                    }
                }

                if ($shouldDelete) {
                    if ($existingImage) {
                        if (str_starts_with($existingImage->image_path, '/storage/')) {
                            Storage::disk('public')->delete(str_replace('/storage/', '', $existingImage->image_path));
                        }
                        $existingImage->delete();
                    }
                } elseif ($newImagePath) {
                    if ($existingImage) {
                        // Delete old file if local
                        if (str_starts_with($existingImage->image_path, '/storage/')) {
                            Storage::disk('public')->delete(str_replace('/storage/', '', $existingImage->image_path));
                        }
                        $existingImage->update(['image_path' => $newImagePath]);
                    } else {
                        \App\Models\ProductImage::create([
                            'product_color_id' => $variant->id,
                            'image_path' => $newImagePath,
                        ]);
                    }
                }
            }
        }

        // Delete variants that were removed
        $deletedVariants = \App\Models\ProductColor::where('product_id', $product->id)
            ->whereNotIn('id', $currentVariantIds)
            ->get();

        foreach ($deletedVariants as $deletedVariant) {
            foreach ($deletedVariant->images as $delImage) {
                if (str_starts_with($delImage->image_path, '/storage/')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $delImage->image_path));
                }
            }
            $deletedVariant->sizes()->detach();
            $deletedVariant->images()->delete();
            $deletedVariant->delete();
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
