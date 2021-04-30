<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("product_id");
            $table->foreignId("product_manufacture_id");
            $table->text("spesifikasi");
            $table->string("foto_produk");
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_manufacture_id')->references('id')->on('product_manufactures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_details', function (Blueprint $table){
            $table->dropForeign('product_manufacture_id');
        });
    }
}
