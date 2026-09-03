<?php
// database/migrations/2026_08_23_000001_create_work_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();

            // Tenant
            $table->foreignId('business_id')->constrained()->onDelete('cascade');

            // Identity
            $table->string('work_order_no', 50);
            $table->string('work_order_type', 50); // production, repair, service, software, maintenance
            $table->string('title', 255);
            $table->text('description')->nullable();

            // Source / Origin (polymorphic reference to SO, PO, Project, etc.)
            $table->string('reference_type', 100)->nullable(); // App\Models\Sales\Order
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_no', 100)->nullable();

            // Business Party
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();

            // Assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            // Location
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();

            // Priority & Status
            $table->string('priority', 20)->default('normal'); // low, normal, high, urgent
            $table->string('status', 30)->default('draft'); // draft, pending, in_progress, on_hold, completed, cancelled, closed

            // Dates
            $table->dateTime('requested_at')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();

            // Progress
            $table->decimal('progress', 5, 2)->default(0); // 0.00 to 100.00

            // Financial
            $table->decimal('estimated_cost', 15, 2)->default(0);
            $table->decimal('actual_cost', 15, 2)->default(0);

            // Time
            $table->decimal('estimated_hours', 10, 2)->default(0);
            $table->decimal('actual_hours', 10, 2)->default(0);

            // Notes
            $table->text('instructions')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('completion_notes')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique(['business_id', 'work_order_no'], 'uq_wo_business_no');
            $table->index(['business_id', 'status'], 'idx_wo_business_status');
            $table->index(['business_id', 'work_order_type'], 'idx_wo_business_type');
            $table->index(['business_id', 'customer_id'], 'idx_wo_business_customer');
            $table->index(['business_id', 'vendor_id'], 'idx_wo_business_vendor');
            $table->index(['business_id', 'assigned_to'], 'idx_wo_business_assigned');
            $table->index(['business_id', 'warehouse_id'], 'idx_wo_business_warehouse');
            $table->index(['business_id', 'due_at'], 'idx_wo_business_due');
            $table->index(['reference_type', 'reference_id'], 'idx_wo_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};