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


// INTERVENCIONS
Route::get('/intervencions', 'InterventionController@index');
Route::get('/intervencions/{intervencio}', 'InterventionController@read');

// QUIROFANS
Route::get('/quirofans', 'QuirofanController@index');

// URPA
Route::get('/urpa', 'UrpaController@index');