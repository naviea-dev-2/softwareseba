<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_wheat_purchases', function (Blueprint $table) {
            $table->decimal('paid_amount', 15, 2)->default(0)->after('purchase_amount');
        });

        Schema::create('production_wheat_purchase_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wheat_purchase_id')->constrained('production_wheat_purchases')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('method'); // cash, bkash, bank
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_wheat_purchase_payments');

        Schema::table('production_wheat_purchases', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
};