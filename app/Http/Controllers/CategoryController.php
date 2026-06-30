<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::with('mainCategory');

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $categories = $query->latest()->paginate(10)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return redirect('/admin/categories/' . $category->id . '/edit');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all main categories to choose as parent
        $parentCategories = MainCategory::orderBy('name')->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ec_categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'required|exists:ec_main_categories,id',
            'status' => 'required|in:active,inactive',
            'image_file' => 'nullable|image|max:2048',
            'image' => 'nullable|string|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'status']);
        $data['main_category_id'] = $request->input('parent_id');
        
        // Generate unique slug
        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $data['slug'] = $slug;

        // Handle Image Upload or URL
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('categories', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image')) {
            $data['image'] = $request->input('image');
        }

        Category::create($data);

        return redirect('/admin/categories')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // Get all main categories
        $parentCategories = MainCategory::orderBy('name')->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ec_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'required|exists:ec_main_categories,id',
            'status' => 'required|in:active,inactive',
            'image_file' => 'nullable|image|max:2048',
            'image' => 'nullable|string|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'status']);
        $data['main_category_id'] = $request->input('parent_id');

        // Generate unique slug
        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name);
        if ($slug !== $category->slug) {
            $originalSlug = $slug;
            $count = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $data['slug'] = $slug;
        }

        // Handle Image Upload or URL
        if ($request->hasFile('image_file')) {
            // Delete old stored file if exists
            if ($category->image && str_starts_with($category->image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $category->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image_file')->store('categories', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image')) {
            $data['image'] = $request->input('image');
        }

        $category->update($data);

        return redirect('/admin/categories')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Prevent deletion if category has sub-categories
        if ($category->children()->exists()) {
            return redirect('/admin/categories')->with('error', 'Cannot delete category: It has active sub-categories. Please delete or reassign sub-categories first.');
        }

        // Delete stored image file if exists
        if ($category->image && str_starts_with($category->image, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $category->image);
            Storage::disk('public')->delete($oldPath);
        }

        $category->delete();

        return redirect('/admin/categories')->with('success', 'Category deleted successfully.');
    }
}
