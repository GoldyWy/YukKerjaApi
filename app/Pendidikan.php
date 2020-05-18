<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    protected $fillable = [
        'id', 'pekerja_id', 'institusi', 'bulan_wisuda', 'tahun_wisuda','kualifikasi','jurusan','nilai_akhir','created_at','updated_at'
    ];

    public function pekerja(){
        return $this->belongsTo('App\Pekerja');
    }
}
