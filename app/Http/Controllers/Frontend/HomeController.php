<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\HomeBanner;
use App\Models\DestinationGroup;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $activeCategory = request('category');

        $banners = HomeBanner::query()
            ->latest()
            ->get()
            ->map(function (HomeBanner $b) {
                return [
                    'id' => $b->id,
                    'name' => $b->name,
                    'description' => $b->description,
                    'video_url' => $b->video_url,
                ];
            })
            ->values();

        $products = Product::query()
            ->latest()
            ->paginate(12)
            ->through(function ($product) {
                $image = $product->main_image_url
                    ?? ($product->main_image_path ? asset('storage/' . $product->main_image_path) : null);

                return [
                    'id' => $product->id,
                    'name' => $product->model ?? '',
                    'price' => (float) ($product->price_lkr ?? 0),
                    'image' => $image,
                    'created_at' => optional($product->created_at)->toIso8601String(),
                ];
            });

        // ✅ Destination groups for ExploreSriLanka.vue
        $destinationGroups = DestinationGroup::query()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->withCount([
                'places as places_count' => function ($q) {
                    $q->where('status', 'active');
                },
            ])
            ->get()
            ->map(function ($g) {
                return [
                    'id' => $g->id,
                    'slug' => $g->slug,
                    'name' => $g->name,
                    // if you don’t have description column yet, it will just be null
                    'description' => $g->description ?? null,
                    'coverImage' => $g->cover_image_path ? Storage::url($g->cover_image_path) : null,
                    'placesCount' => (int) ($g->places_count ?? 0),
                ];
            })
            ->values();

        return Inertia::render('Frontend/Home/Index', [
            'products' => $products,
            'activeCategory' => $activeCategory,
            'banners' => $banners,
            'destinationGroups' => $destinationGroups,
        ]);
    }
}
