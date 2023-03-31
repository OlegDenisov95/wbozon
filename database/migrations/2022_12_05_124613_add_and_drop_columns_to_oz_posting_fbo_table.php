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
            $table->string('currency_code')->nullable();
            $table->double('commission_amount')->nullable();
            $table->integer('commission_percent')->nullable();
            $table->double('payout')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->double('old_price')->nullable();
            $table->double('total_discount_value')->nullable();
            $table->double('total_discount_percent')->nullable();
            $table->json('actions')->nullable();
            $table->json('picking')->nullable();
            $table->string('client_price')->nullable();
            $table->string('cluster_from')->nullable();
            $table->string('cluster_to')->nullable();

            $table->dropColumn('products');
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
            $table->dropColumn('currency_code');
            $table->dropColumn('commission_amount');
            $table->dropColumn('commission_percent');
            $table->dropColumn('payout');
            $table->dropColumn('product_id');
            $table->dropColumn('old_price');
            $table->dropColumn('total_discount_value');
            $table->dropColumn('total_discount_percent');
            $table->dropColumn('actions');
            $table->dropColumn('picking');
            $table->dropColumn('client_price');
            $table->dropColumn('cluster_from');
            $table->dropColumn('cluster_to');

            $table->json('products')->nullable();
        });
    }
};
