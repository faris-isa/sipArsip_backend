<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpcamProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ipcam_products', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedBigInteger("product_id");
            // $table->string("model_produk");
            $table->string("max_resolution");
            $table->string("lens");
            $table->string("wdr");
            $table->string("form_factor");
            $table->string("protection");
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
        Schema::dropIfExists('ipcam_products');
    }
}
