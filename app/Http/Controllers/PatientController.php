<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Patient;
use App\Utilities\Transformers\PatientTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    protected $patientTransformer;

    /**
     * PatientController constructor.
     * @param $patientTransformer
     */
    public function __construct(PatientTransformer $patientTransformer)
    {
        $this->patientTransformer = $patientTransformer;
    }

    public function retrievePatients($day)
    {
        $patients = Intervention::searchPatients($day)->get();

        if (!$patients) {
            return $this->RespondNotFound('No patients were found');
        }
        return $this->respondWithData('Query Successful', $this->patientTransformer->transformAllPatients($patients));

    }

    public function getBoxURPA()
    {
        $query_boxURPA = DB::table('variables')
            ->select('Duracio_unix')
            ->where('Tipus_var', '=', 'boxes_URPA')->get();

        if (!$query_boxURPA) {
            return $this->RespondNotFound('No patients were found');
        }
        return $this->respondWithData('Query Successful', $query_boxURPA = $query_boxURPA->toArray());
        //dd($query_boxURPA);
    }
}
