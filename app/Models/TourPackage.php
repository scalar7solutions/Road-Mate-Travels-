<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
  protected $fillable = [
    'name','slug','destination_group_id','days','passenger_min','passenger_max','status',
    'limited_km_enabled','included_km_basis','included_km','extra_km_price',
    'short_description','description','main_image_path','gallery_images'
  ];

  protected $casts = [
    'limited_km_enabled' => 'boolean',
    'gallery_images' => 'array',
  ];

  public function destinationGroup()
  {
    return $this->belongsTo(DestinationGroup::class);
  }

  public function places()
  {
    return $this->belongsToMany(Place::class, 'tour_package_place');
  }

  public function vehicleTypes()
  {
    return $this->belongsToMany(VehicleType::class, 'tour_package_vehicle_type');
  }

  public function availabilityRanges()
  {
    return $this->hasMany(TourPackageAvailabilityRange::class);
  }

  public function priceOptions()
  {
    return $this->hasMany(TourPackagePriceOption::class);
  }
}
