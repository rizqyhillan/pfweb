<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;

class HomeBannerController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->latest()
            ->get()
            ->map(fn (HomeBanner $banner) => [
                'id' => $banner->id,
                'title' => $banner->title,
                'subtitle' => $banner->subtitle,
                'image' => $banner->image,
                'image_url' => $banner->image_url,
                'link_url' => $banner->link_url,
                'sort_order' => $banner->sort_order,
            ]);

        return response()->json([
            'data' => $banners,
        ]);
    }
}
