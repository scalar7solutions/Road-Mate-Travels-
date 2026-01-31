<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // default range: last 14 days
        $days = (int) $request->input('days', 14);
        if ($days < 7) $days = 7;
        if ($days > 90) $days = 90;

        $from = Carbon::now()->startOfDay()->subDays($days - 1);
        $to = Carbon::now()->endOfDay();

        $base = Booking::query()->whereBetween('created_at', [$from, $to]);

        // KPIs
        $totalBookings = (clone $base)->count();
        $totalRevenue = (float) (clone $base)->sum('total');
        $pendingCount = (clone $base)->where('status', 'pending')->count();
        $cancelledCount = (clone $base)->where('status', 'cancelled')->count();

        // Top package (by count)
        $topTour = Booking::query()
            ->whereNotNull('tour_package_id')
            ->select('tour_package_id', DB::raw('count(*) as c'))
            ->groupBy('tour_package_id')
            ->orderByDesc('c')
            ->with('tourPackage:id,name')
            ->first();

        $topRental = Booking::query()
            ->whereNotNull('rental_package_id')
            ->select('rental_package_id', DB::raw('count(*) as c'))
            ->groupBy('rental_package_id')
            ->orderByDesc('c')
            ->with('rentalPackage:id,name')
            ->first();

        $topPackageLabel = '—';
        $topPackageCount = 0;

        if ($topTour && (!$topRental || $topTour->c >= $topRental->c)) {
            $topPackageLabel = optional($topTour->tourPackage)->name ?? 'Tour Package';
            $topPackageCount = (int) $topTour->c;
        } elseif ($topRental) {
            $topPackageLabel = optional($topRental->rentalPackage)->name ?? 'Rental Package';
            $topPackageCount = (int) $topRental->c;
        }

        // Charts (Bookings per day)
        $bookingsPerDay = (clone $base)
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->map(fn($r) => ['date' => $r->d, 'value' => (int) $r->c]);

        // Revenue per day
        $revenuePerDay = (clone $base)
            ->selectRaw('DATE(created_at) as d, SUM(total) as s')
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->map(fn($r) => ['date' => $r->d, 'value' => (float) $r->s]);

        // Status breakdown
        $statusBreakdown = (clone $base)
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->get()
            ->map(fn($r) => ['status' => $r->status, 'value' => (int) $r->c]);

        // Top packages (within range)
        $topPackages = Booking::query()
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("
                CASE
                    WHEN booking_type = 'tour' THEN CONCAT('tour:', tour_package_id)
                    ELSE CONCAT('rental:', rental_package_id)
                END as k,
                COUNT(*) as c
            ")
            ->groupBy('k')
            ->orderByDesc('c')
            ->limit(8)
            ->get()
            ->map(function ($r) {
                $key = (string) $r->k;
                $count = (int) $r->c;

                $label = $key;

                if (str_starts_with($key, 'tour:')) {
                    $id = (int) str_replace('tour:', '', $key);
                    $name = optional(\App\Models\TourPackage::find($id))->name;
                    $label = $name ? "Tour: {$name}" : "Tour #{$id}";
                } else {
                    $id = (int) str_replace('rental:', '', $key);
                    $name = optional(\App\Models\RentalPackage::find($id))->name;
                    $label = $name ? "Rental: {$name}" : "Rental #{$id}";
                }

                return ['label' => $label, 'value' => $count];
            });

        // Latest bookings
        $latest = Booking::query()
            ->with(['tourPackage:id,name', 'rentalPackage:id,name'])
            ->orderByDesc('id')
            ->limit(8)
            ->get()
            ->map(function (Booking $b) {
                $product = $b->booking_type === 'tour'
                    ? optional($b->tourPackage)->name
                    : optional($b->rentalPackage)->name;

                return [
                    'id' => $b->id,
                    'booking_ref' => $b->booking_ref,
                    'customer_name' => $b->customer_name,
                    'customer_phone' => $b->customer_phone,
                    'booking_type' => $b->booking_type,
                    'product' => $product ?? '—',
                    'start_date' => optional($b->start_date)->toDateString(),
                    'total' => (float) $b->total,
                    'status' => $b->status,
                    'payment_status' => $b->payment_status,
                ];
            });

        return Inertia::render('Dashboard/Index', [
            'range' => [
                'days' => $days,
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
            ],
            'kpis' => [
                'totalBookings' => $totalBookings,
                'totalRevenue' => round($totalRevenue, 2),
                'pendingCount' => $pendingCount,
                'cancelledCount' => $cancelledCount,
                'topPackageLabel' => $topPackageLabel,
                'topPackageCount' => $topPackageCount,
            ],
            'charts' => [
                'bookingsPerDay' => $bookingsPerDay,
                'revenuePerDay' => $revenuePerDay,
                'statusBreakdown' => $statusBreakdown,
                'topPackages' => $topPackages,
            ],
            'latest' => $latest,
        ]);
    }
}
