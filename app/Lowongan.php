<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    protected $fillable = [
        'id', 'perusahaan_id', 'judul', 'deskripsi', 'requirement','waktu_kerja','range_gaji1','range_gaji2','lokasi','status','created_at','updated_at'
    ];

    public function lkeahlian(){
        return $this->hasMany('App\Lkeahlian');
    }

    public function pendaftar(){
        return $this->belongsTo('App\Pendaftar');
    }

    public function perusahaan(){
        return $this->belongsTo('App\Perusahaan');
    }

    public function notifikasi(){
        return $this->hasMany('App\Notifikasi');
    }

}
