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


/*
Route::get('/', function () {
    return view('welcome');
});
 */
use Illuminate\Http\Request;
use App\Http\Controllers;



Route::get('/', 'App\Http\Controllers\MessageController@index')->name('messages')
->missing(fn($request) => response()->view('errors.project-not-found'));


Route::resource('/messages', 'App\Http\Controllers\MessageController')->except(['create'])
->missing(fn($request) => response()->view('errors.project-not-found'));





