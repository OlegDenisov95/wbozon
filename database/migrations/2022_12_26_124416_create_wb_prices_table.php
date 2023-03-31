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
        Schema::create('wb_prices', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->unsignedBigInteger('nm_id');
            $table->float('price')->nullable();
            $table->integer('discount')->nullable();
            $table->float('promo_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wb_prices');
    }
};
