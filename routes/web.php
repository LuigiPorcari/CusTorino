<?php

use App\Http\Controllers\AliasController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckAdminOrTrainer;
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

//!ROTTE REGISTRAZIONE ADMIN
Route::get('/register/admin', [RegisterController::class, 'showAdminRegistrationForm'])->name('admin.register');
Route::post('/register/admin', [RegisterController::class, 'registerAdmin']);
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
//!MIDDLEWERE ADMIN + TRAINER
Route::middleware([CheckAdminOrTrainer::class])->group(function () {
    //!ROTTA ELIMINA TRAINER
    Route::delete('/trainer/delete/{id}', [LoginController::class, 'destroyTrainer'])->name('trainer.destroy');
    //!ROTTE MODIFICA ALIAS
    Route::post('/alias/student-absence/{alias}', [AliasController::class, 'studentAbsence'])->name('student.absence');
    Route::post('/alias/student-recoveries/{alias}', [AliasController::class, 'recoveriesStudent'])->name('student.recoveries');
    Route::post('/alias/trainer-absence/{alias}', [AliasController::class, 'aliasUpdate'])->name('alias.update');
    Route::get('/alias/details/{alias}', [AliasController::class, 'showDetails'])->name('alias.details');
    Route::get('/alias/edit/student/{alias}', [AliasController::class, 'editStudent'])->name('student.edit');
});
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
    //!ROTTE CAMBIO RUOLI TRAINER E CORSISTA
    Route::post('/admin/user/{trainer}/make-trainer-student', [AdminController::class, 'makeTrainerAndStudent'])->name('admin.user.make-trainer-student');
    //!ROTTE REGISTRAZIONE ADMIN
    // Route::get('/register/admin', [RegisterController::class, 'showAdminRegistrationForm'])->name('admin.register');
    // Route::post('/register/admin', [RegisterController::class, 'registerAdmin']);
    //!ROTTA ELIMINA ADMIN
    Route::delete('/admin/delete/{id}', [LoginController::class, 'destroyAdmin'])->name('admin.destroy');
    //!ROTTA ADMIN ELIMINA CORSISTA
    Route::delete('/student/delete/{id}', [LoginController::class, 'adminDestroyStudent'])->name('student.destroy');
    //!ROTTE REGISTRAZIONE TRAINER
    Route::get('/register/trainer', [RegisterController::class, 'showTrainerRegistrationForm'])->name('trainer.register');
    Route::post('/register/trainer', [RegisterController::class, 'registerTrainer']);
    //!ROTTE ADMIN GRUPPI
    Route::get('/admin/dashboard/group/details/{group}', [AdminController::class, 'groupDetails'])->name('admin.group.details');
    Route::post('/admin/{group}/alias/store', [AdminController::class, 'storeAlias'])->name('storeAlias');
    //!ROTTE GRUPPI
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/edit/{group}', [GroupController::class, 'edit'])->name('groups.edit');
    Route::post('/groups/update/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/delete/{group}', [GroupController::class, 'delete'])->name('groups.delete');
    Route::delete('/alias/delete/{alias}', [GroupController::class, 'deleteAlias'])->name('alias.delete');
    Route::get('/groups/create/student/{group}', [GroupController::class, 'editStudent'])->name('edit.student');
    Route::post('/groups/update/student/{group}', [GroupController::class, 'createStudent'])->name('create.student');
    //!ROTTE ADMIN DASHBOARD
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard/trainer', [AdminController::class, 'dashboardTrainer'])->name('admin.dashboard.trainer');
    Route::get('/admin/dashboard/trainer/details/{trainer}', [AdminController::class, 'trainerDetails'])->name('admin.trainer.details');
    Route::get('/admin/dashboard/student/details/{student}', [AdminController::class, 'studentDetails'])->name('admin.student.details');
    Route::get('/admin/dashboard/student', [AdminController::class, 'dashboardStudent'])->name('admin.dashboard.student');
    Route::post('/admin/dashboard/update/{student}', [AdminController::class, 'updateStudent'])->name('admin.update.student');
});
//!MIDDLEWERE TRAINER
Route::middleware(CheckTrainer::class)->group(function () {
    //!ROTTE DASHBOARD TRAINER
    Route::get('/trainer/dashboard', [TrainerController::class, 'dashboard'])->name('trainer.dashboard');
    Route::get('/trainer/dashboard/group', [TrainerController::class, 'dashboardGroup'])->name('trainer.group');
    Route::get('/trainer/dashboard/salary', [TrainerController::class, 'salary'])->name('trainer.salary');
});
//!MIDDLEWERE CORSISTA
Route::middleware(CheckStudent::class)->group(function () {
    //!ROTTE DASHBOARD CORSISTA
    Route::get('/corsista/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::post('/student/mark-absence/{alias}', [StudentController::class, 'markAbsence'])->name('student.markAbsence');
    Route::post('/student/rec-absence/{alias}', [StudentController::class, 'recAbsence'])->name('student.recAbsence');
    //!ROTTA ANNULLA OPERAZIONE
    Route::post('/student/undo-last-action', [StudentController::class, 'undoLastAction'])->name('student.undoLastAction');
});
