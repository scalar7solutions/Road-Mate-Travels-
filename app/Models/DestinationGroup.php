<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationGroup extends Model
{
  protected $fillable = ['name','slug','status','sort_order'];

  public function places()
  {
    return $this->hasMany(Place::class);
  }

  public function tourPackages()
  {
    return $this->hasMany(TourPackage::class);
  }
}
