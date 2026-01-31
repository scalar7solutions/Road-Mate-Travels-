<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPackagePriceOption extends Model
{
  protected $fillable = ['tour_package_id','title','mode','values','status'];

  protected $casts = ['values' => 'array'];

  public function tourPackage()
  {
    return $this->belongsTo(TourPackage::class);
  }
}
