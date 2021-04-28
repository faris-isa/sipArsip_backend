<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoeswtProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poeswt_products', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedBigInteger("product_id");
            // $table->string("model_produk");
            $table->text("deskripsi_produk");
            $table->string("foto_produk");
            $table->timestamps();

            // $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poeswt_products');
    }
}
