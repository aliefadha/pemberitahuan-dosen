<?php

use App\Http\Controllers\Admin\DokumenController as AdminDokumenController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notificationId}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/dokumens', [AdminDokumenController::class, 'index'])->name('dokumens.index');
        Route::get('/dokumens/create', [AdminDokumenController::class, 'create'])->name('dokumens.create');
        Route::post('/dokumens', [AdminDokumenController::class, 'store'])->name('dokumens.store');
        Route::get('/dokumens/{dokumen}/edit', [AdminDokumenController::class, 'edit'])->name('dokumens.edit');
        Route::put('/dokumens/{dokumen}', [AdminDokumenController::class, 'update'])->name('dokumens.update');
        Route::delete('/dokumens/{dokumen}', [AdminDokumenController::class, 'destroy'])->name('dokumens.destroy');
        Route::get('/dokumens/{dokumen}/submissions', [AdminDokumenController::class, 'submissions'])->name('dokumens.submissions');
        Route::post('/dokumens/{dokumen}/submissions/{submissionId}/accept', [AdminDokumenController::class, 'accept'])->name('dokumens.submissions.accept');
        Route::post('/dokumens/{dokumen}/submissions/{submissionId}/reject', [AdminDokumenController::class, 'reject'])->name('dokumens.submissions.reject');

        Route::get('/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp.index');
        Route::get('/whatsapp/status', [WhatsAppController::class, 'status'])->name('whatsapp.status');
        Route::get('/whatsapp/qr', [WhatsAppController::class, 'qr'])->name('whatsapp.qr');
        Route::post('/whatsapp/restart', [WhatsAppController::class, 'restart'])->name('whatsapp.restart');
        Route::post('/whatsapp/send-test', [WhatsAppController::class, 'sendTest'])->name('whatsapp.sendTest');
    });

    Route::get('/dokumens', [DokumenController::class, 'index'])->name('dokumens.index');
    Route::middleware('dosen')->group(function () {
        Route::get('/dokumens/{dokumen}/submit', [DokumenController::class, 'submit'])->name('dokumens.submit');
        Route::post('/dokumens/{dokumen}/submit', [DokumenController::class, 'storeSubmit'])->name('dokumens.store-submit');
        Route::get('/dokumens/submissions', [DokumenController::class, 'submissions'])->name('dokumens.my-submissions');
        Route::post('/dokumens/submissions/{submission}/send-whatsapp', [DokumenController::class, 'sendWhatsApp'])->name('dokumens.submissions.send-whatsapp');
    });
});

require __DIR__.'/auth.php';
