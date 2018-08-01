<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Patient;
use App\Models\Quirofan;
use App\Models\RegistrePre;
use App\Models\RegProcAuxiliar;
use App\Models\Urpa;
use App\Utilities\Transformers\InterventionTransformer;
use App\Utilities\Transformers\PatientTransformer;
use DB;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    protected $interventionTransformer;
    protected $patientTransformer;

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

    public function createIntervention(Request $request)
    {
        (new Intervention)->createIntervention($request);
    }

    public function deleteIntervention($codiProcedim)
    {
        (new Intervention)->deleteIntervention($codiProcedim);
    }

    public function updateIntervencio(Request $request, $id)
    {
        $array = $request->input('body');
        // $array = $request->all();
        foreach ($array as $key=>$value)
        {
            if (($key && $value && $id) != null)
            (new Intervention)->changeInterventionField($id, $key, $value);
        }
    }

}