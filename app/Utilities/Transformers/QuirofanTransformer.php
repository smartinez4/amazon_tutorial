<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 27/06/2018
 * Time: 10:03
 */

namespace App\Utilities\Transformers;

use App\Utilities\Timestamps\TimestampChecker;

class QuirofanTransformer extends Transformer
{
    public $turnover = 30;

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

            'Codi_proc_auxiliar_sortida_Temps_fi' => $intervencio['quirofan']['auxiliar_out']['Temps_fi'],

            'T_entrada_expected' => $intervencio['T_entrada_expected'],
            'T_sortida_expected' => $intervencio['T_sortida_expected']


        ];
    }

    public function transformAllInfoQuirofans($intervencions)
    {
        $intervencions = $intervencions->toArray();
        $pacientsQuirofan = [];

        foreach ($intervencions as &$intervencio) {

            $intervencio['T_entrada_expected'] = '';
            $intervencio['T_sortida_expected'] = '';
            $this->entradaSortidaExpected($intervencio);

            $pacientsQuirofan[$intervencio['quirofan']['idQuirofan']][] = $this->transform($intervencio);
        }
        return $pacientsQuirofan;
    }

    private function durationBetweenDatesInMin($date_before, $date_after)
    {
        $date_before = strtotime($date_before) / 60; //in minutes
        $date_after = strtotime($date_after) / 60;
        return $date_after - $date_before;
    }

    private function entradaSortidaExpected($intervencio)
    {
        $delay = 0;
        $delay_start = '';
        if ($intervencio['quirofan']['T_entrada_quirofan_H2_Real'] != null && $intervencio['quirofan']['T_sortida_Quirofan_H5_Real'] != null) {
            //inicialitzem el delay amb l'ultim pacient acabat.
            $delay_start = $intervencio['quirofan']['T_sortida_Quirofan_H5_Real'];
            $delay = 0;
        }
        if ($intervencio['quirofan']['T_entrada_quirofan_H2_Real'] != null && $intervencio['quirofan']['T_sortida_Quirofan_H5_Real'] == null) {
            //tornem a incialitzar el delay en el cas de que hi hagi un pacient en curs.
            $delay = $intervencio['quirofan']['T_restant_quirofan_min'];
            $delay_start = $intervencio['quirofan']['T_entrada_quirofan_H2_Real'];
        }
        if ($intervencio['quirofan']['T_entrada_quirofan_H2_Real'] == null && $intervencio['quirofan']['T_sortida_Quirofan_H5_Real'] == null) {
            if (strlen($delay_start) == 0) {
                //estem considerant el cas de que no s'ha fet cap pacient.
                $delay_start = date('Y-m-d H:i:s');
            }
            $intervencio['T_entrada_expected'] = date('Y-m-d H:i:s', strtotime($delay_start) + $delay * 60 + $this->turnover * 60); //li sumem el delay total i turnover de l'anterior.
            $duration = $this->durationBetweenDatesInMin($intervencio['quirofan']['T_entrada_quirofan_H2'], $intervencio['quirofan']['T_sortida_Quirofan_H5']);
            $intervencio['T_sortida_expected'] = date('Y-m-d H:i:s', strtotime($delay_start) + $delay * 60 + $this->turnover * 60 + $duration * 60); //
            $delay += $this->turnover + $duration;
        }
        return $intervencio;
    }

}