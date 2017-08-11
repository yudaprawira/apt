<?php
$info = readModulInfo(__DIR__.'/../module.json');

//BACKEND
Route::group(['middleware' => 'web', 'prefix' => config('app.backend').'/'.$info['alias'], 'namespace' => 'Modules\PustakaGambar\Http\Controllers'], function()
{
    Route::get('/', 'BeController@index');
    Route::get('/trash', 'BeController@trash');
    Route::get('/add', 'BeController@form');
    Route::get('/lookup', 'BeController@lookup');
    Route::get('/edit/{id}', 'BeController@form')->where('id', '[0-9]+');
    Route::get('/restore/{id}', 'BeController@restore')->where('id', '[0-9]+');
    Route::get('/delete/{id}', 'BeController@delete')->where('id', '[0-9]+');
    Route::post('/save', 'BeController@save'); 
});