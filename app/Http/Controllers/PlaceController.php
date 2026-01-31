<?php

namespace App\Http\Controllers;

use App\Models\DestinationGroup;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PlaceController extends Controller
{
  public function index(Request $request)
  {
    $q = $request->string('q')->toString();
    $groupId = $request->integer('group_id');

    $items = Place::query()
      ->with('group:id,name')
      ->when($groupId, fn($qr) => $qr->where('destination_group_id',$groupId))
      ->when($q, fn($qr) => $qr->where('name','like',"%{$q}%"))
      ->orderByDesc('id')
      ->paginate(10)
      ->withQueryString();

    $groups = DestinationGroup::orderBy('sort_order')->orderBy('name')->get(['id','name']);

    return Inertia::render('Places/Index', [
      'items' => $items,
      'groups' => $groups,
      'filters' => ['q' => $q, 'group_id' => $groupId],
    ]);
  }

  public function create()
  {
    return Inertia::render('Places/CreateUpdate', [
      'mode' => 'create',
      'place' => null,
      'groups' => DestinationGroup::orderBy('sort_order')->orderBy('name')->get(['id','name']),
    ]);
  }

  public function edit(Place $place)
  {
    return Inertia::render('Places/CreateUpdate', [
      'mode' => 'edit',
      'place' => $place->only(['id','destination_group_id','name','image_path','map_url','lat','lng','status']),
      'groups' => DestinationGroup::orderBy('sort_order')->orderBy('name')->get(['id','name']),
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'destination_group_id' => ['required','exists:destination_groups,id'],
      'name' => ['required','string','max:255'],
      'map_url' => ['nullable','string','max:500'],
      'lat' => ['nullable','numeric','between:-90,90'],
      'lng' => ['nullable','numeric','between:-180,180'],
      'status' => ['required','in:active,inactive'],
      'image' => ['nullable','image','max:4096'],
    ]);

    if ($request->hasFile('image')) {
      $validated['image_path'] = $request->file('image')->store('places', 'public');
    }

    Place::create($validated);

    return redirect()->route('places.index')->with('success','Place created.');
  }

  public function update(Request $request, Place $place)
  {
    $validated = $request->validate([
      'destination_group_id' => ['required','exists:destination_groups,id'],
      'name' => ['required','string','max:255'],
      'map_url' => ['nullable','string','max:500'],
      'lat' => ['nullable','numeric','between:-90,90'],
      'lng' => ['nullable','numeric','between:-180,180'],
      'status' => ['required','in:active,inactive'],
      'image' => ['nullable','image','max:4096'],
    ]);

    if ($request->hasFile('image')) {
      if ($place->image_path) Storage::disk('public')->delete($place->image_path);
      $validated['image_path'] = $request->file('image')->store('places', 'public');
    }

    $place->update($validated);

    return redirect()->route('places.index')->with('success','Place updated.');
  }

  public function destroy(Place $place)
  {
    if ($place->image_path) Storage::disk('public')->delete($place->image_path);
    $place->delete();

    return redirect()->route('places.index')->with('success','Place deleted.');
  }
}
