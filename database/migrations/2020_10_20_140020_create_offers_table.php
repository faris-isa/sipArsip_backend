<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->unsignedBigInteger("offer_detail_id");
            $table->string('nama_pembeli','50');
            $table->integer('harga_total');
            $table->enum("status",["accept", "decline", "not decided"]);
            $table->timestamps();

            // $table->foreign('offer_detail_id')->references('id')->on('offer_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
