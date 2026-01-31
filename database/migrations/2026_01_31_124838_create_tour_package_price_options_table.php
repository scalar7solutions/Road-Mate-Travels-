<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('tour_package_price_options', function (Blueprint $table) {
      $table->id();
      $table->foreignId('tour_package_id')->constrained('tour_packages')->cascadeOnDelete();
      $table->string('title'); // Standard / Premium
      $table->enum('mode', ['fixed_total','per_day','per_person','tiered']);
      $table->json('values'); // mode-specific data
      $table->enum('status', ['active','inactive'])->default('active');
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('tour_package_price_options'); }
};
