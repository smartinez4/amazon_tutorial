<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quirofan extends Model
{
    protected $table = 'registrequirofan';
    protected $primaryKey = 'Codi_RegQuir';

    public $timestamps = false;

    public function intervention()
    {
        return $this->belongsTo('App\Models\Intervention', 'Codi_RegQuir', 'Codi_RegQuir_interv');
    }

    public function auxiliar_in()
    {
        return $this->hasOne('App\Models\RegProcAuxiliar', 'Codi_Proc', 'Codi_proc_auxiliar_entrada');
    }

    public function auxiliar_out()
    {
        return $this->hasOne('App\Models\RegProcAuxiliar', 'Codi_Proc', 'Codi_proc_auxiliar_sortida');
    }

}
