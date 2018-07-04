<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pacient extends Model
{
    protected $table = 'pacient';
    protected $primaryKey = 'id_NHC_pacient';

    public $timestamps = false;

    public function intervention()
    {
        return $this->belongsTo('App\Models\Intervention', 'id_NHC_pacient', 'Codi_NHC_Pacient');
    }
}
