<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id');
            $table->foreignId('offer_id');
            $table->integer('qty');
            $table->integer('harga');
            // $table->integer('disc');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('offer_id')->references('id')->on('offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_product', function (Blueprint $table){
            $table->dropForeign('product_id');
            $table->dropForeign('offer_id');
        });
    }
}
