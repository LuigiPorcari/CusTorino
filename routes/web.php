<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\TrainerAuthController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::get('/', function () {
    return view('homepage');
});

// Admin routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('login.admin');
Route::get('/admin/register', [AdminAuthController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('register.admin');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Trainer routes
Route::get('/trainer/login', [TrainerAuthController::class, 'showLoginForm'])->name('trainer.login');
Route::post('/trainer/login', [TrainerAuthController::class, 'login'])->name('login.trainer');
Route::get('/trainer/register', [TrainerAuthController::class, 'showRegistrationForm'])->name('trainer.register');
Route::post('/trainer/register', [TrainerAuthController::class, 'register'])->name('register.trainer');
Route::post('/trainer/logout', [TrainerAuthController::class, 'logout'])->name('trainer.logout');

// Student routes
Route::get('/student/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
Route::post('/student/login', [StudentAuthController::class, 'login'])->name('login.student');
Route::get('/student/register', [StudentAuthController::class, 'showRegistrationForm'])->name('student.register');
Route::post('/student/register', [StudentAuthController::class, 'register'])->name('register.student');
Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
Route::post('/student/mark-absence/{alias}', [StudentController::class, 'markAbsence'])->name('student.markAbsence');
Route::get('/student/dashboard' , [StudentController::class , 'dashboard'])->name('student.dashboard');
Route::post('/student/rec-absence/{alias}', [StudentController::class, 'recAbsence'])->name('student.recAbsence');

//Groups routes
Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
