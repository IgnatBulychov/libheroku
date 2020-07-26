<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    
});

Route::get('allbooks', 'BookController@allBooks');
Route::get('booksbytags', 'BookController@booksByTags');
Route::get('booksbysearch', 'BookController@booksBySearchQuery');
Route::get('books/{id}', 'BookController@getOne');
Route::get('tags', 'TagController@getAll');

Route::group(['middleware' => 'jwt.auth'], function ($router) {
    
    Route::get('books', 'BookController@getAll');
    Route::post('books/new', 'BookController@new');
    Route::post('books/update/{id}', 'BookController@update');
    Route::delete('books/remove/{id}', 'BookController@remove');

    Route::post('tags/new', 'TagController@new');
    Route::delete('tags/remove/{id}', 'TagController@remove');

});