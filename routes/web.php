<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Admin\TenantsController;
use App\Http\Controllers\Admin\UsersController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Generic dashboard (entry point)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile Operations
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin')->prefix('admin')->as('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/tenants', [TenantsController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/create', [TenantsController::class, 'create'])->name('tenants.create');
        Route::post('/tenants/store', [TenantsController::class, 'store'])->name('tenants.store');
        Route::get('/tenants/{tenant}/edit', [TenantsController::class, 'edit'])->name('tenants.tenant');
        Route::put('/tenants/{tenant}', [TenantsController::class, 'update'])->name('tenants.update');
        Route::patch('/tenants/{tenant}/status', [TenantsController::class, 'status'])->name('tenants.status');

        Route::get('/tenants/{tenant}/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('/tenants/{tenant}/users/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('/tenants/{tenant}/users', [UsersController::class, 'store'])->name('users.store');
        Route::get('/tenants/{tenant}/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::put('/tenants/{tenant}/users/{user}', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/tenants/{tenant}/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware('role:user')->prefix('user')->as('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    });
});

require __DIR__.'/auth.php';
