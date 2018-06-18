<?php

namespace App\Http\Controllers;

use App\Intervention;

class InterventionsController extends Controller
{
    public function index()
    {
        $intervencions = Intervention::all();

        return view('intervencions.index', compact('intervencions'));
    }
}
