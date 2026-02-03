<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\DestinationController;
use App\Http\Controllers\Frontend\TourPackageController;

// Frontend home (public)
Route::get('/', [HomeController::class, 'index'])->name('frontend.root');

// Destinations page
Route::get('/destinations', [DestinationController::class, 'index'])->name('frontend.destinations.index');

// Tour Packages page
Route::get('/packages', [TourPackageController::class, 'index'])->name('frontend.tourpackages.index');

// Optional: keep /home working too
Route::redirect('/home', '/')->name('frontend.home');
