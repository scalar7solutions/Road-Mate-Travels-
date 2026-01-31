<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('tour_package_availability_ranges', function (Blueprint $table) {
      $table->id();
      $table->foreignId('tour_package_id')->constrained('tour_packages')->cascadeOnDelete();
      $table->date('start_date');
      $table->date('end_date');
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('tour_package_availability_ranges'); }
};
