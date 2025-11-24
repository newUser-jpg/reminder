<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/agenda', function(){
    return view('agenda');
})->name('agenda');
Route::get('/pomodoro', function(){
    return view('pomodoro');
})->name('pomodoro');
Route::get('/study', function(){
    return view('study');
})->name('study');
