<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePkeahliansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pkeahlians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pekerja_id');
            $table->bigInteger('keahlian_id');
            $table->string('keahlian_nama');
            $table->string('tingkat');
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
        Schema::dropIfExists('pkeahlians');
    }
}
