<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returndiamond', function (Blueprint $table) {
            $table->bigIncrements('return_id');
            $table->bigInteger('certificate_no');
            $table->dateTime('purchase_date');
            $table->dateTime('return_initiated_date');
            $table->dateTime('return_date');
            $table->integer('return_paid_amount');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('returndiamond');
    }
};
