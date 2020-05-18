<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pkeahlian extends Model
{
    protected $fillable = [
        'id', 'pekerja_id', 'keahlian_id', 'keahlian_nama', 'tingkat', 'created_at', 'updated_at'
    ];

    public function pekerja(){
        return $this->hasMany('App\Pekerja');
    }

}
