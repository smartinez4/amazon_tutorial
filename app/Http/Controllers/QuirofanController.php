<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Utilities\Transformers\QuirofanTransformer;

class QuirofanController extends Controller
{
    protected $quirofanTransformer;

    function __construct(QuirofanTransformer $quirofanTransformer)
    {
        $this->quirofanTransformer = $quirofanTransformer;
    }

    public function infoQuirofans($day) {

        $intervencions = Intervention::searchQuirofans($day)->get();

        if (!$intervencions) {
            return $this->RespondNotFound('No patients were found');
        }
        return $this->respondWithData('Query Successful', $this->quirofanTransformer->transformAllInfoQuirofans($intervencions));
    }
}
