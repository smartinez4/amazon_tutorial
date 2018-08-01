<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// INTERVENTIONS
Route::get('interventions', 'InterventionController@index');
// Route::get('interventions/{intervencio}', 'InterventionController@read');
Route::get('interventions/day/{day}/centre/{centre}', 'InterventionController@allPatientsRelatives');

// Create new fake intervention
Route::post('create_intervention', 'InterventionController@createIntervention');

// Update intervencio field
Route::put('intervention/{id}', 'InterventionController@updateIntervencio');

// Delete intervention
Route::delete('intervention/{Codi_procedim}', 'InterventionController@deleteIntervention');

// QUIROFANS
// Route::get('quirofans', 'QuirofanController@index');

// URPA
// Route::get('urpa', 'UrpaController@index');

// GET INFO QUIROFANS
// Route::get('info_per_quirofan/day/{day}', 'QuirofanController@infoQuirofans');
Route::get('quirofans/day/{day}', 'QuirofanController@infoQuirofans');

// RETRIEVE PATIENTS
Route::get('retrieve_patients/day/{day}/centre/{centre}', 'PatientController@retrievePatients');

// VARIABLES
Route::get('variables', 'PatientController@getBoxURPA');