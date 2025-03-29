<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('storage_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requireable_id');
            $table->string('requireable_type');
            $table->float('temperature_min')->nullable();
            $table->float('temperature_max')->nullable();
            $table->float('humidity_min')->nullable();
            $table->float('humidity_max')->nullable();
            $table->unsignedBigInteger('lighting_level_id')->nullable();
            $table->string('ventilation_level')->nullable();
            $table->string('fire_safety_class')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('lighting_level_id')
                ->references('id')
                ->on('lighting_levels')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->unique([
                'requireable_id',
                'requireable_type',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_requirements');
    }
};
