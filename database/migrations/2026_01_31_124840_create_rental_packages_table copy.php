<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('rental_package_prices', function (Blueprint $table) {
      $table->id();
      $table->foreignId('rental_package_id')->constrained('rental_packages')->cascadeOnDelete();
      $table->enum('service_type', ['self_drive','with_driver','all_inclusive']);
      $table->enum('pricing_mode', ['fixed_total','per_day'])->default('per_day');
      $table->json('values'); // e.g. { price: 12000 } or { price_per_day: 12000 }
      $table->enum('status', ['active','inactive'])->default('active');
      $table->timestamps();

      $table->unique(['rental_package_id','service_type']);
    });
  }
  public function down(): void { Schema::dropIfExists('rental_package_prices'); }
};

