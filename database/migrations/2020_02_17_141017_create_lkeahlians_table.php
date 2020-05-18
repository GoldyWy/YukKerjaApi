<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLkeahliansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lkeahlians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lowongan_id');
            $table->bigInteger('keahlian_id');
            $table->string('keahlian_nama');
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
        Schema::dropIfExists('lkeahlians');
    }
}
