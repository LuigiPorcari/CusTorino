<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\TrainerAuthController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

//! Rotte per gli amministratori
Route::group(['middleware' => ['auth:admin']], function () {
    // Admin routes
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard/group/details/{group}', [AdminController::class, 'groupDetails'])->name('admin.group.details');
    Route::get('/admin/dashboard/trainer', [AdminController::class, 'dashboardTrainer'])->name('admin.dashboard.trainer');
    Route::get('/admin/dashboard/trainer/details/{trainer}', [AdminController::class, 'trainerDetails'])->name('admin.trainer.details');
    Route::get('/admin/dashboard/student', [AdminController::class, 'dashboardStudent'])->name('admin.dashboard.student');
    Route::post('/admin/dashboard/update/{student}', [AdminController::class, 'updateStudent'])->name('admin.update.student');
    Route::delete('/admin/delete/{id}', [AdminAuthController::class, 'destroy'])->name('admin.destroy');
    //Groups routes
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/edit/{group}', [GroupController::class, 'edit'])->name('groups.edit');
    Route::post('/groups/update/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/delete/{group}', [GroupController::class, 'delete'])->name('groups.delete');
    Route::get('/groups/create/student/{group}', [GroupController::class, 'editStudent'])->name('edit.student');
    Route::post('/groups/update/student/{group}', [GroupController::class, 'createStudent'])->name('create.student');
    //Students routes
    Route::delete('/student/delete/{id}', [StudentAuthController::class, 'destroy'])->name('student.destroy');
});
//! Rotte per gli allenatori
Route::group(['middleware' => ['auth:trainer']], function () {
    // Trainer routes
    Route::post('/trainer/logout', [TrainerAuthController::class, 'logout'])->name('trainer.logout');
    Route::delete('/trainer/delete/{id}', [TrainerAuthController::class, 'destroy'])->name('trainer.destroy');
    Route::get('/trainer/dashboard', [TrainerController::class, 'dashboard'])->name('trainer.dashboard');
    Route::post('/trainer/student-absence/{alias}', [TrainerController::class, 'studentAbsence'])->name('student.absence');
    Route::post('/trainer/student-recoveries/{alias}', [TrainerController::class, 'recoveriesStudent'])->name('student.recoveries');
    Route::post('/trainer/trainer-absence/{alias}', [TrainerController::class, 'aliasUpdate'])->name('alias.update');
    Route::get('/trainer/create/student/{alias}', [TrainerController::class, 'editStudent'])->name('editStudent.trainer');
    Route::post('/trainer/update/student/{alias}', [TrainerController::class, 'recoveriesStudent'])->name('createStudent.trainer');
    Route::get('/trainer/alias/{alias}', [TrainerController::class, 'showDetails'])->name('trainer.details');
});
//! Rotte per gli studenti
Route::group(['middleware' => ['auth:student']], function () {
    // Student routes
    Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::post('/student/mark-absence/{alias}', [StudentController::class, 'markAbsence'])->name('student.markAbsence');
    Route::post('/student/rec-absence/{alias}', [StudentController::class, 'recAbsence'])->name('student.recAbsence');
    Route::post('/student/update', [StudentController::class, 'update'])->name('student.updateDoc');
});
//! Public routes
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('login.admin');
Route::get('/admin/register', [AdminAuthController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('register.admin');
Route::get('/student/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
Route::post('/student/login', [StudentAuthController::class, 'login'])->name('login.student');
Route::get('/student/register', [StudentAuthController::class, 'showRegistrationForm'])->name('student.register');
Route::post('/student/register', [StudentAuthController::class, 'register'])->name('register.student');
Route::get('/trainer/login', [TrainerAuthController::class, 'showLoginForm'])->name('trainer.login');
Route::post('/trainer/login', [TrainerAuthController::class, 'login'])->name('login.trainer');
Route::get('/trainer/register', [TrainerAuthController::class, 'showRegistrationForm'])->name('trainer.register');
Route::post('/trainer/register', [TrainerAuthController::class, 'register'])->name('register.trainer');
