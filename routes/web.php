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
Route::get('/screen/{id}/addwidget/{type}', 'Screen\ScreenController@addWidget')->name('screen.addwidget');
Route::get('/screen/edit/{id}', 'Screen\ScreenController@edit')->name('screen.edit');
Route::get('/screen/remove/{id}', 'Screen\ScreenController@remove')->name('screen.remove');
Route::get('/screen/add', 'Screen\ScreenController@add')->name('screen.add');
Route::get('/s/{uuid}', 'Screen\ShowController@view')->name('screen.view');
Route::get('/data/{uuid}', 'Screen\ShowController@data')->name('screen.data');

Route::get('/widget/activate/{widgetId}/{value}', 'Screen\ScreenController@activate')->name('widget.activate');

Route::get('/widget/score/{widgetId}/{team}/{type}', 'Screen\ScoreWidgetController@change')->name('widget.score.change');
Route::post('/widget/score/{widgetId}/saveSetting', 'Screen\ScoreWidgetController@setting')->name('widget.score.setting');

Route::get('/widget/timer/state/{widgetId}/{state}', 'Screen\TimerWidgetController@state')->name('widget.timer.state');
Route::get('/widget/timer/time/{widgetId}/{state}', 'Screen\TimerWidgetController@time')->name('widget.timer.time');
Route::get('/widget/timer/advanced_size/{widgetId}/{advancedSize}', 'Screen\TimerWidgetController@advancedSize')->name('widget.timer.advanced_size');
Route::get('/widget/timer/part/{widgetId}/{value}', 'Screen\TimerWidgetController@part')->name('widget.timer.part');
Route::post('/widget/timer/{widgetId}/saveSetting', 'Screen\TimerWidgetController@setting')->name('widget.timer.setting');

Route::post('/widget/squad/{widgetId}/saveSquad', 'Screen\SquadWidgetController@squad')->name('widget.squad.squad');
Route::post('/widget/squad/{widgetId}/saveSetting', 'Screen\SquadWidgetController@setting')->name('widget.squad.setting');
