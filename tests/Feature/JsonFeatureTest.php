<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 25/06/2018
 * Time: 8:31
 */
class JsonFeatureTest extends TestCase
{

    public function testJsonStructure()
    {
        $response = $this->json('GET', 'http://127.0.0.1:8000/all_patients_relatives/day/2016-09-08/centre/HCPB?page=2');

        //$response->dump();

        $response->assertJsonStructure([
            'data' => [
                'current_page',
                'data' => [
                    '0' => [
                        'episodi_5',
                        'missatgeFam',
                        'duracio_machine_learning',
                        'T_entrada_quirofan_H2_Real',
                        'T_inici_cirugia_H3',
                        'T_fi_cirugia_H4',
                        'T_sortida_Quirofan_H5_Real',
                        'T_info_families',
                        'idQuirofan',
                        'T_restant_quirofan_min',
                        'T_entrada_quirofan_H2',
                        'T_sortida_Quirofan_H5',
                        'Codi_proc_auxiliar_entrada_Temps_fi',
                        'Codi_proc_auxiliar_sortida_Temps_creacio',
                        'T_entrada_URPA_H6',
                        'T_sortida_URPA_H8',
                        'Codi_proc_auxiliar_sortida_Temps_fi'
                    ]
                ]
            ],
            'message',
            'status_code'
        ])
            ->assertStatus(200);

        $data_all = $response->getData();

        $entradaQuirofanH2Real = $data_all->{'data'}->{'data'}[0]->{'T_entrada_quirofan_H2_Real'};
        $inici_cirugia_H3 = $data_all->{'data'}->{'data'}[0]->{'T_inici_cirugia_H3'};
        $fi_cirugia_H4 = $data_all->{'data'}->{'data'}[0]->{'T_fi_cirugia_H4'};
        $sortidaQuirofanH5Real = $data_all->{'data'}->{'data'}[0]->{'T_sortida_Quirofan_H5_Real'};
        //$infoFamilies = $data_all->{'data'}->{'data'}[0]->{'T_info_families'};
        //$restantQuirofanMin = $data_all->{'data'}->{'data'}[0]->{'T_restant_quirofan_min'};
        $entradaQuirofanH2 = $data_all->{'data'}->{'data'}[0]->{'T_entrada_quirofan_H2'};
        $sortidaQuirofanH5 = $data_all->{'data'}->{'data'}[0]->{'T_sortida_Quirofan_H5'};
        $codiProcAuxiliarEntradaTempsFi= $data_all->{'data'}->{'data'}[0]->{'Codi_proc_auxiliar_entrada_Temps_fi'};
        $codiProcAuxiliarSortidaTempsCreacio= $data_all->{'data'}->{'data'}[0]->{'Codi_proc_auxiliar_sortida_Temps_creacio'};
        $entradaURPAH6 = $data_all->{'data'}->{'data'}[0]->{'T_entrada_URPA_H6'};
        $sortidaURPAH8 = $data_all->{'data'}->{'data'}[0]->{'T_sortida_URPA_H8'};
        $codiProcAuxiliarSortidaTempsFi= $data_all->{'data'}->{'data'}[0]->{'Codi_proc_auxiliar_sortida_Temps_fi'};

        //= $data_all->{'data'}->{'data'}[0]->{''};

        $entradaQuirofanH2Real = Carbon::createFromFormat('Y-m-d H:i:s', $entradaQuirofanH2Real);
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
        $codiProcAuxiliarSortidaTempsFi = Carbon::createFromFormat('Y-m-d H:i:s', $codiProcAuxiliarSortidaTempsFi);

        // En el cas de que hagin oblidat el fi de cirugia
        if ($fi_cirugia_H4 = null && $sortidaQuirofanH5Real !=null)
        {
            $fi_cirugia_H4 = $sortidaQuirofanH5Real;
        }

        // Mirem si el pacient ha estat baixat a quirofan
        if ($codiProcAuxiliarEntradaTempsFi == null && $entradaQuirofanH2Real != null)
        {
            $codiProcAuxiliarEntradaTempsFi = $entradaQuirofanH2Real;
        }

        // Mirem "Dirijase" si s'ha demanat el seguent pacient
        if ($codiProcAuxiliarSortidaTempsCreacio == null)
        {
            $codiProcAuxiliarSortidaTempsFi = null;
        }

        // Si el camiller s'oblida de registrar l'entrada a quirofan, s'agafa la d'inici de cirugia
        if ($entradaQuirofanH2Real == null && $inici_cirugia_H3 != null)
        {
            $entradaQuirofanH2Real = $inici_cirugia_H3;
        }

        // Si a cirugia no registren l'inici de la cirugia, s'agafa el temps d'entrada a quirofan
        if ($inici_cirugia_H3 == null && $sortidaQuirofanH5Real != null)
        {
            $inici_cirugia_H3 = $entradaQuirofanH2Real;
        }

        //$response->assertTrue($entradaQuirofanH2Real<$inici_cirugia_H3);

        dd($fi_cirugia_H4);


    }

}