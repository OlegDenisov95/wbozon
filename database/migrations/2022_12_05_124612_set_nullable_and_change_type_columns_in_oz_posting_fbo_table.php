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
        Schema::table('oz_posting_fbo', function (Blueprint $table) {
            $table->string('order_number')->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->integer('cancel_reason_id')->nullable()->change();
            $table->dateTime('posting_created_at', 3)->nullable()->change();
            $table->dateTime('in_process_at', 3)->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
            $table->string('offer_id')->nullable()->change();
            $table->double('price')->nullable()->change();
            $table->json('digital_codes')->nullable()->change();
            $table->string('region')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('delivery_type')->nullable()->change();
            $table->boolean('is_premium')->nullable()->change();
            $table->string('payment_type_group_name')->nullable()->change();
            $table->unsignedBigInteger('warehouse_id')->nullable()->change();
            $table->string('warehouse_name')->nullable()->change();
            $table->boolean('is_legal')->nullable()->change();
            $table->double('fulfillment')->nullable()->change();
            $table->double('pickup')->nullable()->change();
            $table->double('dropoff_pvz')->nullable()->change();
            $table->double('dropoff_sc')->nullable()->change();
            $table->double('dropoff_ff')->nullable()->change();
            $table->double('direct_flow_trans')->nullable()->change();
            $table->double('return_flow_trans')->nullable()->change();
            $table->double('deliv_to_customer')->nullable()->change();
            $table->double('return_not_deliv_to_customer')->nullable()->change();
            $table->double('return_part_goods_customer')->nullable()->change();
            $table->double('return_after_deliv_to_customer')->nullable()->change();
            $table->json('additional_data')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oz_posting_fbo', function (Blueprint $table) {
            $table->dateTime('posting_created_at')->change();
            $table->dateTime('in_process_at')->change();
            $table->float('price')->change();
            $table->integer('fulfillment')->change();
            $table->integer('pickup')->change();
            $table->integer('dropoff_pvz')->change();
            $table->integer('dropoff_sc')->change();
            $table->integer('dropoff_ff')->change();
            $table->integer('direct_flow_trans')->change();
            $table->integer('return_flow_trans')->change();
            $table->integer('deliv_to_customer')->change();
            $table->integer('return_not_deliv_to_customer')->change();
            $table->integer('return_part_goods_customer')->change();
            $table->integer('return_after_deliv_to_customer')->change();
        });
    }
};
