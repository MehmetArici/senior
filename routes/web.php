<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within aP group which
| contains the "web" middleware group. Now create something great!
|
*/
/* GETS */
Route::get('/', 'HomeController@index')->name('home');

Route::get('/result', 'ResultController@index')->name('result');

Route::get('/analysis', 'AnalysisController@index')->name('analysis');

Route::get('/load_analysis', 'AnalysisController@load_example')->name('load_exapmle');

Route::get('/load_genome/{gene?}/{region?}', 'ResultController@load_genome')->name('load_genome');


/* POSTS */
Route::post('/post_analyze', 'AnalysisController@post_analyze')->name('post_analyze');

Route::post('/send_job', 'ResultController@send_job')->name('send_job');

Route::post('/see_analysis', 'ResultController@see_analysis')->name('see_analysis');
