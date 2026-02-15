<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factory_sub_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factory_id')->constrained('factories')->cascadeOnDelete();
            $table->unsignedBigInteger('material_sub_category_id');
            $table->foreign('material_sub_category_id', 'fac_sub_mat_sub_cat_fk')
                ->references('id')->on('material_sub_categories')->cascadeOnDelete();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['factory_id', 'material_sub_category_id'], 'factory_sub_material_unique');
        });

        Schema::create('factory_sub_material_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factory_id')->constrained('factories')->cascadeOnDelete();
            $table->unsignedBigInteger('material_sub_category_id');
            $table->foreign('material_sub_category_id', 'fac_sub_mat_txn_sub_cat_fk')
                ->references('id')->on('material_sub_categories')->cascadeOnDelete();
            $table->enum('type', ['insert', 'use']);
            $table->decimal('quantity', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['factory_id', 'material_sub_category_id'], 'factory_sub_material_txn_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factory_sub_material_transactions');
        Schema::dropIfExists('factory_sub_materials');
    }
};
