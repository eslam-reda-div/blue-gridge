<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_sub_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->unsignedBigInteger('material_sub_category_id');
            $table->foreign('material_sub_category_id', 'sup_sub_mat_sub_cat_fk')
                ->references('id')->on('material_sub_categories')->cascadeOnDelete();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['supplier_id', 'material_sub_category_id'], 'supplier_sub_material_unique');
        });

        Schema::create('supplier_sub_material_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->unsignedBigInteger('material_sub_category_id');
            $table->foreign('material_sub_category_id', 'sup_sub_mat_txn_sub_cat_fk')
                ->references('id')->on('material_sub_categories')->cascadeOnDelete();
            $table->enum('type', ['insert', 'use']);
            $table->decimal('quantity', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['supplier_id', 'material_sub_category_id'], 'supplier_sub_material_txn_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_sub_material_transactions');
        Schema::dropIfExists('supplier_sub_materials');
    }
};
