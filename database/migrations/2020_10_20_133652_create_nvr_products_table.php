<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvrProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nvr_products', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedBigInteger("product_id");
            // $table->string("model_barang");
            $table->string("in_bandwidth");
            $table->string("out_bandwidth");
            $table->string("channel_dicoding");
            $table->enum('four_k_support',['ya','tidak']);
            $table->string("sata_int");
            $table->string("network_int");
            $table->string("e_sata");
            $table->string("poe_ports");
            $table->string("hdmi_out");
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
        Schema::dropIfExists('nvr_products');
    }
}
