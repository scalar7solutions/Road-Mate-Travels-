<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('destination_groups', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->enum('status', ['active','inactive'])->default('active');
      $table->unsignedInteger('sort_order')->default(0);
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('destination_groups'); }
};
