<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{

    protected $table = 'intervencio';
    protected $primaryKey = 'Codi_procedim';

    public function scopeCheckCentre($query, $centre)
    {
        return $query->where('Servei', $centre);
    }

    public function scopeOfTheDay($query, $day)
    {
        return $query->where('Data_hora_Intervencio','like', $day.'%');
    }

    public function scopeGetIntervention($query, $id)
    {
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

    public static function searchInterventions($day, $centre)
    {
        $interventions = Intervention::withAuxiliar()->checkCentre($centre)->ofTheDay($day)->paginate(20);

        return $interventions;
    }

    public function scopeWithAuxiliar($query)
    {
        return $query->with(['quirofan.auxiliar_in', 'quirofan.auxiliar_out', 'urpa']);
    }

    public static function searchQuirofans($day)
    {
        $intervencions = Intervention::withAuxiliar()->ofTheDay($day);

        return $intervencions;
    }

    /*public function getidQuirofanAttribute($value)
    {
        $this->attributes['idQuirofan'] = strtolower($value);
        //return strtolower($value);
    }*/

}