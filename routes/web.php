<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

 Route::get('login', function() {
     return view('login');
 })->name('login');

Route::group(['prefix' => 'web/v1'], function () {
    // Route::post('login', 'Security\LoginController@login');


    //people
    // Route::group(['prefix' => 'people'], function () {
    //     Route::get('total-people-venue', 'Core\PersonController@totalPeopleVenue');
    //     Route::get('owners', 'Core\VenueController@getOwners');
    //     Route::get('{id}/owners-by-type-structures', 'Core\PersonController@ownersByTypeStructure');
    //     //Route::get('venues/{venueId}/users', 'Core\PersonController@getPersonFromVenueAndUser');
    //     Route::get('venues/{venueId}/users/{userId}', 'Core\PersonController@getPersonFromVenueAndUser');
    //     Route::get('{id}/capabilities', 'Core\PersonController@getPersonCapabilities');
    //     Route::get('{person}/send-invitation', 'Core\PersonController@sendInvitation');
    //     Route::get('{person}/reset-password', 'Core\PersonController@resetPassword');
    //     Route::get('{id}/download-data-people', 'Core\PersonController@downloadDataPeople');
    // });



    // Route::group(['prefix' => 'users'], function () {
    //     Route::get('hosts', 'Core\UserController@getHosts');
    //     Route::get('{user}/units', 'Core\UserController@getUnits')->where('user', '[0-9]+');
    //     Route::get('{user}/venues', 'Core\UserController@getVenues')->where('user', '[0-9]+');
    //     Route::get('{user}/workplaces', 'Core\UserController@getWorkplaces')->where('user', '[0-9]+');
    //     Route::get('{userId}/open-shift', 'Core\UserController@getOpenShift')->where('userId', '[0-9]+');
    //     Route::get('{userId}/profiles/{profileId}/venues', 'Core\UserController@getVenuesByProfile')->where('userId', '[0-9]+');
    //     Route::post('administrators', 'Core\UserController@createAdministrator');
    //     Route::get('guards', 'Core\UserController@getGuards');
    //     Route::get('{id}/reset-password', 'Core\UserController@resetPassword');
    //     Route::get('{id}/common-polls', 'Core\UserController@getCommonPolls');
    //     Route::post('{id}/opt-in', 'Core\UserController@optIn');
    // });


});
