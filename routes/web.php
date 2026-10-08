<?php

use App\Livewire\Admin\AdminRegister;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Settings;
use App\Livewire\Profile\ProfilePage;
use App\Livewire\Security\SecuritySettings;
use App\Livewire\User\Dashboard as UserDashboard;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome')->name('home');

Route::get('/', function () {
    return redirect()->route('login');
});

// Pages Auth Admin
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::view('/login', 'admin.auth.login')->name('login');
    Route::view('/forgot-password', 'admin.auth.forgot-password')->name('password.request');
});

//User Dashboard
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('dashboard');
});

//Admin Dashboard
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

        // Création d’un autre admin.
        Route::get('/register', AdminRegister::class)->name('register');
        Route::get('/settings', Settings::class)->name('settings');
        Route::get('/security', SecuritySettings::class)->name('security');
        Route::redirect('/settings', '/security', '/profile')->name('settings');
    });


// Sécurité commune : 2FA, passkeys, password confirm

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', ProfilePage::class)->name('profile');
    Route::get('/security', SecuritySettings::class)->name('security');
    Route::redirect('/security', '/profile')->name('settings');
});