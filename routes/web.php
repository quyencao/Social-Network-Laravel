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

Route::get('/hello', function () {
    return Auth::user()->hello();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', 'ProfileController@edit')->name('profile.edit');

    Route::post('/profile/update', 'ProfileController@update')->name('profile.update');

    Route::get('/profile/{slug}', 'ProfileController@index')->name('profile');
});