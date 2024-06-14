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
Route::get('/admin/teams_tagged/{id?}', 'App\Http\Controllers\Admin\OrdersController@teamsTagged');
Route::get('/admin/companies/team/{id?}', 'App\Http\Controllers\Admin\OrdersController@companyTeamData');
Route::get('/admin/active_round', 'App\Http\Controllers\Admin\OrdersController@checkActiveRound');
Route::post('/admin/check_sell_quantity', 'App\Http\Controllers\Admin\OrdersController@checkSellQuantity');
Route::post('/admin/save_order', 'App\Http\Controllers\Admin\OrdersController@saveOrders');
Route::get('/admin/configuration', 'App\Http\Controllers\Admin\StatsController@config')->name('configuration');
Route::get('/admin/cal_stats', 'App\Http\Controllers\Admin\StatsController@calculateStats');
Route::get('/admin/change_round/{id?}', 'App\Http\Controllers\Admin\StatsController@changeActiveRound');
Route::get('/admin/ledger', 'App\Http\Controllers\Admin\StatsController@ledger')->name('ledger');
Route::get('/admin/holdings', 'App\Http\Controllers\Admin\StatsController@holdings')->name('holdings');
Route::get('/admin/holdings/{id?}', 'App\Http\Controllers\Admin\StatsController@teamHoldings');
Route::get('/admin/reset_game', 'App\Http\Controllers\Admin\StatsController@resetGame');


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
