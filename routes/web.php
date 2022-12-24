
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

Route::get('/', 'HomePageController@homePage')->name('homePage');

Route::group(['prefix' => 'users'], function(){
    Route::get('/', 'UserController@index')->name('usersIndex');
    Route::get('/records', 'UserController@usersRecords')->name('usersRecords');
    Route::get('/create', 'UserController@create')->name('usersCreate');
    Route::post('/store', 'UserController@store')->name('usersStore');
    Route::get('/edit/{id}', 'UserController@edit')->name('usersEdit');
    Route::post('/update/{id}', 'UserController@update')->name('usersUpdate');
    Route::post('/delete', 'UserController@delete')->name('usersDelete');
});

Route::group(['prefix' => 'company'], function(){
    Route::get('/', 'CompanyController@index')->name('companiesIndex');
    Route::get('/records', 'CompanyController@companiesRecords')->name('companiesRecords');
    Route::get('/create', 'CompanyController@create')->name('companiesCreate');
    Route::post('/store', 'CompanyController@store')->name('companiesStore');
    Route::get('/edit/{id}', 'CompanyController@edit')->name('companiesEdit');
    Route::post('/update/{id}', 'CompanyController@update')->name('companiesUpdate');
    Route::post('/delete', 'CompanyController@delete')->name('companiesDelete');
    Route::get('/assign/users/{id}', 'CompanyController@assignUsers')->name('companiesAssignUsers');
    Route::post('/assign/users/add/{id}', 'CompanyController@storeAssignUsers')->name('companiesStoreAssignUsers');
});
