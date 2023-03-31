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
            $table->renameColumn('fulfillment', 'marketplace_service_item_fulfillment');
            $table->renameColumn('pickup', 'marketplace_service_item_pickup');
            $table->renameColumn('dropoff_pvz', 'marketplace_service_item_dropoff_pvz');
            $table->renameColumn('dropoff_sc', 'marketplace_service_item_dropoff_sc');
            $table->renameColumn('dropoff_ff', 'marketplace_service_item_dropoff_ff');
            $table->renameColumn('direct_flow_trans', 'marketplace_service_item_direct_flow_trans');
            $table->renameColumn('return_flow_trans', 'marketplace_service_item_return_flow_trans');
            $table->renameColumn('deliv_to_customer', 'marketplace_service_item_deliv_to_customer');
            $table->renameColumn('return_not_deliv_to_customer', 'marketplace_service_item_return_not_deliv_to_customer');
            $table->renameColumn('return_part_goods_customer', 'marketplace_service_item_return_part_goods_customer');
            $table->renameColumn('return_after_deliv_to_customer', 'marketplace_service_item_return_after_deliv_to_customer');
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
            $table->renameColumn('marketplace_service_item_fulfillment', 'fulfillment');
            $table->renameColumn('marketplace_service_item_pickup', 'pickup');
            $table->renameColumn('marketplace_service_item_dropoff_pvz', 'dropoff_pvz');
            $table->renameColumn('marketplace_service_item_dropoff_sc', 'dropoff_sc');
            $table->renameColumn('marketplace_service_item_dropoff_ff', 'dropoff_ff');
            $table->renameColumn('marketplace_service_item_direct_flow_trans', 'direct_flow_trans');
            $table->renameColumn('marketplace_service_item_return_flow_trans', 'return_flow_trans');
            $table->renameColumn('marketplace_service_item_deliv_to_customer', 'deliv_to_customer');
            $table->renameColumn('marketplace_service_item_return_not_deliv_to_customer', 'return_not_deliv_to_customer');
            $table->renameColumn('marketplace_service_item_return_part_goods_customer', 'return_part_goods_customer');
            $table->renameColumn('marketplace_service_item_return_after_deliv_to_customer', 'return_after_deliv_to_customer');
        });
    }
};
