<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::any('/family/add_member',                        'Family\FamilyController@add_member');
Route::any('/family/load_family_list',                  'Family\FamilyController@load_family_list');
Route::any('/family/get_info',                          'Family\FamilyController@get_info');
Route::any('/family/delete',                            'Family\FamilyController@delete');
Route::any('/family/edit',                              'Family\FamilyController@edit');
Route::any('/family/update',                            'Family\FamilyController@update');
