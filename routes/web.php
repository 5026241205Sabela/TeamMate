<?php

use App\Http\Controllers\Admin\AccountVerificationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompetitionPostController;
use App\Http\Controllers\JoinRequestController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $account = Auth::user();

        if ($account->isAdmin()) {
            return to_route('admin.verifications.index');
        }

        if ($account->isRejectedByAdmin() && request()->query('tab') === 'register') {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return view('landing');
        }

        if (! $account->isApprovedByAdmin() || ! $account->hasVerifiedEmail()) {
            return to_route('verification.notice');
        }

        return to_route('home');
    }

    return view('landing');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('password.request');
});

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/verifications');
    Route::get('/verifications', [AccountVerificationController::class, 'index'])->name('verifications.index');
    Route::patch('/verifications/{account}/approve', [AccountVerificationController::class, 'approve'])->name('verifications.approve');
    Route::patch('/verifications/{account}/reject', [AccountVerificationController::class, 'reject'])->name('verifications.reject');
    Route::get('/verifications/{account}/ktm', [AccountVerificationController::class, 'ktm'])->name('verifications.ktm');
});

Route::get('/posts/latest', [PostController::class, 'latest'])->name('posts.latest');

Route::middleware(['auth', 'account.verified'])->group(function () {
    Route::get('/home', [PostController::class, 'home'])->name('home');

    // Competition Posts (create, my-posts, edit status)
    Route::get('/create-post', [CompetitionPostController::class, 'create'])
        ->name('posts.create');

    Route::post('/create-post', [CompetitionPostController::class, 'store'])
        ->name('posts.store');

    Route::get('/my-posts', [CompetitionPostController::class, 'index'])
        ->name('posts.index');

    Route::get('/my-posts/{post}/edit', [CompetitionPostController::class, 'edit'])
        ->name('posts.edit');

    Route::patch('/my-posts/{post}/status', [CompetitionPostController::class, 'updateStatus'])
        ->name('posts.update-status');

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
});
