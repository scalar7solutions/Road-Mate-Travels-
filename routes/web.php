<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\StorageOptionController;
use App\Http\Controllers\RamOptionController;
use App\Http\Controllers\WarrantyOptionController;
use App\Http\Controllers\ColorOptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeBannerController;

use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\DestinationGroupController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\TourPackageController;
use App\Http\Controllers\RentalPackageController;
use App\Http\Controllers\BookingController;

// ✅ load frontend routes
require __DIR__ . '/frontend.php';

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
})->name('privacypolicy');

// ✅ if anything still hits /dashboard, send it to admin
Route::redirect('/dashboard', '/admin/dashboard');

// ✅ optional: keep old /login URL working (GET only) -> send to admin login
Route::redirect('/login', '/admin/login');

// =======================
// ✅ ADMIN (Backend)
// =======================
Route::prefix('admin')->middleware('web')->group(function () {

    // ✅ Admin login page (guest only)
    Route::get('/login', function () {
        return Inertia::render('auth/Login', [
            'status' => session('status'),
            'canRegister' => Features::enabled(Features::registration()),
            'canResetPassword' => Features::enabled(Features::resetPasswords()),
        ]);
    })->middleware('guest')->name('admin.login');

    // ✅ All admin pages (auth required)
    Route::middleware('auth')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ✅ Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/data', [CategoryController::class, 'data'])->name('categories.data');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // ✅ Brands
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/data', [BrandController::class, 'data'])->name('brands.data');
        Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

        // ✅ Storage Options
        Route::get('/storage-options', [StorageOptionController::class, 'index'])->name('storage.index');
        Route::get('/storage-options/data', [StorageOptionController::class, 'data'])->name('storage.data');
        Route::get('/storage-options/create', [StorageOptionController::class, 'create'])->name('storage.create');
        Route::post('/storage-options', [StorageOptionController::class, 'store'])->name('storage.store');
        Route::get('/storage-options/{storageOption}/edit', [StorageOptionController::class, 'edit'])->name('storage.edit');
        Route::put('/storage-options/{storageOption}', [StorageOptionController::class, 'update'])->name('storage.update');
        Route::delete('/storage-options/{storageOption}', [StorageOptionController::class, 'destroy'])->name('storage.destroy');

        // ✅ RAM Options
        Route::get('/ram-options', [RamOptionController::class, 'index'])->name('ram.index');
        Route::get('/ram-options/data', [RamOptionController::class, 'data'])->name('ram.data');
        Route::get('/ram-options/create', [RamOptionController::class, 'create'])->name('ram.create');
        Route::post('/ram-options', [RamOptionController::class, 'store'])->name('ram.store');
        Route::get('/ram-options/{ramOption}/edit', [RamOptionController::class, 'edit'])->name('ram.edit');
        Route::put('/ram-options/{ramOption}', [RamOptionController::class, 'update'])->name('ram.update');
        Route::delete('/ram-options/{ramOption}', [RamOptionController::class, 'destroy'])->name('ram.destroy');

        // ✅ Warranty Options
        Route::get('/warranty-options', [WarrantyOptionController::class, 'index'])->name('warranty.index');
        Route::get('/warranty-options/data', [WarrantyOptionController::class, 'data'])->name('warranty.data');
        Route::get('/warranty-options/create', [WarrantyOptionController::class, 'create'])->name('warranty.create');
        Route::post('/warranty-options', [WarrantyOptionController::class, 'store'])->name('warranty.store');
        Route::get('/warranty-options/{warrantyOption}/edit', [WarrantyOptionController::class, 'edit'])->name('warranty.edit');
        Route::put('/warranty-options/{warrantyOption}', [WarrantyOptionController::class, 'update'])->name('warranty.update');
        Route::delete('/warranty-options/{warrantyOption}', [WarrantyOptionController::class, 'destroy'])->name('warranty.destroy');

        // ✅ Color Options
        Route::get('/color-options', [ColorOptionController::class, 'index'])->name('colors.index');
        Route::get('/color-options/data', [ColorOptionController::class, 'data'])->name('colors.data');
        Route::get('/color-options/create', [ColorOptionController::class, 'create'])->name('colors.create');
        Route::post('/color-options', [ColorOptionController::class, 'store'])->name('colors.store');
        Route::get('/color-options/{colorOption}/edit', [ColorOptionController::class, 'edit'])->name('colors.edit');
        Route::put('/color-options/{colorOption}', [ColorOptionController::class, 'update'])->name('colors.update');
        Route::delete('/color-options/{colorOption}', [ColorOptionController::class, 'destroy'])->name('colors.destroy');

        // ✅ Products
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // ✅ Other CMS
        Route::prefix('other-cms')->group(function () {
            Route::get('/homebanners', [HomeBannerController::class, 'index'])->name('homebanners.index');
            Route::get('/homebanners/create', [HomeBannerController::class, 'create'])->name('homebanners.create');
            Route::post('/homebanners', [HomeBannerController::class, 'store'])->name('homebanners.store');
            Route::get('/homebanners/{homeBanner}/edit', [HomeBannerController::class, 'edit'])->name('homebanners.edit');
            Route::put('/homebanners/{homeBanner}', [HomeBannerController::class, 'update'])->name('homebanners.update');
            Route::delete('/homebanners/{homeBanner}', [HomeBannerController::class, 'destroy'])->name('homebanners.destroy');

            Route::get('/homebanners/data', [HomeBannerController::class, 'data'])->name('homebanners.data');
        });


// Vehicle Types
Route::get('/vehicle-types', [VehicleTypeController::class, 'index'])->name('vehicletypes.index');
Route::get('/vehicle-types/create', [VehicleTypeController::class, 'create'])->name('vehicletypes.create');
Route::post('/vehicle-types', [VehicleTypeController::class, 'store'])->name('vehicletypes.store');
Route::get('/vehicle-types/{vehicleType}/edit', [VehicleTypeController::class, 'edit'])->name('vehicletypes.edit');
Route::put('/vehicle-types/{vehicleType}', [VehicleTypeController::class, 'update'])->name('vehicletypes.update');
Route::delete('/vehicle-types/{vehicleType}', [VehicleTypeController::class, 'destroy'])->name('vehicletypes.destroy');

// Destination Groups
Route::get('/destination-groups', [DestinationGroupController::class, 'index'])->name('destinationgroups.index');
Route::get('/destination-groups/create', [DestinationGroupController::class, 'create'])->name('destinationgroups.create');
Route::post('/destination-groups', [DestinationGroupController::class, 'store'])->name('destinationgroups.store');
Route::get('/destination-groups/{destinationGroup}/edit', [DestinationGroupController::class, 'edit'])->name('destinationgroups.edit');
Route::put('/destination-groups/{destinationGroup}', [DestinationGroupController::class, 'update'])->name('destinationgroups.update');
Route::delete('/destination-groups/{destinationGroup}', [DestinationGroupController::class, 'destroy'])->name('destinationgroups.destroy');

// Places
Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
Route::get('/places/create', [PlaceController::class, 'create'])->name('places.create');
Route::post('/places', [PlaceController::class, 'store'])->name('places.store');
Route::get('/places/{place}/edit', [PlaceController::class, 'edit'])->name('places.edit');
Route::put('/places/{place}', [PlaceController::class, 'update'])->name('places.update');
Route::delete('/places/{place}', [PlaceController::class, 'destroy'])->name('places.destroy');

// Tour Packages
Route::get('/tour-packages', [TourPackageController::class, 'index'])->name('tourpackages.index');
Route::get('/tour-packages/create', [TourPackageController::class, 'create'])->name('tourpackages.create');
Route::post('/tour-packages', [TourPackageController::class, 'store'])->name('tourpackages.store');
Route::get('/tour-packages/{tourPackage}/edit', [TourPackageController::class, 'edit'])->name('tourpackages.edit');
Route::put('/tour-packages/{tourPackage}', [TourPackageController::class, 'update'])->name('tourpackages.update');
Route::delete('/tour-packages/{tourPackage}', [TourPackageController::class, 'destroy'])->name('tourpackages.destroy');

// Rental Packages
Route::get('/rental-packages', [RentalPackageController::class, 'index'])->name('rentalpackages.index');
Route::get('/rental-packages/create', [RentalPackageController::class, 'create'])->name('rentalpackages.create');
Route::post('/rental-packages', [RentalPackageController::class, 'store'])->name('rentalpackages.store');
Route::get('/rental-packages/{rentalPackage}/edit', [RentalPackageController::class, 'edit'])->name('rentalpackages.edit');
Route::put('/rental-packages/{rentalPackage}', [RentalPackageController::class, 'update'])->name('rentalpackages.update');
Route::delete('/rental-packages/{rentalPackage}', [RentalPackageController::class, 'destroy'])->name('rentalpackages.destroy');

// Bookings (admin)
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
Route::put('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');


    });
});
