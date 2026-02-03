<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class TourPackageController extends Controller
{
    public function index()
    {
        // For now: pure UI (hardcoded data inside Vue), same as your TSX design.
        // Later you can pass DB packages + filters here.
        return Inertia::render('Frontend/TourPackages/Index', []);
    }
}
