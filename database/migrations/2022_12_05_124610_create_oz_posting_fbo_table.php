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
        Schema::create('oz_posting_fbo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('order_number');
            $table->string('posting_number');
            $table->string('status');
            $table->integer('cancel_reason_id');
            $table->dateTime('posting_created_at');
            $table->dateTime('in_process_at');
            $table->json('additional_data');
            $table->unsignedBigInteger('sku');
            $table->string('name');
            $table->integer('quantity');
            $table->string('offer_id');
            $table->float('price');
            $table->json('digital_codes');
            $table->string('region');
            $table->string('city');
            $table->string('delivery_type');
            $table->boolean('is_premium');
            $table->string('payment_type_group_name');
            $table->unsignedBigInteger('warehouse_id');
            $table->string('warehouse_name');
            $table->boolean('is_legal');
            $table->json('products');
            $table->integer('fulfillment');
            $table->integer('pickup');
            $table->integer('dropoff_pvz');
            $table->integer('dropoff_sc');
            $table->integer('dropoff_ff');
            $table->integer('direct_flow_trans');
            $table->integer('return_flow_trans');
            $table->integer('deliv_to_customer');
            $table->integer('return_not_deliv_to_customer');
            $table->integer('return_part_goods_customer');
            $table->integer('return_after_deliv_to_customer');
            $table->timestamps();

            $table->unique(['order_id', 'posting_number', 'sku'], 'ozon_posting_fbo_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oz_posting_fbo');
    }
};
