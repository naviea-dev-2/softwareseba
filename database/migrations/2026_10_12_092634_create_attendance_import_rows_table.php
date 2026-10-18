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
        Schema::create('attendance_import_rows', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attendance_import_id')
                ->constrained('attendance_imports')
                ->cascadeOnDelete();

            $table->unsignedInteger('row_number');

            // Preserve original Excel values for accurate validation.
            $table->json('raw_data');

            $table->json('errors')->nullable();

            $table->timestamps();

            $table->index([
                'attendance_import_id',
                'row_number',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_import_rows');
    }
};
