<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
  protected $fillable = [
    'destination_group_id','name','image_path','map_url','lat','lng','status'
  ];

  public function group()
  {
    return $this->belongsTo(DestinationGroup::class, 'destination_group_id');
  }

  public function tourPackages()
  {
    return $this->belongsToMany(TourPackage::class, 'tour_package_place');
  }
}
