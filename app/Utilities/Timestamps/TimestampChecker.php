<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 26/06/2018
 * Time: 9:11
 */

namespace App\Utilities\Timestamps;


class TimestampChecker
{
    private $entradaQuirofanH2Real, $inici_cirugia_H3, $fi_cirugia_H4, $sortidaQuirofanH5Real, $entradaQuirofanH2, $sortidaQuirofanH5, $codiProcAuxiliarEntradaTempsFi, $codiProcAuxiliarSortidaTempsCreacio, $entradaURPAH6, $sortidaURPAH8, $codiProcAuxiliarSortidaTempsFi;

    /**
     * TimestampChecker constructor.
     * @param $intervencio
     */
    public function __construct($intervencio)
    {
        $this->entradaQuirofanH2Real = $intervencio['T_entrada_quirofan_H2_Real'];
        $this->inici_cirugia_H3 = $intervencio['T_inici_cirugia_H3'];
        $this->fi_cirugia_H4 = $intervencio['T_fi_cirugia_H4'];
        $this->sortidaQuirofanH5Real = $intervencio['T_sortida_Quirofan_H5_Real'];
        //$infoFamilies = $intervencio['data']->{'T_info_families'};
        //$restantQuirofanMin = $intervencio['data']->{'T_restant_quirofan_min'};
        $this->entradaQuirofanH2 = $intervencio['T_entrada_quirofan_H2'];
        $this->sortidaQuirofanH5 = $intervencio['T_sortida_Quirofan_H5'];
        $this->codiProcAuxiliarEntradaTempsFi = $intervencio['Codi_proc_auxiliar_entrada_Temps_fi'];
        $this->codiProcAuxiliarSortidaTempsCreacio = $intervencio['Codi_proc_auxiliar_sortida_Temps_creacio'];
        $this->entradaURPAH6 = $intervencio['T_entrada_URPA_H6'];
        $this->sortidaURPAH8 = $intervencio['T_sortida_URPA_H8'];
        $this->codiProcAuxiliarSortidaTempsFi = $intervencio['Codi_proc_auxiliar_sortida_Temps_fi'];

        /*$entradaQuirofanH2Real = Carbon::createFromFormat('Y-m-d H:i:s', $entradaQuirofanH2Real);
        $codiProcAuxiliarEntradaTempsFi = Carbon::createFromFormat('Y-m-d H:i:s', $codiProcAuxiliarEntradaTempsFi);
        $inici_cirugia_H3 = Carbon::createFromFormat('Y-m-d H:i:s', $inici_cirugia_H3);
        $fi_cirugia_H4 = Carbon::createFromFormat('Y-m-d H:i:s', $fi_cirugia_H4);
        $sortidaQuirofanH5Real = Carbon::createFromFormat('Y-m-d H:i:s', $sortidaQuirofanH5Real);
        //$infoFamilies= Carbon::createFromFormat('Y-m-d H:i:s', $infoFamilies);
        //$restantQuirofanMin = Carbon::createFromFormat('Y-m-d H:i:s', $restantQuirofanMin);
        $entradaQuirofanH2 = Carbon::createFromFormat('Y-m-d H:i:s', $entradaQuirofanH2);
        $sortidaQuirofanH5 = Carbon::createFromFormat('Y-m-d H:i:s', $sortidaQuirofanH5);
        $codiProcAuxiliarSortidaTempsCreacio = Carbon::createFromFormat('Y-m-d H:i:s', $codiProcAuxiliarSortidaTempsCreacio);
        $entradaURPAH6 = Carbon::createFromFormat('Y-m-d H:i:s', $entradaURPAH6);
        $sortidaURPAH8 = Carbon::createFromFormat('Y-m-d H:i:s', $sortidaURPAH8);
        $codiProcAuxiliarSortidaTempsFi = Carbon::createFromFormat('Y-m-d H:i:s', $codiProcAuxiliarSortidaTempsFi);*/


    }

    public function checkFiCirugia()
    {
        // En el cas de que hagin oblidat el fi de cirugia
        if ($this->fi_cirugia_H4 == null && $this->sortidaQuirofanH5Real != null) {
            $this->fi_cirugia_H4 = $this->sortidaQuirofanH5Real;
        }
        return $this->fi_cirugia_H4;
    }

    public function checkBaixatQuirofan()
    {
        // Mirem si el pacient ha estat baixat a quirofan
        if ($this->codiProcAuxiliarEntradaTempsFi == null && $this->entradaQuirofanH2Real != null) {
            $this->codiProcAuxiliarEntradaTempsFi = $this->entradaQuirofanH2Real;
        }
        return $this->codiProcAuxiliarEntradaTempsFi;
    }

    public function checkSeguentPacient()
    {
        // Mirem "Dirijase" si s'ha demanat el seguent pacient
        if ($this->codiProcAuxiliarSortidaTempsCreacio == null) {
            $this->codiProcAuxiliarSortidaTempsFi = null;
        }
        return $this->codiProcAuxiliarSortidaTempsFi;
    }

    public function checkIniciCirugia()
    {
        // Si a cirugia no registren l'inici de la cirugia, s'agafa el temps d'entrada a quirofan
        if ($this->inici_cirugia_H3 == null && $this->sortidaQuirofanH5Real != null) {
            $this->inici_cirugia_H3 = $this->entradaQuirofanH2Real;
        }
        return $this->inici_cirugia_H3;
    }

    public function testURPA()
    {
        // Metode de test per comprovar que funciona tot aixo
        if ($this->entradaURPAH6 == null && $this->entradaQuirofanH2Real != null) {
            $this->entradaURPAH6 = $this->entradaQuirofanH2Real;
        }
        return $this->entradaURPAH6;
    }




}