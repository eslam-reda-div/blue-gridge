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
        Schema::create('selling_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();
            $table->foreignId('material_sub_category_id')->constrained('material_sub_categories')->cascadeOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->decimal('price_per_unit', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('type')->default('broadcast'); // broadcast, targeted
            $table->string('status')->default('open'); // open, accepted, closed
            $table->foreignId('accepted_by_supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->index(['seller_id', 'status']);
            $table->index(['material_sub_category_id', 'status']);
        });

        Schema::create('selling_offer_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selling_offer_id')->constrained('selling_offers')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['selling_offer_id', 'supplier_id'], 'sell_offer_target_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selling_offer_targets');
        Schema::dropIfExists('selling_offers');
    }
};
