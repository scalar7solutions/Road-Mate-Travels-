<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalPackagePrice extends Model
{
  protected $fillable = ['rental_package_id','service_type','pricing_mode','values','status'];

  protected $casts = ['values' => 'array'];

  public function rentalPackage()
  {
    return $this->belongsTo(RentalPackage::class);
  }
}
