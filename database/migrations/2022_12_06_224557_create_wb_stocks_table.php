<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wb_stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->dateTime('last_change_date', 3);
            $table->string('supplier_article', 75);
            $table->string('tech_size', 30);
            $table->string('barcode', 30);
            $table->integer('quantity');
            $table->boolean('is_supply');
            $table->boolean('is_realization');
            $table->integer('quantity_full');
            $table->integer('quantity_not_in_orders');
            $table->unsignedBigInteger('warehouse');
            $table->string('warehouse_name', 50);
            $table->integer('in_way_to_client');
            $table->integer('in_way_from_client');
            $table->unsignedBigInteger('nm_id');
            $table->string('subject', 50);
            $table->string('category', 50);
            $table->integer('days_on_site');
            $table->string('brand', 50);
            $table->string('sc_code', 50);
            $table->float('price');
            $table->float('discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wb_stocks');
    }
};
