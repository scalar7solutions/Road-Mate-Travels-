<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalPackage extends Model
{
  protected $fillable = [
    'name','slug','vehicle_type_id','passenger_max','status',
    'short_description','description','main_image_path','gallery_images'
  ];

  protected $casts = ['gallery_images' => 'array'];

  public function vehicleType()
  {
    return $this->belongsTo(VehicleType::class);
  }

  public function prices()
  {
    return $this->hasMany(RentalPackagePrice::class);
  }
}
