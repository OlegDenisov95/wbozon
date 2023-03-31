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
        if (Schema::hasTable('sales')) {
            return;
        }
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('g_number');
            $table->dateTime('date');
            $table->dateTime('last_change_date');
            $table->string('supplier_article', 75);
            $table->string('tech_size', 30);
            $table->string('barcode', 30);
            $table->decimal('total_price');
            $table->integer('discount_percent');
            $table->boolean('is_supply');
            $table->boolean('is_realization');
            $table->decimal('promo_code_discount');
            $table->string('warehouse_name');
            $table->string('country_name', 200);
            $table->string('oblast_okrug_name', 200);
            $table->string('region_name', 200);
            $table->unsignedBigInteger('income_id')->references('id')->on('incomes');
            $table->string('sale_id', 15);
            $table->integer('od_id');
            $table->decimal('spp');
            $table->decimal('for_pay');
            $table->decimal('finished_price');
            $table->decimal('price_with_disc');
            $table->unsignedBigInteger('nm_id')->references('id')->on('price');
            $table->string('subject', 50);
            $table->string('category', 50);
            $table->string('brand', 50);
            $table->integer('is_storno');
            $table->string('sticker');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
