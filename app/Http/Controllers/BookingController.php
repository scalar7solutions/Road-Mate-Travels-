<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
  public function index(Request $request)
  {
    $q = $request->string('q')->toString();
    $status = $request->string('status')->toString();

    $items = Booking::query()
      ->with([
        'tourPackage:id,name',
        'rentalPackage:id,name',
      ])
      ->when($status, fn($qr) => $qr->where('status',$status))
      ->when($q, function ($qr) use ($q) {
        $qr->where(function ($x) use ($q) {
          $x->where('booking_ref','like',"%{$q}%")
            ->orWhere('customer_name','like',"%{$q}%")
            ->orWhere('customer_phone','like',"%{$q}%");
        });
      })
      ->orderByDesc('id')
      ->paginate(10)
      ->withQueryString();

    return Inertia::render('Bookings/Index', [
      'items' => $items,
      'filters' => ['q' => $q, 'status' => $status],
    ]);
  }

  public function show(Booking $booking)
  {
    $booking->load([
      'tourPackage:id,name',
      'rentalPackage:id,name',
      'tourPriceOption:id,title,mode,values',
    ]);

    return Inertia::render('Bookings/Show', [
      'booking' => $booking,
    ]);
  }

  public function updateStatus(Request $request, Booking $booking)
  {
    $validated = $request->validate([
      'status' => ['required','in:pending,confirmed,completed,cancelled'],
      'payment_status' => ['required','in:unpaid,partially_paid,paid'],
      'admin_note' => ['nullable','string'],
    ]);

    $booking->update($validated);

    return redirect()->route('bookings.show', $booking->id)->with('success','Booking updated.');
  }
}

