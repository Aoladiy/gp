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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('name');
            $table->string('article_number')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('rotation_method_id')->nullable();
            $table->timestamps();

            $table->foreign('template_id')
                ->references('id')
                ->on('part_templates')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('rotation_method_id')
                ->references('id')
                ->on('rotation_methods')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
