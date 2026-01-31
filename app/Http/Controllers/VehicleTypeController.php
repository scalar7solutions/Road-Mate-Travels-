<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VehicleTypeController extends Controller
{
  public function index(Request $request)
  {
    $q = $request->string('q')->toString();

    $items = VehicleType::query()
      ->when($q, fn($qr) => $qr->where('name','like',"%{$q}%"))
      ->orderByDesc('id')
      ->paginate(10)
      ->withQueryString();

    return Inertia::render('VehicleTypes/Index', [
      'items' => $items,
      'filters' => ['q' => $q],
    ]);
  }

  public function create()
  {
    return Inertia::render('VehicleTypes/CreateUpdate', [
      'mode' => 'create',
      'vehicleType' => null,
    ]);
  }

  public function edit(VehicleType $vehicleType)
  {
    return Inertia::render('VehicleTypes/CreateUpdate', [
      'mode' => 'edit',
      'vehicleType' => $vehicleType,
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => ['required','string','max:255'],
      'passenger_min' => ['required','integer','min:1','max:1000'],
      'passenger_max' => ['required','integer','min:1','max:1000','gte:passenger_min'],
      'status' => ['required','in:active,inactive'],
      'image' => ['nullable','image','max:4096'],
    ]);

    if ($request->hasFile('image')) {
      $validated['image_path'] = $request->file('image')->store('vehicle-types', 'public');
    }

    VehicleType::create($validated);

    return redirect()->route('vehicletypes.index')->with('success','Vehicle type created.');
  }

  public function update(Request $request, VehicleType $vehicleType)
  {
    $validated = $request->validate([
      'name' => ['required','string','max:255'],
      'passenger_min' => ['required','integer','min:1','max:1000'],
      'passenger_max' => ['required','integer','min:1','max:1000','gte:passenger_min'],
      'status' => ['required','in:active,inactive'],
      'image' => ['nullable','image','max:4096'],
    ]);

    if ($request->hasFile('image')) {
      // optional: delete old
      if ($vehicleType->image_path) Storage::disk('public')->delete($vehicleType->image_path);
      $validated['image_path'] = $request->file('image')->store('vehicle-types', 'public');
    }

    $vehicleType->update($validated);

    return redirect()->route('vehicletypes.index')->with('success','Vehicle type updated.');
  }

  public function destroy(VehicleType $vehicleType)
  {
    if ($vehicleType->image_path) Storage::disk('public')->delete($vehicleType->image_path);
    $vehicleType->delete();

    return redirect()->route('vehicletypes.index')->with('success','Vehicle type deleted.');
  }
}
