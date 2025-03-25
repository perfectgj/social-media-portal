<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login.show');
});

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
    Route::get('/search-users', [DashboardController::class, 'searchUsers'])->name('search.users');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Friend Routes
    Route::get('/add-friend/{id}', [FriendController::class, 'addFriend'])->name('add.friend');
    Route::post('/respond-friend/{id}', [FriendController::class, 'respondFriendRequest'])->name('respond.friend');
    Route::get('/friends', [FriendController::class, 'friendsList'])->name('friends.list');
    Route::delete('/remove-friend/{id}', [FriendController::class, 'removeFriend'])->name('remove.friend');

    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifications/respond/{id}', [NotificationController::class, 'respondNotification'])->name('notifications.respond');

    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show')->middleware('auth');
    Route::post('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update')->middleware('auth');
});
