<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $fillable = [
        'id', 'email', 'nama','password', 'nomor_telp', 'alamat','foto','informasi','created_at','updated_at'
    ];

    
    public function pendaftar(){
        return $this->belongsTo('App\Pendaftar');
    }

    public function notifikasi(){
        return $this->hasMany('App\Notifikasi');
    }

    public function lowongan(){
        return $this->belongsTo('App\Lowongan');
    }


}
