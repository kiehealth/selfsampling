<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KitController;
use App\Http\Controllers\UserController;

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
    //return view('welcome');
    return view('research');
});

Route::get('order', function () {
    return view('order_kit');
});

Route::get('unsubscribe', function () {
    return view('unsubscribe');
});

Route::get('admin/reports', function () {
    return view('admin.reports');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/faqs', function () {
    return view('faqs');
});


/*
Route::post('users', 'UserController@store');
//Route::get('users', 'UserController@index');
Route::get('users/{id}', 'UserController@show');
Route::get('users/{id}/orders', 'OrderController@getAllOrdersforUser');
Route::get('users/pnr/{pnr}', 'UserController@getUserbyPNR');
//Route::put('users/{id}', 'UserController@update');
Route::delete('users/{id}', 'UserController@destroy');

Route::get('orders', 'OrderController@index');
Route::get('orders/{id}', 'OrderController@show');
Route::get('orders/{order}/users', 'UserController@getUserforOrder');
Route::put('orders/{id}', 'OrderController@update');
Route::delete('orders/{id}', 'OrderController@destroy');


*/

Route::post('orders', 'OrderController@store');
Route::post('orderKit', 'OrderController@orderKit');

Route::get('login', 'BankIDController@bankidlogin');
Route::get('authenticate', 'BankIDController@bankidauthenticate');
Route::post('checkpnr', 'PersonnummerController@valid');
Route::get('logout', 'BankIDController@bankidlogout');

Route::get('profile', 'UserController@profile');
Route::get('myprofile', 'UserController@myprofile');
Route::put('user/updateprofile/{id}', 'UserController@updateprofile');
Route::get('myorders', 'OrderController@myorders');
Route::get('myresults', 'SampleController@myresults');
Route::post('unsubscribe/{type?}', [UserController::class, 'unsubscribe'])->where(['type' => 'pnr']);
Route::post('admin/search', 'SearchController@search');

Route::get('admin/login', function () {
    return view('admin.login');
});

Route::get('admin', 'DashboardController@home');
Route::get('admin/dashboard', 'DashboardController@dashboard');
Route::get('admin/createUser', 'UserController@create');
Route::post('admin/createUser', 'UserController@store');
Route::get('admin/importUser', 'UserController@import');
Route::post('admin/importUser', 'UserController@importUserSave');
Route::get('admin/users', 'UserController@index');
Route::get('admin/getUsers', 'UserController@getUsers');
Route::get('admin/users/{id}/edit', 'UserController@edit');
Route::put('admin/users/{id}', 'UserController@update');
Route::delete('admin/users/{id}', 'UserController@destroy');
Route::get('admin/orders', 'OrderController@index');
Route::get('admin/getOrders', 'OrderController@getOrders');
Route::get('admin/createOrder', 'OrderController@create');
Route::delete('admin/orders/{id}', 'OrderController@destroy');
Route::get('admin/importOrder', 'OrderController@import');
Route::post('admin/importOrder', 'OrderController@importOrderSave');
Route::get('admin/orders/{id}/registerKit', 'KitController@create');
Route::post('admin/orders/{id}/registerKit', 'KitController@store');
Route::get('admin/kits/{id}/edit/{type?}', 'KitController@edit')->where(['type' => 'kits|viaord']);
Route::put('admin/kits/{id}/{type?}', 'KitController@update')->where(['type' => 'kits|viaord']);
Route::delete('admin/kits/{id}', 'KitController@destroy');
Route::get('admin/kits', 'KitController@index');
Route::get('admin/getKits', 'KitController@getKits');
Route::get('admin/importKit', 'KitController@import');
Route::post('admin/importKit', 'KitController@importKitSave');
Route::get('admin/kits/{id}/registerSample', 'SampleController@registerSample');
Route::post('admin/kits/{id}/register', 'SampleController@register');
Route::get('admin/samples', 'SampleController@index');
Route::get('admin/getSamples', 'SampleController@getSamples');
Route::get('admin/samples/{id}/edit', 'SampleController@edit');
Route::put('admin/samples/{id}', 'SampleController@update');
Route::delete('admin/samples/{id}', 'SampleController@destroy');
Route::get('admin/importSample', 'SampleController@import');
Route::post('admin/importSample', 'SampleController@importSampleSave');


