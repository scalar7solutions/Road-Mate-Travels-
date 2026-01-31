<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tour_package_place', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();

            $table->unsignedBigInteger('tour_package_id');
            $table->unsignedBigInteger('place_id');

            $table->foreign('tour_package_id')
                ->references('id')
                ->on('tour_packages')
                ->cascadeOnDelete();

            $table->foreign('place_id')
                ->references('id')
                ->on('places')
                ->cascadeOnDelete();

            $table->unique(['tour_package_id', 'place_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_package_place');
    }
};
