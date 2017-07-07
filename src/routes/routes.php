<?php

use App\Http\Middleware as middleware;

Route::group(['namespace' => 'Mustaard\Metaphor\Controllers', 'prefix' => 'facebook/csv/loader/', 'middleware' => ['web','auth'] ], function() {
    Route::post('/', ['as' => 'loadcsv', 'uses' => 'LoadcsvController@convertCsv']);
    Route::get('/upload', ['as' => 'upload', 'uses' => 'LoadcsvController@upload']);
    Route::post('/toCrm', ['as' => 'toCrm', 'uses' => 'LoadcsvController@pushToCrm']);
});

Route::get('/test', function(){
    return 'Hello world';
});

Route::get('/loadcsv', function(){
   // $data =   Metaphor::importCsv();
    $data =   Metaphor::importCsvFile();






    return view('Metaphor::welcome', compact('data'));
});