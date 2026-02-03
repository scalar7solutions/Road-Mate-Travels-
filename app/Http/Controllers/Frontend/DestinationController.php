<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DestinationController extends Controller
{
    public function index()
    {
        // For now it’s hardcoded UI (Banner + cards + map) inside the Vue page.
        // Later you can pass destination groups from DB here.
        return Inertia::render('Frontend/Destinations/Index', []);
    }
}
