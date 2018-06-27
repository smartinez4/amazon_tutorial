<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 19/06/2018
 * Time: 14:25
 */

namespace App\Utilities\Transformers;

use App\Utilities\Timestamps\TimestampChecker;

class InterventionTransformer extends Transformer
{

    public function transform($intervencio)
    {

        return [
            'episodi_5' => substr($intervencio['Episodi'], -5),
            'missatgeFam' => $intervencio['missatgeFam'],
            'duracio_machine_learning' => $intervencio['duracio_machine_learning'],

            'T_entrada_quirofan_H2_Real' => $intervencio['quirofan']['T_entrada_quirofan_H2_Real'],
            'T_inici_cirugia_H3' => $intervencio['quirofan']['T_inici_cirugia_H3'],
            'T_fi_cirugia_H4' => $intervencio['quirofan']['T_fi_cirugia_H4'],
            'T_sortida_Quirofan_H5_Real' => $intervencio['quirofan']['T_sortida_Quirofan_H5_Real'],
            'T_info_families' => $intervencio['quirofan']['T_info_families'],
            'idQuirofan' => $intervencio['quirofan']['idQuirofan'],
            'T_restant_quirofan_min' => $intervencio['quirofan']['T_restant_quirofan_min'],
            'T_entrada_quirofan_H2' => $intervencio['quirofan']['T_entrada_quirofan_H2'],
            'T_sortida_Quirofan_H5' => $intervencio['quirofan']['T_sortida_Quirofan_H5'],

            'Codi_proc_auxiliar_entrada_Temps_fi' => $intervencio['quirofan']['auxiliar_in']['Temps_fi'],
            'Codi_proc_auxiliar_sortida_Temps_creacio' => $intervencio['quirofan']['auxiliar_in']['Temps_creacio'],

            'T_entrada_URPA_H6' => $intervencio['urpa']['T_entrada_URPA_H6'],
            'T_sortida_URPA_H8' => $intervencio['urpa']['T_sortida_URPA_H8'],

            'Codi_proc_auxiliar_sortida_Temps_fi' => $intervencio['quirofan']['auxiliar_out']['Temps_fi']
        ];

    }

    public function transformAll($intervencions)
    {
        $intervencions = $intervencions->toArray();

        foreach ($intervencions['data'] as &$intervencio) {

            $intervencio = $this->transform($intervencio);
            $intervencio = $this->applyChecksTimestamps($intervencio);
        }

        return $intervencions;
    }

    public function applyChecksTimestamps($intervencio)
    {
        $timestampChecker = new TimestampChecker($intervencio);
        $intervencio['T_fi_cirugia_H4'] = $timestampChecker->checkFiCirugia();
        $intervencio['Codi_proc_auxiliar_entrada_Temps_fi'] = $timestampChecker->checkBaixatQuirofan();
        $intervencio['Codi_proc_auxiliar_sortida_Temps_fi'] = $timestampChecker->checkSeguentPacient();
        $intervencio['T_inici_cirugia_H3'] = $timestampChecker->checkIniciCirugia();

        //test
        //$intervencio['T_entrada_URPA_H6'] = $timestampChecker->testURPA();


        return $intervencio;
    }


}