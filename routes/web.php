<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/index', function(){
    return view('index');
})->name('index');
Route::get('/pomodoro', function(){
    return view('pomodoro');
})->name('pomodoro');
Route::get('/study', function(){
    return view('study');
})->name('study');
