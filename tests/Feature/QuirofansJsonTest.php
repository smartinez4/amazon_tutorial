<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuirofansJsonTest extends TestCase
{
    public function testJsonStructure()
    {
        $response = $this->json('GET', '/info_per_quirofan/day/2016-04-14');

        $response->assertJsonStructure([
            'data' => [
                'QUI1' => [
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
                        'Codi_proc_auxiliar_sortida_Temps_fi',
                        'T_entrada_expected',
                        'T_sortida_expected'
                    ]
                ]
            ],
            'message',
            'status_code'
        ])->assertStatus(200);
    }
}
