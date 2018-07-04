<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrePre extends Model
{
    protected $table = 'RegistrePRE';
    protected $primaryKey = 'Codi_RegPRE';

    public $timestamps = false;

    public function intervention()
    {
        return $this->belongsTo('App\Models\Intervention', 'CodiRegPRE', 'Codi_RegPRE_interv');
    }

}
