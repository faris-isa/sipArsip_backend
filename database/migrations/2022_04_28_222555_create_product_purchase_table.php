<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_purchase', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id');
            $table->foreignId('purchase_id');
            $table->string('serial_number');
            $table->date('tanggal_beli');
            $table->integer('masa_garansi');
            $table->date('tanggal_selesai');
            $table->foreignId('purchase_location_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('purchase_location_id')->references('id')->on('purchase_locations');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_product', function (Blueprint $table){
            $table->dropForeign('product_id');
            $table->dropForeign('purchase_id');
        });
    }
}
