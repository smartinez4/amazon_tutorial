<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    public function getInterventions($query) {
        return $query->where('codi_procedim', 1679);
    }
}
