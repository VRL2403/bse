<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/index', function () {
//     return view('admin.index');
// });/
Route::get('/', function () {
    return redirect('/dashboard');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/bse_form', function () {
    return view('bse_form');
});


Route::get('/admin/companies', 'App\Http\Controllers\Admin\CompaniesController@companies')->name('companies');
Route::get('/admin/broker_houses', 'App\Http\Controllers\Admin\BrokerHousesController@broker_houses')->name('broker_houses');
Route::get('/admin/teams', 'App\Http\Controllers\Admin\TeamsController@teams')->name('teams');
Route::get('/admin/orders', 'App\Http\Controllers\Admin\OrdersController@brokers')->name('orders');
Route::get('/admin/teams_tagged', 'App\Http\Controllers\Admin\OrdersController@teamsTagged');
Route::get('/admin/active_round', 'App\Http\Controllers\Admin\OrdersController@checkActiveRound');


Route::get('/admin/users', 'App\Http\Controllers\Admin\UsersController@user_list');

Route::get('/sign-up', [RegisterController::class, 'create'])
    ->middleware('guest')
    ->name('sign-up');

Route::post('/sign-up', [RegisterController::class, 'store'])
    ->middleware('guest');

Route::get('/sign-in', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('sign-in');


Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Route::post('/sign-in', [LoginController::class, 'store'])
//     ->middleware('guest');
// Route::post('/logout', [LoginController::class, 'destroy'])
//     ->middleware('auth')
//     ->name('logout');
