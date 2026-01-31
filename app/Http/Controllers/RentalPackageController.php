<?php

namespace App\Http\Controllers;

use App\Models\RentalPackage;
use App\Models\RentalPackagePrice;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class RentalPackageController extends Controller
{
  public function index(Request $request)
  {
    $q = $request->string('q')->toString();

    $items = RentalPackage::query()
      ->with('vehicleType:id,name')
      ->when($q, fn($qr) => $qr->where('name','like',"%{$q}%"))
      ->orderByDesc('id')
      ->paginate(10)
      ->withQueryString();

    return Inertia::render('RentalPackages/Index', [
      'items' => $items,
      'filters' => ['q' => $q],
    ]);
  }

  public function create()
  {
    return Inertia::render('RentalPackages/CreateUpdate', [
      'mode' => 'create',
      'pkg' => null,
      'vehicleTypes' => VehicleType::orderBy('name')->get(['id','name','passenger_min','passenger_max','status']),
    ]);
  }

  public function edit(RentalPackage $rentalPackage)
  {
    $rentalPackage->load('prices:id,rental_package_id,service_type,pricing_mode,values,status');

    return Inertia::render('RentalPackages/CreateUpdate', [
      'mode' => 'edit',
      'pkg' => [
        ...$rentalPackage->only([
          'id','name','vehicle_type_id','passenger_max','status',
          'short_description','description','main_image_path','gallery_images'
        ]),
        'prices' => $rentalPackage->prices->map(fn($p) => [
          'service_type' => $p->service_type,
          'pricing_mode' => $p->pricing_mode,
          'values' => $p->values,
          'status' => $p->status,
        ])->values(),
      ],
      'vehicleTypes' => VehicleType::orderBy('name')->get(['id','name','passenger_min','passenger_max','status']),
    ]);
  }

  public function store(Request $request)
  {
    $validated = $this->validatePayload($request);

    return DB::transaction(function () use ($request, $validated) {
      $slug = $this->uniqueSlug($validated['name']);

      $mainImagePath = null;
      if ($request->hasFile('main_image')) {
        $mainImagePath = $request->file('main_image')->store('rental-packages', 'public');
      }

      $gallery = [];
      if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $file) {
          $gallery[] = $file->store('rental-packages/gallery', 'public');
        }
      }

      $pkg = RentalPackage::create([
        'name' => $validated['name'],
        'slug' => $slug,
        'vehicle_type_id' => $validated['vehicle_type_id'],
        'passenger_max' => $validated['passenger_max'],
        'status' => $validated['status'],
        'short_description' => $validated['short_description'] ?? null,
        'description' => $validated['description'] ?? null,
        'main_image_path' => $mainImagePath,
        'gallery_images' => $gallery ?: null,
      ]);

      foreach ($validated['prices'] as $p) {
        RentalPackagePrice::create([
          'rental_package_id' => $pkg->id,
          'service_type' => $p['service_type'],
          'pricing_mode' => $p['pricing_mode'],
          'values' => $p['values'],
          'status' => $p['status'] ?? 'active',
        ]);
      }

      return redirect()->route('rentalpackages.index')->with('success','Rental package created.');
    });
  }

  public function update(Request $request, RentalPackage $rentalPackage)
  {
    $validated = $this->validatePayload($request);

    return DB::transaction(function () use ($request, $validated, $rentalPackage) {
      $update = [
        'name' => $validated['name'],
        'vehicle_type_id' => $validated['vehicle_type_id'],
        'passenger_max' => $validated['passenger_max'],
        'status' => $validated['status'],
        'short_description' => $validated['short_description'] ?? null,
        'description' => $validated['description'] ?? null,
      ];

      if ($request->hasFile('main_image')) {
        if ($rentalPackage->main_image_path) Storage::disk('public')->delete($rentalPackage->main_image_path);
        $update['main_image_path'] = $request->file('main_image')->store('rental-packages', 'public');
      }

      $existingGallery = is_array($rentalPackage->gallery_images) ? $rentalPackage->gallery_images : [];
      $gallery = $existingGallery;
      if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $file) {
          $gallery[] = $file->store('rental-packages/gallery', 'public');
        }
        $update['gallery_images'] = $gallery;
      }

      $rentalPackage->update($update);

      RentalPackagePrice::where('rental_package_id', $rentalPackage->id)->delete();
      foreach ($validated['prices'] as $p) {
        RentalPackagePrice::create([
          'rental_package_id' => $rentalPackage->id,
          'service_type' => $p['service_type'],
          'pricing_mode' => $p['pricing_mode'],
          'values' => $p['values'],
          'status' => $p['status'] ?? 'active',
        ]);
      }

      return redirect()->route('rentalpackages.index')->with('success','Rental package updated.');
    });
  }

  public function destroy(RentalPackage $rentalPackage)
  {
    if ($rentalPackage->main_image_path) Storage::disk('public')->delete($rentalPackage->main_image_path);
    if (is_array($rentalPackage->gallery_images)) {
      foreach ($rentalPackage->gallery_images as $p) Storage::disk('public')->delete($p);
    }
    $rentalPackage->delete();

    return redirect()->route('rentalpackages.index')->with('success','Rental package deleted.');
  }

  private function validatePayload(Request $request): array
  {
    return $request->validate([
      'name' => ['required','string','max:255'],
      'vehicle_type_id' => ['required','exists:vehicle_types,id'],
      'passenger_max' => ['required','integer','min:1','max:1000'],
      'status' => ['required','in:draft,published,hidden'],
      'short_description' => ['nullable','string','max:1000'],
      'description' => ['nullable','string'],

      'prices' => ['required','array','min:1'],
      'prices.*.service_type' => ['required','in:self_drive,with_driver,all_inclusive'],
      'prices.*.pricing_mode' => ['required','in:fixed_total,per_day'],
      'prices.*.values' => ['required','array'],
      'prices.*.status' => ['nullable','in:active,inactive'],

      'main_image' => ['nullable','image','max:4096'],
      'gallery_images' => ['nullable','array'],
      'gallery_images.*' => ['image','max:4096'],
    ]);
  }

  private function uniqueSlug(string $name): string
  {
    $base = Str::slug($name);
    $slug = $base;
    $i = 1;

    while (RentalPackage::where('slug',$slug)->exists()) {
      $slug = $base.'-'.$i;
      $i++;
    }

    return $slug;
  }
}
