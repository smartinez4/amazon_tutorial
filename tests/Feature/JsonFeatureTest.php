<?php

use Tests\TestCase;

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
        $response = $this->json('GET', '/all_patients_relatives/day/2016-09-08/centre/HCPB?page=2');

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
        ])->assertStatus(200);
    }
}