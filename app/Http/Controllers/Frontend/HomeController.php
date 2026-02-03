<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\HomeBanner;
use App\Models\DestinationGroup;
use App\Models\TourPackage;
use App\Models\RentalPackage;
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
                    'description' => $g->description ?? null,
                    'coverImage' => $g->cover_image_path ? Storage::url($g->cover_image_path) : null,
                    'placesCount' => (int) ($g->places_count ?? 0),
                ];
            })
            ->values();

        // ✅ TODAY FILTER for Tour Packages
        $today = now()->toDateString();

        $tourPackages = TourPackage::query()
            ->where('status', 'published')
            ->whereHas('availabilityRanges', function ($q) use ($today) {
                $q->whereDate('start_date', '<=', $today)
                  ->whereDate('end_date', '>=', $today);
            })
            ->with([
                'destinationGroup:id,name',
                'places:id,name',
                'availabilityRanges:id,tour_package_id,start_date,end_date',
                'priceOptions:id,tour_package_id,title,mode,values,status',
            ])
            ->orderByDesc('id')
            ->limit(6)
            ->get()
            ->map(function (TourPackage $p) {
                $placeName = optional($p->places->first())->name;

                $minPerPerson = $this->lowestPerPersonPrice($p);
                $imageUrl = $p->main_image_path ? Storage::url($p->main_image_path) : null;

                return [
                    'id' => $p->id,
                    'slug' => $p->slug,
                    'name' => $p->name,
                    'destinationGroupName' => optional($p->destinationGroup)->name ?? '',
                    'placeName' => $placeName ?? '',
                    'days' => (int) $p->days,
                    'maxPassengers' => (int) $p->passenger_max,
                    'coverImage' => $imageUrl,
                    'fromPriceLkr' => $minPerPerson,
                ];
            })
            ->values();

        // ✅ Rental packages for VehicleRental.vue (published only)
        $rentalPackages = RentalPackage::query()
            ->where('status', 'published')
            ->with([
                'vehicleType:id,name,passenger_max',
                'prices:id,rental_package_id,service_type,pricing_mode,values,status',
            ])
            ->orderByDesc('id')
            ->limit(4)
            ->get()
            ->map(function (RentalPackage $p) {
                $minPerDay = $this->lowestPerDayPrice($p);
                $imageUrl = $p->main_image_path ? Storage::url($p->main_image_path) : null;

                return [
                    'id' => $p->id,
                    'slug' => $p->slug,
                    'name' => $p->name,
                    'vehicleTypeName' => optional($p->vehicleType)->name ?? '',
                    'passengerMax' => (int) ($p->passenger_max ?? (optional($p->vehicleType)->passenger_max ?? 0)),
                    'coverImage' => $imageUrl,
                    'fromPricePerDayLkr' => $minPerDay,
                ];
            })
            ->values();

        return Inertia::render('Frontend/Home/Index', [
            'products' => $products,
            'activeCategory' => $activeCategory,
            'banners' => $banners,
            'destinationGroups' => $destinationGroups,
            'tourPackages' => $tourPackages,
            'rentalPackages' => $rentalPackages,
        ]);
    }

    // ---------------------------
    // Tour package pricing helper
    // ---------------------------
    private function lowestPerPersonPrice(TourPackage $pkg): float
    {
        $perPersonMins = [];
        $otherMins = [];

        foreach ($pkg->priceOptions ?? [] as $opt) {
            if (($opt->status ?? 'active') !== 'active') continue;

            $nums = $this->extractNumericValues($opt->values ?? []);
            $nums = array_filter($nums, fn($n) => is_numeric($n) && $n > 0);

            if (empty($nums)) continue;

            $min = (float) min($nums);

            if (($opt->mode ?? '') === 'per_person') {
                $perPersonMins[] = $min;
            } else {
                $otherMins[] = $min;
            }
        }

        if (!empty($perPersonMins)) return (float) min($perPersonMins);
        if (!empty($otherMins)) return (float) min($otherMins);

        return 0.0;
    }

    // ---------------------------
    // Rental package pricing helper
    // ---------------------------
    private function lowestPerDayPrice(RentalPackage $pkg): float
    {
        $perDayMins = [];
        $otherMins = [];

        foreach ($pkg->prices ?? [] as $price) {
            if (($price->status ?? 'active') !== 'active') continue;

            $nums = $this->extractNumericValues($price->values ?? []);
            $nums = array_filter($nums, fn($n) => is_numeric($n) && $n > 0);

            if (empty($nums)) continue;

            $min = (float) min($nums);

            if (($price->pricing_mode ?? '') === 'per_day') {
                $perDayMins[] = $min;
            } else {
                $otherMins[] = $min;
            }
        }

        if (!empty($perDayMins)) return (float) min($perDayMins);
        if (!empty($otherMins)) return (float) min($otherMins);

        return 0.0;
    }

    /**
     * Recursively extract numeric values from arrays/objects.
     * Works for arrays like:
     * - [15000, 20000]
     * - [{price:15000},{price:20000}]
     * - {tiers:[{amount:15000}]} etc.
     */
    private function extractNumericValues($value): array
    {
        $out = [];

        if (is_numeric($value)) {
            $out[] = (float) $value;
            return $out;
        }

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                if (is_string($k) && preg_match('/(price|amount|value|lkr|rate|cost)/i', $k) && is_numeric($v)) {
                    $out[] = (float) $v;
                    continue;
                }
                $out = array_merge($out, $this->extractNumericValues($v));
            }
            return $out;
        }

        if (is_object($value)) {
            foreach (get_object_vars($value) as $k => $v) {
                if (preg_match('/(price|amount|value|lkr|rate|cost)/i', $k) && is_numeric($v)) {
                    $out[] = (float) $v;
                    continue;
                }
                $out = array_merge($out, $this->extractNumericValues($v));
            }
            return $out;
        }

        return $out;
    }
}
