<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/main-builder', function () {
    return view('wekker_dashboard.main_builder');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ------------------------- PROJECT ------------------------------------
use App\Http\Controllers\ProjectController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/main-builder', [ProjectController::class, 'showMainBuilder'])->name('main.builder');
    Route::get('/dashboard/main-builder/{id_unik?}', [ProjectController::class, 'showMainBuilder'])->name('projects.show');
    Route::post('/dashboard/main-builder/create-project', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/dashboard/main-builder/{id_unik}/save-projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/dashboard/main-builder/{uuid}/data', [ProjectController::class, 'getProjectData'])->name('projects.get');
    Route::get('/get-list-projects', [ProjectController::class, 'getListProject'])->name('projects.list');
    Route::delete('/delete-project/{uuid}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});

// ------------------------- DEVELOPER TOOLS ------------------------------------

Route::get('/dashboard/developer-tool', function () {
    return view('wekker_dashboard.developer_tool');
})->middleware(['auth', 'verified'])->name('developer.tool');

// ------------------------- FILE MANAGER ------------------------------------

Route::get('/dashboard/file-manager', function () {
    return view('wekker_dashboard.file_manager');
})->middleware(['auth', 'verified'])->name('file.manager');

// ------------------------- API MANAGEMENT ------------------------------------

Route::get('/dashboard/api-management', function () {
    return view('wekker_dashboard.api_management');
})->middleware(['auth', 'verified'])->name('api.management');

// ------------------------- SETTING ------------------------------------

Route::get('/dashboard/setting', function () {
    return view('wekker_dashboard.setting');
})->middleware(['auth', 'verified'])->name('setting');

// ------------------------- SUPPORT CENTER ------------------------------------

Route::get('/dashboard/support-center', function () {
    return view('wekker_dashboard.support_center');
})->middleware(['auth', 'verified'])->name('support.center');

// ------------------------- SIGNOUT ------------------------------------

Route::get('/dashboard/signout', function () {
    return view('wekker_dashboard.signout');
})->middleware(['auth', 'verified'])->name('signout');