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

Route::group([], function () {
});
Auth::routes();

Route::get('/screens', 'Screen\ScreenController@list')->name('screen.list');
Route::get('/screen/edit/{id}', 'Screen\ScreenController@edit')->name('screen.edit');
Route::get('/screen/remove/{id}', 'Screen\ScreenController@remove')->name('screen.remove');
Route::get('/screen/add', 'Screen\ScreenController@add')->name('screen.add');
Route::get('/s/{uuid}', 'Screen\ShowController@view')->name('screen.view');
Route::get('/data/{uuid}', 'Screen\ShowController@data')->name('screen.data');


Route::get('/widget/score/{widgetId}/{team}/{type}', 'Screen\ScoreWidgetController@change')->name('widget.score.change');
Route::post('/widget/score/{widgetId}/saveSetting', 'Screen\ScoreWidgetController@setting')->name('widget.score.setting');
Route::get('/widget/timer/state/{widgetId}/{state}', 'Screen\TimerWidgetController@state')->name('widget.timer.state');
Route::get('/widget/timer/time/{widgetId}/{state}', 'Screen\TimerWidgetController@time')->name('widget.timer.time');
Route::get('/widget/timer/advanced_size/{widgetId}/{advancedSize}', 'Screen\TimerWidgetController@advancedSize')->name('widget.timer.advanced_size');
Route::post('/widget/timer/{widgetId}/saveSetting', 'Screen\TimerWidgetController@setting')->name('widget.timer.setting');
