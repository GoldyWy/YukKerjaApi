<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lkeahlian extends Model
{
    protected $fillable = [
        'id', 'lowongan_id', 'keahlian_id', 'keahlian_nama', 'created_at','updated_at'
    ];

    public function lowongan(){
        return $this->belongsTo('App\Lowongan');
    }


}
