<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('tour_package_vehicle_type', function (Blueprint $table) {
      $table->id();
      $table->foreignId('tour_package_id')->constrained('tour_packages')->cascadeOnDelete();
      $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->cascadeOnDelete();
      $table->unique(['tour_package_id','vehicle_type_id']);
    });
  }
  public function down(): void { Schema::dropIfExists('tour_package_vehicle_type'); }
};
