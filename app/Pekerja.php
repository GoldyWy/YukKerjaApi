<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pekerja extends Model
{
    protected $fillable = [
        'id', 'email', 'nama_depan', 'nama_belakang', 'password','jk','nomor_telp','alamat','foto','gaji_harapan','lokasi_kerja','resume','resume_updated_at','status','deskripsi','token','created_at','updated_at'
    ];

    public function pendaftar(){
        return $this->hasMany('App\Pendaftar');
    }

    public function notifikasi(){
        return $this->hasMany('App\Notifikasi');
    }

    public function pendidikan(){
        return $this->hasMany('App\Pendidikan');
    }

    public function pkeahlian(){
        return $this->hasMany('App\Pkeahlian');
    }

}
