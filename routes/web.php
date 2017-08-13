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

Route::get('/add', function () {
    return \App\User::find(4)->add_friend(1);
});

Route::get('accept', function () {
    return App\User::find(1)->accept_friend(4);
});

Route::get('/friends', function () {
    return App\User::find(4)->friends();
});


Route::get('/ids', function () {
    return App\User::find(5)->friends_ids();
});

Route::get('/is_friend', function () {
    return App\User::find(5)->is_friends_with(1);
});

Route::get('/ch', function () {
    return App\User::find(5)->add_friend(3);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', 'ProfileController@edit')->name('profile.edit');

    Route::post('/profile/update', 'ProfileController@update')->name('profile.update');

    Route::get('/profile/{slug}', 'ProfileController@index')->name('profile');

    Route::get('/check_relationship_status/{id}', 'FriendshipsController@check')->name('check');

    Route::get('/add_friend/{id}', 'FriendshipsController@add')->name('add');
});