<?php

use Illuminate\Support\Facades\Route;


Route::get('/register','AuthController@showRegister')->name('register');
Route::post('/register','AuthController@register');

Route::get('/login','AuthController@showLogin')->name('login');
Route::post('/login','AuthController@login');

Route::post('/logout','AuthController@logout')->name('logout');

Route::middleware('auth')->group(function(){

    Route::get('dashboard_list','TaskController@dashboard')->name('dashboard_list');

    Route::get('/','TaskController@index')->name('task_list');
    Route::get('task_list','TaskController@index')->name('task_list');
    Route::get('task_add','TaskController@add')->name('task_add');
    Route::post('task_added','TaskController@add')->name('task_added');
    Route::get('task_edit/{id}','TaskController@edit')->name('task_edit');
    Route::post('task_edited','TaskController@edit')->name('task_edited');
    Route::post('task_delete','TaskController@delete')->name('task_delete');

    Route::post('task_completion', 'TaskController@task_completion')->name('task_completion');

    Route::post('/task_reorder', 'TaskController@reorder')->name('task_reorder');


    
});