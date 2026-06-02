<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeBannerController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::orderBy('sort_order')->latest()->pathPaginate(10, url('admin/home-banners/page'));

        return view('admin.home-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.home-banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:100',
            'subtitle' => 'nullable|string|max:160',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'link_url' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['image'] = $request->file('image')->store('home-banners', 'public');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        HomeBanner::create($data);

        return redirect()->route('admin.home-banners.index')->with('success', 'Banner home berhasil ditambahkan.');
    }

    public function edit(HomeBanner $homeBanner)
    {
        return view('admin.home-banners.edit', compact('homeBanner'));
    }

    public function update(Request $request, HomeBanner $homeBanner)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:100',
            'subtitle' => 'nullable|string|max:160',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'link_url' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($homeBanner->image);
            $data['image'] = $request->file('image')->store('home-banners', 'public');
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $homeBanner->update($data);

        return redirect()->route('admin.home-banners.index')->with('success', 'Banner home berhasil diperbarui.');
    }

    public function destroy(HomeBanner $homeBanner)
    {
        Storage::disk('public')->delete($homeBanner->image);
        $homeBanner->delete();

        return redirect()->route('admin.home-banners.index')->with('success', 'Banner home berhasil dihapus.');
    }
}
