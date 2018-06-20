<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{

    protected $table = 'intervencio';
    protected $primaryKey = 'Codi_procedim';

    public function scopeGetIntervention($query, $id) {
        return $query->where('Codi_procedim', $id);
    }

    public function  quirofan()
    {
        return $this->hasOne('App\Models\Quirofan', 'Codi_RegQuir', 'Codi_RegQuir_interv');
    }

    public function urpa()
    {
        return $this->hasOne('App\Models\Urpa', 'Codi_RegURPA', 'Codi_RegURPA_interv');
    }

    public function getidQuirofanAttribute($value)
    {
        $this->attributes['idQuirofan'] = strtolower($value);
        //return strtolower($value);
    }

}