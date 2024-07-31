<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\TrainerAuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PasswordUpdateController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

//! Rotte per gli amministratori
Route::group(['middleware' => ['auth:admin']], function () {
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('password/change', [PasswordUpdateController::class, 'showAdminPasswordChangeForm'])->name('password.change');
        Route::post('password/change', [PasswordUpdateController::class, 'updateAdminPassword'])->name('password.update');
    });
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
    Route::prefix('trainer')->name('trainer.')->group(function () {
        Route::get('password/change', [PasswordUpdateController::class, 'showTrainerPasswordChangeForm'])->name('password.change');
        Route::post('password/change', [PasswordUpdateController::class, 'updateTrainerPassword'])->name('password.update');
    });
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
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('password/change', [PasswordUpdateController::class, 'showStudentPasswordChangeForm'])->name('password.change');
        Route::post('password/change', [PasswordUpdateController::class, 'updateStudentPassword'])->name('password.update');
    });
    Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::post('/student/mark-absence/{alias}', [StudentController::class, 'markAbsence'])->name('student.markAbsence');
    Route::post('/student/rec-absence/{alias}', [StudentController::class, 'recAbsence'])->name('student.recAbsence');
    Route::post('/student/update', [StudentController::class, 'update'])->name('student.updateDoc');
});
//! Public routes
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admins.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('login.admin');
Route::get('/admin/register', [AdminAuthController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('register.admin');
Route::get('/student/login', [StudentAuthController::class, 'showLoginForm'])->name('students.login');
Route::post('/student/login', [StudentAuthController::class, 'login'])->name('login.student');
Route::get('/student/register', [StudentAuthController::class, 'showRegistrationForm'])->name('student.register');
Route::post('/student/register', [StudentAuthController::class, 'register'])->name('register.student');
Route::get('/trainer/login', [TrainerAuthController::class, 'showLoginForm'])->name('trainers.login');
Route::post('/trainer/login', [TrainerAuthController::class, 'login'])->name('login.trainer');
Route::get('/trainer/register', [TrainerAuthController::class, 'showRegistrationForm'])->name('trainer.register');
Route::post('/trainer/register', [TrainerAuthController::class, 'register'])->name('register.trainer');
// Rotte per 'trainer'
Route::prefix('trainer')->name('trainers.')->group(function () {
    Route::get('password/reset', [PasswordResetController::class, 'showTrainerLinkRequestForm'])->name('password.request');
    Route::post('password/email', [PasswordResetController::class, 'sendTrainerResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [PasswordResetController::class, 'showTrainerResetForm'])->name('password.reset');
    Route::post('password/reset', [PasswordResetController::class, 'resetTrainerPassword'])->name('password.update');
});
// Rotte per 'student'
Route::prefix('student')->name('students.')->group(function () {
    Route::get('password/reset', [PasswordResetController::class, 'showStudentLinkRequestForm'])->name('password.request');
    Route::post('password/email', [PasswordResetController::class, 'sendStudentResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [PasswordResetController::class, 'showStudentResetForm'])->name('password.reset');
    Route::post('password/reset', [PasswordResetController::class, 'resetStudentPassword'])->name('password.update');
});
// Rotte per 'admin'
Route::prefix('admin')->name('admins.')->group(function () {
    Route::get('password/reset', [PasswordResetController::class, 'showAdminLinkRequestForm'])->name('password.request');
    Route::post('password/email', [PasswordResetController::class, 'sendAdminResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [PasswordResetController::class, 'showAdminResetForm'])->name('password.reset');
    Route::post('password/reset', [PasswordResetController::class, 'resetAdminPassword'])->name('password.update');
});
