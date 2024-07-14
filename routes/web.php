<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile', [ProfileController::class, 'upload'])->name('profile.upload');
});

Route::middleware('auth')->group(function () {
    Route::prefix('friend')->group(function () {
        Route::get('/portal', [FriendController::class, 'getPortal'])->name('portal');
        Route::get('/', [FriendController::class, 'index'])->name('home');
        Route::get('/addpage', [FriendController::class, 'addPage'])->name('addpage');
        Route::get('/online-users', [FriendController::class, 'onlineUsers'])->name('online');
        Route::post('/store', [FriendController::class, 'store'])->name('friendreq');
        Route::get('/list', [FriendController::class, 'show'])->name('requestlist');
        // 接受好友邀請
        Route::post('/accept', [FriendController::class, 'accept'])->name('accept');
        // 拒絕好友邀請
        Route::delete('/cancel', [FriendController::class, 'cancel'])->name('cancel');
        Route::get('/chat/{friend_id}', [MessageController::class, 'room'])->name('chatroom');
        Route::post('/send', [MessageController::class, 'sendMessage'])->name('send');
    });
});

require __DIR__.'/auth.php';
