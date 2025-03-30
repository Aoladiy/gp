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
        Schema::create('part_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('part_id');
            $table->string('serial_number')->nullable();
            $table->unsignedBigInteger('part_batch_id')->nullable();
            $table->unsignedBigInteger('storage_location_id')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->text('condition')->nullable();
            $table->timestamps();

            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('part_batch_id')
                ->references('id')
                ->on('part_batches')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('storage_location_id')
                ->references('id')
                ->on('storage_locations')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('status_id')
                ->references('id')
                ->on('part_item_statuses')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_items');
    }
};
