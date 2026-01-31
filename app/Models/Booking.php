<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
  protected $fillable = [
    'booking_ref','booking_type',
    'tour_package_id','rental_package_id',
    'tour_price_option_id','rental_service_type',
    'start_date','end_date','days','passengers',
    'customer_name','customer_phone','customer_email',
    'subtotal','extra_km_fee','total',
    'price_snapshot','admin_note',
    'status','payment_status'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'price_snapshot' => 'array',
  ];

  public function tourPackage()
  {
    return $this->belongsTo(TourPackage::class);
  }

  public function rentalPackage()
  {
    return $this->belongsTo(RentalPackage::class);
  }

  public function tourPriceOption()
  {
    return $this->belongsTo(TourPackagePriceOption::class, 'tour_price_option_id');
  }
}
