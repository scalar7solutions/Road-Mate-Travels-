<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('bookings', function (Blueprint $table) {
      $table->id();
      $table->string('booking_ref')->unique();

      $table->enum('booking_type', ['tour','rental']);
      $table->foreignId('tour_package_id')->nullable()->constrained('tour_packages')->nullOnDelete();
      $table->foreignId('rental_package_id')->nullable()->constrained('rental_packages')->nullOnDelete();

      // selection
      $table->foreignId('tour_price_option_id')->nullable()->constrained('tour_package_price_options')->nullOnDelete();
      $table->enum('rental_service_type', ['self_drive','with_driver','all_inclusive'])->nullable();

      $table->date('start_date');
      $table->date('end_date')->nullable();
      $table->unsignedInteger('days')->default(1);
      $table->unsignedInteger('passengers')->default(1);

      // customer
      $table->string('customer_name');
      $table->string('customer_phone');
      $table->string('customer_email')->nullable();

      // money
      $table->decimal('subtotal', 12, 2)->default(0);
      $table->decimal('extra_km_fee', 12, 2)->default(0);
      $table->decimal('total', 12, 2)->default(0);

      // snapshot
      $table->json('price_snapshot')->nullable();
      $table->longText('admin_note')->nullable();

      $table->enum('status', ['pending','confirmed','completed','cancelled'])->default('pending');
      $table->enum('payment_status', ['unpaid','partially_paid','paid'])->default('unpaid');

      $table->timestamps();
      $table->index(['booking_type','status']);
    });
  }
  public function down(): void { Schema::dropIfExists('bookings'); }
};
