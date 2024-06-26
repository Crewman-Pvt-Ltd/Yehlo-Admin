<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_promotion', function (Blueprint $table) {
            $table->id();
            $table->string('main_heading', 222)->nullable();
            $table->string('title', 222)->nullable();
            $table->string('subtitle', 222)->nullable();
            $table->string('image', 222)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_promotion');
    }
}
