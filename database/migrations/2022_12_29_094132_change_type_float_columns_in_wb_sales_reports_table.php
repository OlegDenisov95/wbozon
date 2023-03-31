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
        Schema::table('wb_sales_reports', function (Blueprint $table) {
            $table->double('retail_price')->change();
            $table->double('retail_amount')->change();
            $table->double('commission_percent')->change();
            $table->double('retail_price_withdisc_rub')->change();
            $table->double('delivery_rub')->change();
            $table->double('product_discount_for_report')->change();
            $table->double('supplier_promo')->change();
            $table->double('ppvz_spp_prc')->change();
            $table->double('ppvz_kvw_prc_base')->change();
            $table->double('ppvz_kvw_prc')->change();
            $table->double('ppvz_sales_commission')->change();
            $table->double('ppvz_for_pay')->change();
            $table->double('ppvz_reward')->change();
            $table->double('acquiring_fee')->change();
            $table->double('ppvz_vw')->change();
            $table->double('ppvz_vw_nds')->change();
            $table->double('penalty')->nullable()->change();
            $table->double('additional_payment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wb_sales_reports', function (Blueprint $table) {
            $table->float('retail_price')->change();
            $table->float('retail_amount')->change();
            $table->float('commission_percent')->change();
            $table->float('retail_price_withdisc_rub')->change();
            $table->float('delivery_rub')->change();
            $table->float('product_discount_for_report')->change();
            $table->float('supplier_promo')->change();
            $table->float('ppvz_spp_prc')->change();
            $table->float('ppvz_kvw_prc_base')->change();
            $table->float('ppvz_kvw_prc')->change();
            $table->float('ppvz_sales_commission')->change();
            $table->float('ppvz_for_pay')->change();
            $table->float('ppvz_reward')->change();
            $table->float('acquiring_fee')->change();
            $table->float('ppvz_vw')->change();
            $table->float('ppvz_vw_nds')->change();
            $table->float('penalty')->nullable()->change();
            $table->float('additional_payment')->nullable()->change();
        });
    }
};
