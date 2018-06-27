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

        if (str_contains(url()->current(), 'localhost') || str_contains(url()->current(), '127.0.0.1'))
        {
            $day = '2016-04-13';
        }
        else
        {
            $day =  date('Y-m-d');
        }

        $intervencions = Intervention::searchQuirofans($day)->get();



        if (!$intervencions) {
            return $this->RespondNotFound('No patients were found');
        }
        return $this->respondWithData('Query Successful', $this->quirofanTransformer->transformAllInfoQuirofans($intervencions));
    }
}
