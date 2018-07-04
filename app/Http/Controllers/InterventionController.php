<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Pacient;
use App\Models\Quirofan;
use App\Models\RegistrePre;
use App\Models\RegProcAuxiliar;
use App\Models\Urpa;
use App\Utilities\Transformers\InterventionTransformer;
use DB;

class InterventionController extends Controller
{
    protected $interventionTransformer;

    function __construct(InterventionTransformer $interventionTransformer)
    {
        $this->interventionTransformer = $interventionTransformer;
    }


    public function index()
    {
        $intervencions = Intervention::withAuxiliar()->paginate(10);

        if (!$intervencions) {
            return $this->RespondNotFound('Could not get Interventions list');
        }

        return $this->respondWithData('Query Successful', $this->interventionTransformer->transformAll($intervencions));

    }

    public function read($id)
    {

        $intervencio = Intervention::getIntervention($id)->with('quirofan')->first();

        if (!$intervencio) {
            return $this->respondNotFound('Intervention does not exist');
        }

        return $this->respondWithData('Query Successful', $this->interventionTransformer->transform($intervencio));
    }

    public function allPatientsRelatives($day, $centre)
    {
        $intervencions = Intervention::searchInterventions($day, $centre);


        if (!$intervencions) {
            return $this->RespondNotFound('Could not get Interventions list');
        }

        //\Dev::log('hola');
        //$intervencions = $this->applyChecksTimestamps($intervencions);

        return $this->respondWithData('Query Successful', $this->interventionTransformer->transformAll($intervencions));
    }

    public function searchCodiProc($query, $code)
    {
    }

    public function createPatient($idQuirofan, $startTime)
    {
        /**
         * Exemple URL per creacio de pacient fals: http://127.0.0.1:8000/create_patient/QUI10/2018-07-01
         */

        //$query = (new Intervention)->insertPatient($day);

        $codi_NHC = rand(0, 9999999);
        $startTime = date('Y-m-d H:i:s', strtotime($startTime . ' + ' . rand(0, 60) . ' minutes' . ' + ' . rand(0, 23) . ' hours'));

        $auxiliar_in = new RegProcAuxiliar();
        $auxiliar_in->Temps_creacio = $startTime;
        $auxiliar_in->Tipus = 'A';
        $auxiliar_in->save();

        $quirofan = new Quirofan();
        $quirofan->idQuirofan = $idQuirofan;
        $quirofan->T_entrada_quirofan_H2 = $startTime;
//        $quirofan->T_entrada_quirofan_H2_Real = date('Y-m-d H:i:s', strtotime($startTime . ' + ' . rand(1, 30) . ' minutes')); // Codi_proc_auxiliar_entrada_Temps_fi?
        $quirofan->Codi_proc_auxiliar_entrada = $auxiliar_in->Codi_Proc;
//        $quirofan->Codi_proc_auxiliar_entrada_Temps_fi = RegProcAuxiliar::getCodiProc($auxiliar_in->Codi_Proc);
//        $quirofan->T_inici_cirugia_H3 = date('Y-m-d H:i:s', strtotime($startTime . ' + ' . rand(30, 60) . ' minutes'));
//        $quirofan->T_fi_cirugia_H4 = date('Y-m-d H:i:s', strtotime($quirofan->T_inici_cirugia_H3 . ' + ' . rand(2, 120) . ' minutes'));
//        $quirofan->T_sortida_Quirofan_H5 = date('Y-m-d H:i:s', strtotime($quirofan->T_fi_cirugia_H4 . ' + ' . rand(1, 3) . ' hours'));
//        $quirofan->T_sortida_Quirofan_H5_Real = date('Y-m-d H:i:s', strtotime($quirofan->T_sortida_Quirofan_H5 . ' + ' . rand(1, 60) . ' minutes'));
        $quirofan->save();

        $urpa = new Urpa();
//        $urpa->T_URPA_programat_min = rand(0, 60);
//        $urpa->T_URPA_programat_planning = rand(61, 120);
//        $urpa->T_entrada_URPA_H6 = date('Y-m-d H:i:s', strtotime($quirofan->T_sortida_Quirofan_H5 . ' + ' . rand(1, 3) . ' hours'));
//        $urpa->T_sortida_URPA_H8 = date('Y-m-d H:i:s', strtotime($quirofan->T_entrada_URPA_H6 . ' + ' . rand(1, 10) . ' hours'));
        $urpa->save();

        $registrePre = new RegistrePre();
        $registrePre->save();

        $pacient = new Pacient();
        $pacient->id_NHC_pacient = $codi_NHC;
        $pacient->Nom_Pacient = 'userTest1';
        $pacient->Sala_origen = 'sala';
        $pacient->save();

        /*$auxiliar_out = new RegProcAuxiliar();
        $auxiliar_out->Temps_creacio = $startTime;
        $auxiliar_out->Tipus = 'A';
        $auxiliar_out->save();*/

        $intervencio = new Intervention();
        $intervencio->Codi_NHC_Pacient = $codi_NHC;
        $intervencio->Data_hora_Intervencio = $startTime;
        $intervencio->Codi_RegQuir_interv = $quirofan->Codi_RegQuir;
        $intervencio->Codi_RegURPA_interv = $urpa->Codi_RegURPA;
        $intervencio->Codi_RegPRE_interv = $registrePre->Codi_RegPRE;
        $intervencio->Codi_nom_ciruja = '13501';
        $intervencio->Prestacion = 'O01322';
        $intervencio->Sala_origen = 'S404';
        $intervencio->Sala_desti = 'M040';
        $intervencio->Episodi = rand(0, 100000000);
        $intervencio->Servei = 'HCPB';
        $intervencio->serveis_id = rand(0, 99);

        $intervencio->save();
        //dd($quirofan);
    }
}