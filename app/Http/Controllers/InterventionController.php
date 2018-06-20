<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Quirofan;
use App\Models\Urpa;
use App\Utilities\Transformers\InterventionTransformer;
use DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Model;


class InterventionController extends Controller
{
    /**
     * @var Utilities\Transformers\InterventionTransformer
     */

    protected  $interventionTransformer;


    function __construct(InterventionTransformer $interventionTransformer)
    {
        $this->interventionTransformer = $interventionTransformer;
    }


    public function index()
    {
        return false;

        $intervencions = Intervention::with('quirofan')->get();
        //$quirofan = Quirofan::all();


        return Response::json([
            'data' => $this->interventionTransformer->transformCollection($intervencions->all())

        ]);

    }

    public function read($id)
    {
        $intervencio = Intervention::getIntervention($id)->with('quirofan')->first();

        return Response::json([
            'data' => $this->interventionTransformer->transform($intervencio)
        ]);
    }

    /*public function show(Intervention $intervencio)
    {
        return view('intervencions.show', compact('intervencio'));
    }*/

}
