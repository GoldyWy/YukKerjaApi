<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePekerjasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pekerjas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('nama_depan');
            $table->string('nama_belakang');
            $table->string('password');
            $table->string('jk');
            $table->string('nomor_telp');
            $table->string('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->bigInteger('gaji_harapan')->nullable();
            $table->string('lokasi_kerja')->nullable();
            $table->string('resume')->nullable();
            $table->timestamp('resume_updated_at')->nullable();
            $table->integer('status');
            $table->text('deskripsi');
            $table->string('token');
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
        Schema::dropIfExists('pekerjas');
    }
}
