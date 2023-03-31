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
        if (Schema::hasTable('incomes')) {
            return;
        }
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('number', 40);
            $table->dateTime('date');
            $table->dateTime('last_change_date');
            $table->string('supplier_article', 75);
            $table->string('techSize', 30);
            $table->string('barcode', 30);
            $table->integer('quantity');
            $table->decimal('total_price');
            $table->dateTime('date_close');
            $table->string('warehouse_name');
            $table->unsignedBigInteger('nm_id')->references('id')->on('price');
            $table->string('status',50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
