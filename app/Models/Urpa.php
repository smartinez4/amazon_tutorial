<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Urpa extends Model
{
    protected $table = 'registreurpa';
    protected $primaryKey = 'Codi_RegURPA';

    public function intervention()
    {
        return $this->belongsTo('App\Models\Intervention', 'Codi_RegURPA', 'Codi_RegURPA_interv');
    }

}
