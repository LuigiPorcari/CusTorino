<?php

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckStudent;
use App\Http\Middleware\CheckTrainer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;

//!ROTTA HOME
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');
//!ROTTE LOGIN UTENTI
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
//!ROTTE REGISTRAZIONE CORSISTA
Route::get('/register/corsista', [RegisterController::class, 'showCorsistaRegistrationForm'])->name('corsista.register');
Route::post('/register/corsista', [RegisterController::class, 'registerCorsista']);
//!ROTTE RESET PASSWORD
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
//!MIDDLEWERE UTENTE LOGGATO
Route::middleware('auth')->group(function () {
    //!ROTTE CAMBIO PASSWORD
    Route::get('/password/change', [PasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/change', [PasswordController::class, 'changePassword']);
    //!ROTTE LOGOUT UTENTI
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
//!MIDDLEWERE ADMIN
Route::middleware(CheckAdmin::class)->group(function () {
    //!ROTTE REGISTRAZIONE ADMIN
    Route::get('/register/admin', [RegisterController::class, 'showAdminRegistrationForm'])->name('admin.register');
    Route::post('/register/admin', [RegisterController::class, 'registerAdmin']);
    //!ROTTA ELIMINA ADMIN
    Route::delete('/admin/delete/{id}', [LoginController::class, 'destroyAdmin'])->name('admin.destroy');
    //!ROTTA ADMIN ELIMINA CORSISTA
    Route::delete('/student/delete/{id}', [LoginController::class, 'adminDestroyStudent'])->name('student.destroy');
    //!ROTTE REGISTRAZIONE TRAINER
    Route::get('/register/trainer', [RegisterController::class, 'showTrainerRegistrationForm'])->name('trainer.register');
    Route::post('/register/trainer', [RegisterController::class, 'registerTrainer']);
    //!ROTTE ADMIN GRUPPI
    Route::get('/admin/dashboard/group/details/{group}', [AdminController::class, 'groupDetails'])->name('admin.group.details');
    //!ROTTE GRUPPI
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/edit/{group}', [GroupController::class, 'edit'])->name('groups.edit');
    Route::post('/groups/update/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/delete/{group}', [GroupController::class, 'delete'])->name('groups.delete');
    Route::get('/groups/create/student/{group}', [GroupController::class, 'editStudent'])->name('edit.student');
    Route::post('/groups/update/student/{group}', [GroupController::class, 'createStudent'])->name('create.student');
    //!ROTTE ADMIN DASHBOARD
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard/trainer', [AdminController::class, 'dashboardTrainer'])->name('admin.dashboard.trainer');
    Route::get('/admin/dashboard/trainer/details/{trainer}', [AdminController::class, 'trainerDetails'])->name('admin.trainer.details');
    Route::get('/admin/dashboard/student', [AdminController::class, 'dashboardStudent'])->name('admin.dashboard.student');
    Route::post('/admin/dashboard/update/{student}', [AdminController::class, 'updateStudent'])->name('admin.update.student');
});
//!MIDDLEWERE TRAINER
Route::middleware(CheckTrainer::class)->group(function () {
    //!ROTTA ELIMINA TRAINER
    Route::delete('/trainer/delete/{id}', [LoginController::class, 'destroyTrainer'])->name('trainer.destroy');
    //!ROTTE DASHBOARD TRAINER
    Route::get('/trainer/dashboard', [TrainerController::class, 'dashboard'])->name('trainer.dashboard');
    Route::get('/trainer/alias/{alias}', [TrainerController::class, 'showDetails'])->name('trainer.details');
    Route::post('/trainer/student-absence/{alias}', [TrainerController::class, 'studentAbsence'])->name('student.absence');
    Route::post('/trainer/trainer-absence/{alias}', [TrainerController::class, 'aliasUpdate'])->name('alias.update');
    Route::get('/trainer/create/student/{alias}', [TrainerController::class, 'editStudent'])->name('editStudent.trainer');
    Route::post('/trainer/update/student/{alias}', [TrainerController::class, 'recoveriesStudent'])->name('createStudent.trainer');
});
//!MIDDLEWERE CORSISTA
Route::middleware(CheckStudent::class)->group(function () {
    //!ROTTE DASHBOARD CORSISTA
    Route::get('/corsista/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::post('/student/mark-absence/{alias}', [StudentController::class, 'markAbsence'])->name('student.markAbsence');
    Route::post('/student/rec-absence/{alias}', [StudentController::class, 'recAbsence'])->name('student.recAbsence');
});
