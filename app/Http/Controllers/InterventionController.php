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

        return $this->respondWithData('Query Successful', $this->interventionTransformer->transformAll($intervencions));
    }

    public function createIntervention($idQuirofan, $startTime)
    {
        (new Intervention)->createIntervention($idQuirofan, $startTime);
    }

    public function deleteIntervention($codiProcedim)
    {
        (new Intervention)->deleteIntervention($codiProcedim);
    }

}