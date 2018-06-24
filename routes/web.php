<?php

use App\Http\Middleware\CheckAge;

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

Route::get('/','IndexController@index');

//获取数据 搜索
Route::get('/getList','IndexController@getList');

//抓取数据
Route::get('/fetchData','FetchController@getCInfo');
//抓取数据2
Route::get('/fetch40Data','FetchController@get40Info');

//导入数据
Route::post('/import','IndexController@import');

