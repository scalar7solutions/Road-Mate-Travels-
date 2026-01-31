<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('rental_packages', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->cascadeOnDelete();
      $table->unsignedInteger('passenger_max')->default(1);
      $table->enum('status', ['draft','published','hidden'])->default('draft');
      $table->text('short_description')->nullable();
      $table->longText('description')->nullable();
      $table->string('main_image_path')->nullable();
      $table->json('gallery_images')->nullable();
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('rental_packages'); }
};
