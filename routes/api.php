<?php

use App\Person;
use Illuminate\Http\Request;

//Route::get('/person/{person}', 'PersonController@show');
Route::apiResource('/person', 'PersonController');