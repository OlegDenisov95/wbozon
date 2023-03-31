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
        if (Schema::hasTable('report_detail_by_period')) {
            return;
        }
        Schema::create('report_detail_by_period', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_from');
            $table->dateTime('date_to');
            $table->dateTime('create_dt');
            //    $table->object('suppliercontract_code');
            $table->integer('rrd_id');
            $table->integer('gi_id');
            $table->string('subject_name');
            $table->unsignedBigInteger('nm_id')->references('id')->on('price');
            $table->string('brand_name');
            $table->string('sa_name');
            $table->string('ts_name');
            $table->string('barcode');
            $table->string('doc_type_name');
            $table->integer('quantity');
            $table->decimal('retail_price');
            $table->decimal('retail_amount');
            $table->integer('sale_percent');
            $table->decimal('commission_percent');
            $table->string('office_name');
            $table->string('supplier_oper_name');
            $table->dateTime('order_dt');
            $table->dateTime('sale_dt');
            $table->dateTime('rr_dt');
            $table->integer('shk_id');
            $table->decimal('retail_price_withdisc_rub');
            $table->integer('delivery_amount');
            $table->integer('return_amount');
            $table->decimal('delivry_rub');
            $table->string('gi_box_type_name');
            $table->decimal('product_discount_for_report');
            $table->integer('supplier_promo');
            $table->integer('rid');
            $table->decimal('ppvz_ssp_prc');
            $table->decimal('ppvz_kvw_prc_base');
            $table->decimal('ppvz_kvw_prc');
            $table->decimal('ppvz_sales_commission');
            $table->decimal('ppvz_reward');
            $table->decimal('acquiring_fee');
            $table->string('acquiring_bank');
            $table->decimal('ppvz_vw');
            $table->decimal('ppvz_vw_nds');
            $table->integer('ppvz_office_id');
            $table->string('ppvz_office_name');
            $table->integer('ppvz_supplier_id');
            $table->string('ppvz_supplier_name');
            $table->string('ppvz_inn');
            $table->string('declaration_number');
            $table->string('bonus_type_name');
            $table->string('sticker_id');
            $table->string('site_country');
            $table->decimal('penalty');
            $table->decimal('additional_payment');
            $table->string('kiz');
            $table->string('srid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_detail_by_period');
    }
};
