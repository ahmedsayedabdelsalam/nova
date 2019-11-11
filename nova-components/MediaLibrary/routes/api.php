<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

// Route::get('/endpoint', function (Request $request) {
//     //
// });

Route::group(['namespace' => 'Code95\MediaLibrary\Http\Controllers'], function () {
    Route::post('upload-media', 'MediaLibraryController@store');
    Route::delete('delete-media/{media}', 'MediaLibraryController@delete');
    Route::put('update-media/{media}', 'MediaLibraryController@update');
    Route::get('images', 'MediaLibraryController@mediaData');
});