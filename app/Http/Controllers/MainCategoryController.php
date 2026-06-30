<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MainCategory::withCount('categories');

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

        return view('admin.main_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.main_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ec_main_categories,slug',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'layout_type' => 'required|in:portrait,landscape',
            'image_file' => 'nullable|image|max:2048',
            'image' => 'nullable|string|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'status', 'layout_type']);
        
        // Generate unique slug
        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (MainCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $data['slug'] = $slug;

        // Handle Image Upload or URL
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('main_categories', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image')) {
            $data['image'] = $request->input('image');
        }

        MainCategory::create($data);

        return redirect('/admin/main-categories')->with('success', 'Main Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MainCategory $mainCategory)
    {
        // the route param might be main_category, making variable $mainCategory
        return view('admin.main_categories.edit', ['category' => $mainCategory]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MainCategory $mainCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ec_main_categories,slug,' . $mainCategory->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'layout_type' => 'required|in:portrait,landscape',
            'image_file' => 'nullable|image|max:2048',
            'image' => 'nullable|string|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'status', 'layout_type']);

        // Generate unique slug
        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name);
        if ($slug !== $mainCategory->slug) {
            $originalSlug = $slug;
            $count = 1;
            while (MainCategory::where('slug', $slug)->where('id', '!=', $mainCategory->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $data['slug'] = $slug;
        }

        // Handle Image Upload or URL
        if ($request->hasFile('image_file')) {
            // Delete old stored file if exists
            if ($mainCategory->image && str_starts_with($mainCategory->image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $mainCategory->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image_file')->store('main_categories', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image')) {
            $data['image'] = $request->input('image');
        }

        $mainCategory->update($data);

        return redirect('/admin/main-categories')->with('success', 'Main Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MainCategory $mainCategory)
    {
        // Prevent deletion if category has sub-categories
        if ($mainCategory->categories()->exists()) {
            return redirect('/admin/main-categories')->with('error', 'Cannot delete main category: It has active sub-categories. Please delete or reassign sub-categories first.');
        }

        // Delete stored image file if exists
        if ($mainCategory->image && str_starts_with($mainCategory->image, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $mainCategory->image);
            Storage::disk('public')->delete($oldPath);
        }

        $mainCategory->delete();

        return redirect('/admin/main-categories')->with('success', 'Main Category deleted successfully.');
    }
}
