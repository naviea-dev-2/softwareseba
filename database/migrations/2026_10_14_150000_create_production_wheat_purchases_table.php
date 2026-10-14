<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('production_wheat_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index(); // Existing production business scope
            $table->unsignedBigInteger('raw_material_id')->nullable()->index();
            $table->date('purchase_date')->nullable()->index();
            $table->string('weight_slip_no', 100);
            $table->string('vehicle_no', 100)->nullable();
            $table->string('driver_name', 150)->nullable();
            $table->string('driver_mobile', 30)->nullable();
            $table->unsignedInteger('bag_quantity')->nullable();
            $table->string('quantity_description', 80)->nullable();
            $table->decimal('empty_weight_kg', 16, 3);
            $table->decimal('loaded_weight_kg', 16, 3);
            $table->decimal('net_weight_kg', 16, 3);
            $table->string('supplier_name', 200)->nullable();
            $table->string('slip_receiver', 150)->nullable();
            $table->decimal('rate_per_kg', 16, 4);
            $table->decimal('purchase_amount', 18, 2);
            $table->text('remarks')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'weight_slip_no'], 'wheat_scope_slip_unique');
        });
    }
    public function down(): void { Schema::dropIfExists('production_wheat_purchases'); }
};
