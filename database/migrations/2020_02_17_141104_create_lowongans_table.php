<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLowongansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lowongans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('perusahaan_id');
            $table->string('judul');
            $table->text('deskripsi');
            $table->text('requirement');
            $table->string('waktu_kerja');
            $table->bigInteger('range_gaji1');
            $table->bigInteger('range_gaji2');
            $table->string('lokasi');
            $table->integer('status');
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
        Schema::dropIfExists('lowongans');
    }
}
