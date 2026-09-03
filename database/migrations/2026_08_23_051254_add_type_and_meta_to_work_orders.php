<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->foreignId('work_order_type_id')
                ->nullable()
                ->after('business_id')
                ->constrained('work_order_types')
                ->nullOnDelete();

            $table->json('meta')->nullable()->after('completion_notes');
            $table->json('company_settings')->nullable()->after('meta');
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('work_order_type_id');
            $table->dropColumn('meta');
            $table->dropColumn('company_settings');
        });
    }
};