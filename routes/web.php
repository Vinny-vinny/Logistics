<?php


Route::view('/','home');
Route::get('import-projects','ProjectController@importProjects');
Route::get('import-customers','CustomerController@importCustomers');
Route::view('/{any}','home');
Route::view('/{any}/{any1}','home');

