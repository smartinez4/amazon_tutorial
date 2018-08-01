<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Urpa extends Model
{
    protected $table = 'RegistreURPA';
    protected $primaryKey = 'Codi_RegURPA';

    public $timestamps = false;

    public function intervention()
    {
        return $this->belongsTo('App\Models\Intervention', 'Codi_RegURPA', 'Codi_RegURPA_interv');
    }
//TODO: a la query with camillers i registreurpa
}
