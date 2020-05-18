<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    protected $fillable = [
        'id','pekerja_id','perusahaan_id','lowongan_id','status','created_at','updated_at'
    ];

    public function pekerja(){
        return $this->belongsTo('App\Pekerja');
    }

    public function lowongan(){
        return $this->belongsTo('App\Lowongan');
    }

    public function perusahaan(){
        return $this->belongsTo('App\Perusahaan');
    }

}
