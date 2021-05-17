<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_purchase', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('offer_id');
            $table->foreignId('purchase_id')->nullable();
            $table->enum('status',['penawaran', 'pembelian','selesai']);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('done_at')->nullable();

            $table->foreign('purchase_id')->references('id')->on('purchases');
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
        Schema::dropIfExists('offer_purchase', function (Blueprint $table){
            $table->dropForeign('purchase_id');
            $table->dropForeign('offer_id');
        });
    }
}
