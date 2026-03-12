<?php

use App\Http\Livewire\Auth\ForgotPassword;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\ResetPassword;
use App\Http\Livewire\Auth\ChangePassword;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\InstitutionProfile;
use App\Http\Livewire\Profile;
use App\Http\Livewire\RegistryCatalog;
use App\Http\Livewire\RegistryForm;
use App\Http\Livewire\RegistryIndex;
use App\Http\Livewire\RegistryShow;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');
    Route::get('/login/forgot-password', ForgotPassword::class)->name('forgot-password');
    Route::get('/reset-password/{id}', ResetPassword::class)
        ->name('reset-password')
        ->middleware('signed');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/registros', RegistryCatalog::class)->name('registries.catalog');
    Route::get('/registros/{module}', RegistryIndex::class)->name('registries.index');
    Route::get('/registros/{module}/crear', RegistryForm::class)->name('registries.create');
    Route::get('/registros/{module}/{record}', RegistryShow::class)->name('registries.show');
    Route::get('/registros/{module}/{record}/editar', RegistryForm::class)->name('registries.edit');
    Route::get('/institucion', InstitutionProfile::class)->name('institution.profile');
    Route::get('/perfil', Profile::class)->name('profile');
    Route::get('/perfil/cambiar-contrasena', ChangePassword::class)->name('password.change');
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
