<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keahlian extends Model
{
    protected $fillable = [
        'id', 'nama', 'created_at', 'updated_at'
    ];
}
