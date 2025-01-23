<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VacationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post("/register", [AuthController::class, "register"])->name("register");
Route::post("/login", [AuthController::class, "login"])->name("login");
Route::get("/logout", [AuthController::class, "logout"])->name("logout")->middleware("auth:sanctum");


Route::middleware(['auth:sanctum'])->group(function () {
    // Rutas individuales con middleware específico
    Route::get('vacations', [VacationController::class, 'index'])
        ->middleware('can:vacations.index');
    Route::post('vacations', [VacationController::class, 'store'])
        ->middleware('can:vacations.store');
    Route::get('vacations/{vacation}', [VacationController::class, 'show'])
        ->middleware('can:vacations.show');
    Route::put('vacations/{vacation}', [VacationController::class, 'update'])
        ->middleware('can:vacations.update');
    Route::delete('vacations/{vacation}', [VacationController::class, 'destroy'])
        ->middleware('can:vacations.destroy');

    Route::patch("vacations/{vacation}/approve", [VacationController::class, 'approve'])
        ->name('vacations.approve')->middleware(['auth:sanctum', 'can:vacations.approve']);
    Route::patch('vacations/{vacation}/reject', [VacationController::class, 'reject'])
        ->name('vacaions.reject')->middleware(['auth:sanctum', 'can:vacations.reject']);
});
