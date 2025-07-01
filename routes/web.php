<?php

use App\Livewire\Admin\ManageCertificate;
use App\Livewire\Admin\ManageProjects;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('admin/projects', ManageProjects::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.projects');

Route::get('admin/certificate', ManageCertificate::class)
        ->middleware(['auth', 'verified'])
        ->name('admin.certificates');

require __DIR__.'/auth.php';
