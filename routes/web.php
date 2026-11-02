<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/create-post', 'posts.create')
    ->name('posts.create');
