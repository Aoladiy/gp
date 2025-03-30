<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('storage_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('location_type_id');
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('storage_locations')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('location_type_id')
                ->references('id')
                ->on('storage_location_types')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_locations');
    }
};
