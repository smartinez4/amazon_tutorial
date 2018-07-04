<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{

    protected $table = 'intervencio';
    protected $primaryKey = 'Codi_procedim';

    protected $fillable = ['Codi_nom_cirujia', 'updated_at', 'created_at'];
    public $timestamps = false;

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

    public function registrePre()
    {
        return $this->hasOne('App\Models\RegistrePre', 'Codi_RegPRE', 'Codi_RegPRE_interv');
    }

    public function pacient()
    {
        return $this->hasOne('App\Models\Pacient', 'id_NHC_pacient', 'Codi_NHC_Pacient');
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

    /*public function insertPatient($day)
    {
        DB::table('intervencio')->insert(
            [ '' => '']
        );
    }*/


    /*public function getidQuirofanAttribute($value)
    {
        $this->attributes['idQuirofan'] = strtolower($value);
        //return strtolower($value);
    }*/

}