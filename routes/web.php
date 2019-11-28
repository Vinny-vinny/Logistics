<?php


Route::view('/','home');
Route::get('cool','JobcardController@cool');
Route::view('/{any}','home');
Route::view('/{any}/{any1}','home');

