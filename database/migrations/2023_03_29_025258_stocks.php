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
        if (Schema::hasTable('stocks')) {
            return;
        }
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->dateTime('last_change_date');
            $table->string('supplier_article', 75);
            $table->string('tech_size', 30);
            $table->string('barcode', 30);
            $table->integer('quantity');
            $table->boolean('is_supply');
            $table->boolean('is_realization');
            $table->integer('quantity_full');
            $table->string('warehouse_name');
            $table->string('subject', 50);
            $table->string('category', 50);
            $table->integer('days_on_site');
            $table->string('brand', 50);
            $table->string('sc_code', 50);
            $table->decimal('price');
            $table->decimal('discount');
            $table->unsignedBigInteger('nm_id')->references('id')->on('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
