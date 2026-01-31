<?php
namespace App\Http\Controllers;

use App\Models\DestinationGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DestinationGroupController extends Controller
{
  public function index(Request $request)
  {
    $q = $request->string('q')->toString();

    $items = DestinationGroup::query()
      ->when($q, fn($qr) => $qr->where('name','like',"%{$q}%"))
      ->orderBy('sort_order')
      ->orderByDesc('id')
      ->paginate(10)
      ->withQueryString();

    return Inertia::render('DestinationGroups/Index', [
      'items' => $items,
      'filters' => ['q' => $q],
    ]);
  }

  public function create()
  {
    return Inertia::render('DestinationGroups/CreateUpdate', [
      'mode' => 'create',
      'group' => null,
    ]);
  }

  public function edit(DestinationGroup $destinationGroup)
  {
    return Inertia::render('DestinationGroups/CreateUpdate', [
      'mode' => 'edit',
      'group' => $destinationGroup,
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => ['required','string','max:255'],
      'status' => ['required','in:active,inactive'],
      'sort_order' => ['nullable','integer','min:0','max:100000'],
    ]);

    $slug = $this->uniqueSlug($validated['name']);
    DestinationGroup::create([
      ...$validated,
      'slug' => $slug,
      'sort_order' => $validated['sort_order'] ?? 0,
    ]);

    return redirect()->route('destinationgroups.index')->with('success','Destination group created.');
  }

  public function update(Request $request, DestinationGroup $destinationGroup)
  {
    $validated = $request->validate([
      'name' => ['required','string','max:255'],
      'status' => ['required','in:active,inactive'],
      'sort_order' => ['nullable','integer','min:0','max:100000'],
    ]);

    // slug update optional; keep stable in MVP
    $destinationGroup->update([
      ...$validated,
      'sort_order' => $validated['sort_order'] ?? 0,
    ]);

    return redirect()->route('destinationgroups.index')->with('success','Destination group updated.');
  }

  public function destroy(DestinationGroup $destinationGroup)
  {
    $destinationGroup->delete();
    return redirect()->route('destinationgroups.index')->with('success','Destination group deleted.');
  }

  private function uniqueSlug(string $name): string
  {
    $base = Str::slug($name);
    $slug = $base;
    $i = 1;

    while (DestinationGroup::where('slug',$slug)->exists()) {
      $slug = $base.'-'.$i;
      $i++;
    }

    return $slug;
  }
}

