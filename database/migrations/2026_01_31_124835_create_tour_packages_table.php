<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('tour_packages', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->foreignId('destination_group_id')->constrained()->cascadeOnDelete();
      $table->unsignedInteger('days')->default(1);
      $table->unsignedInteger('passenger_min')->default(1);
      $table->unsignedInteger('passenger_max')->default(1);
      $table->enum('status', ['draft','published','hidden'])->default('draft');

      $table->boolean('limited_km_enabled')->default(false);
      $table->enum('included_km_basis', ['total','per_day'])->nullable();
      $table->unsignedInteger('included_km')->nullable();
      $table->decimal('extra_km_price', 12, 2)->nullable();

      $table->text('short_description')->nullable();
      $table->longText('description')->nullable();
      $table->string('main_image_path')->nullable();
      $table->json('gallery_images')->nullable();

      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('tour_packages'); }
};
