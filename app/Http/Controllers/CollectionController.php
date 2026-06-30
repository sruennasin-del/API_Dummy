<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Collection::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $collections = $query->latest()->paginate(10);
        return view('admin.collections.index', compact('collections'));
    }

    public function create()
    {
        return view('admin.collections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ec_collections,slug',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only(['name', 'description', 'status']);
        $data['slug'] = $request->filled('slug') ? \Illuminate\Support\Str::slug($request->slug) : \Illuminate\Support\Str::slug($request->name);

        \App\Models\Collection::create($data);

        return redirect('/admin/collections')->with('success', 'Collection created successfully.');
    }

    public function edit(\App\Models\Collection $collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    public function update(Request $request, \App\Models\Collection $collection)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ec_collections,slug,' . $collection->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only(['name', 'description', 'status']);
        $data['slug'] = $request->filled('slug') ? \Illuminate\Support\Str::slug($request->slug) : \Illuminate\Support\Str::slug($request->name);

        $collection->update($data);

        return redirect('/admin/collections')->with('success', 'Collection updated successfully.');
    }

    public function destroy(\App\Models\Collection $collection)
    {
        $collection->delete();
        return redirect('/admin/collections')->with('success', 'Collection deleted successfully.');
    }
}
