<?php
namespace App\Http\Controllers;

use App\Models\DestinationGroup;
use App\Models\Place;
use App\Models\TourPackage;
use App\Models\TourPackageAvailabilityRange;
use App\Models\TourPackagePriceOption;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TourPackageController extends Controller
{
  public function index(Request $request)
  {
    $q = $request->string('q')->toString();

    $items = TourPackage::query()
      ->with('destinationGroup:id,name')
      ->when($q, fn($qr) => $qr->where('name','like',"%{$q}%"))
      ->orderByDesc('id')
      ->paginate(10)
      ->withQueryString();

    return Inertia::render('TourPackages/Index', [
      'items' => $items,
      'filters' => ['q' => $q],
    ]);
  }

  public function create()
  {
    return Inertia::render('TourPackages/CreateUpdate', [
      'mode' => 'create',
      'pkg' => null,
      'destinationGroups' => DestinationGroup::orderBy('sort_order')->orderBy('name')->get(['id','name']),
      'places' => Place::orderBy('name')->get(['id','destination_group_id','name']),
      'vehicleTypes' => VehicleType::orderBy('name')->get(['id','name','passenger_min','passenger_max','status']),
    ]);
  }

  public function edit(TourPackage $tourPackage)
  {
    $tourPackage->load([
      'places:id',
      'vehicleTypes:id',
      'availabilityRanges:id,tour_package_id,start_date,end_date',
      'priceOptions:id,tour_package_id,title,mode,values,status',
    ]);

    return Inertia::render('TourPackages/CreateUpdate', [
      'mode' => 'edit',
      'pkg' => [
        ...$tourPackage->only([
          'id','name','destination_group_id','days','passenger_min','passenger_max',
          'status','limited_km_enabled','included_km_basis','included_km','extra_km_price',
          'short_description','description','main_image_path','gallery_images'
        ]),
        'place_ids' => $tourPackage->places->pluck('id')->values(),
        'vehicle_type_ids' => $tourPackage->vehicleTypes->pluck('id')->values(),
        'availability_ranges' => $tourPackage->availabilityRanges->map(fn($r) => [
          'start_date' => $r->start_date->toDateString(),
          'end_date' => $r->end_date->toDateString(),
        ])->values(),
        'price_options' => $tourPackage->priceOptions->map(fn($p) => [
          'title' => $p->title,
          'mode' => $p->mode,
          'values' => $p->values,
          'status' => $p->status,
        ])->values(),
      ],
      'destinationGroups' => DestinationGroup::orderBy('sort_order')->orderBy('name')->get(['id','name']),
      'places' => Place::orderBy('name')->get(['id','destination_group_id','name']),
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
        $mainImagePath = $request->file('main_image')->store('tour-packages', 'public');
      }

      $gallery = [];
      if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $file) {
          $gallery[] = $file->store('tour-packages/gallery', 'public');
        }
      }

      $pkg = TourPackage::create([
        'name' => $validated['name'],
        'slug' => $slug,
        'destination_group_id' => $validated['destination_group_id'],
        'days' => $validated['days'],
        'passenger_min' => $validated['passenger_min'],
        'passenger_max' => $validated['passenger_max'],
        'status' => $validated['status'],

        'limited_km_enabled' => $validated['limited_km_enabled'] ?? false,
        'included_km_basis' => $validated['included_km_basis'] ?? null,
        'included_km' => $validated['included_km'] ?? null,
        'extra_km_price' => $validated['extra_km_price'] ?? null,

        'short_description' => $validated['short_description'] ?? null,
        'description' => $validated['description'] ?? null,
        'main_image_path' => $mainImagePath,
        'gallery_images' => $gallery ?: null,
      ]);

      $pkg->places()->sync($validated['place_ids']);
      $pkg->vehicleTypes()->sync($validated['vehicle_type_ids']);

      foreach ($validated['availability_ranges'] as $r) {
        TourPackageAvailabilityRange::create([
          'tour_package_id' => $pkg->id,
          'start_date' => $r['start_date'],
          'end_date' => $r['end_date'],
        ]);
      }

      foreach ($validated['price_options'] as $p) {
        TourPackagePriceOption::create([
          'tour_package_id' => $pkg->id,
          'title' => $p['title'],
          'mode' => $p['mode'],
          'values' => $p['values'],
          'status' => $p['status'] ?? 'active',
        ]);
      }

      return redirect()->route('tourpackages.index')->with('success','Tour package created.');
    });
  }

  public function update(Request $request, TourPackage $tourPackage)
  {
    $validated = $this->validatePayload($request);

    return DB::transaction(function () use ($request, $validated, $tourPackage) {
      $update = [
        'name' => $validated['name'],
        'destination_group_id' => $validated['destination_group_id'],
        'days' => $validated['days'],
        'passenger_min' => $validated['passenger_min'],
        'passenger_max' => $validated['passenger_max'],
        'status' => $validated['status'],

        'limited_km_enabled' => $validated['limited_km_enabled'] ?? false,
        'included_km_basis' => $validated['included_km_basis'] ?? null,
        'included_km' => $validated['included_km'] ?? null,
        'extra_km_price' => $validated['extra_km_price'] ?? null,

        'short_description' => $validated['short_description'] ?? null,
        'description' => $validated['description'] ?? null,
      ];

      if ($request->hasFile('main_image')) {
        if ($tourPackage->main_image_path) Storage::disk('public')->delete($tourPackage->main_image_path);
        $update['main_image_path'] = $request->file('main_image')->store('tour-packages', 'public');
      }

      $existingGallery = is_array($tourPackage->gallery_images) ? $tourPackage->gallery_images : [];
      $gallery = $existingGallery;

      if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $file) {
          $gallery[] = $file->store('tour-packages/gallery', 'public');
        }
        $update['gallery_images'] = $gallery;
      }

      $tourPackage->update($update);

      $tourPackage->places()->sync($validated['place_ids']);
      $tourPackage->vehicleTypes()->sync($validated['vehicle_type_ids']);

      // Replace ranges + price options (simple MVP)
      TourPackageAvailabilityRange::where('tour_package_id', $tourPackage->id)->delete();
      foreach ($validated['availability_ranges'] as $r) {
        TourPackageAvailabilityRange::create([
          'tour_package_id' => $tourPackage->id,
          'start_date' => $r['start_date'],
          'end_date' => $r['end_date'],
        ]);
      }

      TourPackagePriceOption::where('tour_package_id', $tourPackage->id)->delete();
      foreach ($validated['price_options'] as $p) {
        TourPackagePriceOption::create([
          'tour_package_id' => $tourPackage->id,
          'title' => $p['title'],
          'mode' => $p['mode'],
          'values' => $p['values'],
          'status' => $p['status'] ?? 'active',
        ]);
      }

      return redirect()->route('tourpackages.index')->with('success','Tour package updated.');
    });
  }

  public function destroy(TourPackage $tourPackage)
  {
    if ($tourPackage->main_image_path) Storage::disk('public')->delete($tourPackage->main_image_path);
    if (is_array($tourPackage->gallery_images)) {
      foreach ($tourPackage->gallery_images as $p) Storage::disk('public')->delete($p);
    }
    $tourPackage->delete();

    return redirect()->route('tourpackages.index')->with('success','Tour package deleted.');
  }

  private function validatePayload(Request $request): array
  {
    $data = $request->validate([
      'name' => ['required','string','max:255'],
      'destination_group_id' => ['required','exists:destination_groups,id'],
      'days' => ['required','integer','min:1','max:365'],
      'passenger_min' => ['required','integer','min:1','max:1000'],
      'passenger_max' => ['required','integer','min:1','max:1000','gte:passenger_min'],
      'status' => ['required','in:draft,published,hidden'],

      'limited_km_enabled' => ['nullable','boolean'],
      'included_km_basis' => ['nullable','in:total,per_day'],
      'included_km' => ['nullable','integer','min:1','max:1000000'],
      'extra_km_price' => ['nullable','numeric','min:0','max:100000000'],

      'short_description' => ['nullable','string','max:1000'],
      'description' => ['nullable','string'],

      'place_ids' => ['required','array','min:1'],
      'place_ids.*' => ['integer','exists:places,id'],

      'vehicle_type_ids' => ['required','array','min:1'],
      'vehicle_type_ids.*' => ['integer','exists:vehicle_types,id'],

      'availability_ranges' => ['required','array','min:1'],
      'availability_ranges.*.start_date' => ['required','date'],
      'availability_ranges.*.end_date' => ['required','date','after_or_equal:availability_ranges.*.start_date'],

      'price_options' => ['required','array','min:1'],
      'price_options.*.title' => ['required','string','max:100'],
      'price_options.*.mode' => ['required','in:fixed_total,per_day,per_person,tiered'],
      'price_options.*.values' => ['required','array'],
      'price_options.*.status' => ['nullable','in:active,inactive'],

      'main_image' => ['nullable','image','max:4096'],
      'gallery_images' => ['nullable','array'],
      'gallery_images.*' => ['image','max:4096'],
    ]);

    // If limited km enabled, enforce required fields
    if (!empty($data['limited_km_enabled'])) {
      if (empty($data['included_km_basis']) || empty($data['included_km']) || !isset($data['extra_km_price'])) {
        abort(422, 'Limited KM requires included_km_basis, included_km, and extra_km_price.');
      }
    }

    return $data;
  }

  private function uniqueSlug(string $name): string
  {
    $base = Str::slug($name);
    $slug = $base;
    $i = 1;

    while (TourPackage::where('slug',$slug)->exists()) {
      $slug = $base.'-'.$i;
      $i++;
    }

    return $slug;
  }
}
