<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
  protected $fillable = ['name','passenger_min','passenger_max','image_path','status'];

  public function tourPackages()
  {
    return $this->belongsToMany(TourPackage::class, 'tour_package_vehicle_type');
  }
}
