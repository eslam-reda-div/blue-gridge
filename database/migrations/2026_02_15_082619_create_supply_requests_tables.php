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
        Schema::create('supply_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factory_id')->constrained('factories')->cascadeOnDelete();
            $table->foreignId('material_sub_category_id')->constrained('material_sub_categories')->cascadeOnDelete();
            $table->decimal('quantity_needed', 10, 2);
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->foreignId('accepted_by_supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('triggered_by')->default('auto'); // auto, manual
            $table->timestamps();

            $table->index(['factory_id', 'material_sub_category_id', 'status'], 'supply_req_factory_material_status_idx');
        });

        Schema::create('supply_request_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_request_id')->constrained('supply_requests')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, accepted, rejected, dismissed
            $table->text('rejection_reason')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->unique(['supply_request_id', 'supplier_id'], 'supply_req_supplier_unique');
        });

        Schema::create('supply_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_request_id')->constrained('supply_requests')->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('action'); // request_created, sent_to_supplier, supplier_accepted, supplier_rejected, supplier_dismissed, request_fulfilled, all_rejected
            $table->text('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_request_logs');
        Schema::dropIfExists('supply_request_suppliers');
        Schema::dropIfExists('supply_requests');
    }
};
