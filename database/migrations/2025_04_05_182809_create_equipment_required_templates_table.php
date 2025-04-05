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
        Schema::create('equipment_required_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('part_template_id');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('equipment_id')
                ->references('id')
                ->on('equipment')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('part_template_id')
                ->references('id')
                ->on('part_templates')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_required_templates');
    }
};
