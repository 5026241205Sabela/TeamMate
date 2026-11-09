
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompetitionPostController;
use App\Http\Controllers\JoinRequestController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('home');

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('password.request');

// route untuk create competition post (sabel)
Route::get('/create-post', [CompetitionPostController::class, 'create'])
    ->name('posts.create');

Route::post('/create-post', [CompetitionPostController::class, 'store'])
    ->name('posts.store');

// Route fitur edit post status (punyamu)
Route::view('/edit-post-status', 'posts.edit-post-status');

// Join Requests
Route::get('/join-requests', [JoinRequestController::class, 'index'])
    ->name('join-requests.index');

Route::patch('/join-requests/{joinRequest}/status', [JoinRequestController::class, 'updateStatus'])
    ->name('join-requests.update-status');

// Reports
Route::get('/reports/create/{post}', [ReportController::class, 'create'])
    ->name('reports.create');

Route::post('/reports/{post}', [ReportController::class, 'store'])
    ->name('reports.store');
