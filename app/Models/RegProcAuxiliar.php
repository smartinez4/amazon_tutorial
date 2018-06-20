<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;




class RegProcAuxiliar extends Model
{

    protected $table = 'regprocauxiliar';
    protected $primaryKey = 'Codi_Proc';

    public function quirofan_in()
    {
        return $this->belongsTo('App\Models\Quirofan', 'Codi_proc_auxiliar_entrada', 'Codi_Proc');
    }

    public function quirofan_out()
    {
        return $this->belongsTo('App\Models\Quirofan', 'Codi_proc_auxiliar_sortida', 'Codi_Proc');
    }

}
