<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements("id");
            // $table->string("nama_produk");
            $table->string("model_produk");
            $table->foreignId("product_type_id");
            // $table->enum("type_products",["poeswitch", "nvr", "ipcam"]);
            $table->enum("status",["ongoing", "deprecated"]);
            $table->integer("harga_satuan");
            $table->timestamps();

            $table->foreign('product_type_id')->references('id')->on('product_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
