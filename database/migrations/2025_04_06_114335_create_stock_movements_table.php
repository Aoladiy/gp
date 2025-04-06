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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('part_item_id');
            $table->unsignedBigInteger('stock_movement_type_id');
            $table->unsignedBigInteger('from_location_id')->nullable();
            $table->unsignedBigInteger('to_location_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dateTime('moved_at');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('part_item_id')
                ->references('id')
                ->on('part_items')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('stock_movement_type_id')
                ->references('id')
                ->on('stock_movement_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('from_location_id')
                ->references('id')
                ->on('storage_locations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('to_location_id')
                ->references('id')
                ->on('storage_locations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
