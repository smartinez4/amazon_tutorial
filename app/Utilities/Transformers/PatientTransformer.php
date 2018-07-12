<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 09/07/2018
 * Time: 16:12
 */

namespace App\Utilities\Transformers;

class PatientTransformer extends Transformer
{
    public function transform($pacient)
    {
        //dd($pacient['Codi_NHC_Pacient']);
        return [
            'Codi_NHC_Pacient' => substr($pacient['Codi_NHC_Pacient'], -4),

            'Nom_Pacient' => $pacient['pacient']['Nom_Pacient'],

            'T_entrada_URPA_H6' => $pacient['urpa']['T_entrada_URPA_H6'],
            'T_sortida_URPA_H8' => $pacient['urpa']['T_sortida_URPA_H8']
        ];
    }

    public function transformAllPatients($pacients)
    {
        $pacients = $pacients->toArray();

        foreach ($pacients as &$pacient) {
            $pacient = $this->transform($pacient);
        }
        // dd($pacients);
        return $pacients;
    }
}