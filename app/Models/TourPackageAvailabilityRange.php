<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPackageAvailabilityRange extends Model
{
  protected $fillable = ['tour_package_id','start_date','end_date'];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
  ];

  public function tourPackage()
  {
    return $this->belongsTo(TourPackage::class);
  }
}
