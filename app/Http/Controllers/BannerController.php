<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status'   => 'required|in:active,inactive',
        ]);

        $data = $request->only([
            'tag', 'title', 'subtitle', 'description',
            'btn_primary_label', 'btn_primary_url',
            'btn_secondary_label', 'btn_secondary_url',
            'bg_gradient', 'sort_order', 'status',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);
        return redirect('/admin/banners')->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only([
            'tag', 'title', 'subtitle', 'description',
            'btn_primary_label', 'btn_primary_url',
            'btn_secondary_label', 'btn_secondary_url',
            'bg_gradient', 'sort_order', 'status',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image) Storage::disk('public')->delete($banner->image);
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);
        return redirect('/admin/banners')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) Storage::disk('public')->delete($banner->image);
        $banner->delete();
        return redirect('/admin/banners')->with('success', 'Banner deleted.');
    }
}
