<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('vehicle_types', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->unsignedInteger('passenger_min')->default(1);
      $table->unsignedInteger('passenger_max')->default(1);
      $table->string('image_path')->nullable();
      $table->enum('status', ['active','inactive'])->default('active');
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('vehicle_types'); }
};
