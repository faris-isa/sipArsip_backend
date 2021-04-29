<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("product_manufacture_id");
            $table->text("spesifikasi");
            $table->string("foto_produk");
            $table->timestamps();

            $table->foreign('product_manufacture_id')->references('id')->on('product_manufacture');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_detail', function (Blueprint $table){
            $table->dropForeign('product_manufacture_id');
        });
    }
}
