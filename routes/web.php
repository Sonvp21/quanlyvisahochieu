<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


// Dashboard
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;

// ADMIN
use App\Http\Controllers\Admin\StudentController as AdminStudent;
use App\Http\Controllers\Admin\PassportController as AdminPassport;
use App\Http\Controllers\Admin\VisaController as AdminVisa;

// STUDENT
use App\Http\Controllers\Student\ProfilesController;
use App\Http\Controllers\Student\PassportController as StudentPassport;
use App\Http\Controllers\Student\VisaController as StudentVisa;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('admin.main');
})->name('home');
/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT THEO ROLE
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Auth;

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    }


    abort(403, 'Không có quyền truy cập');
})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');

        // =========================
        // CRUD SINH VIÊN
        // =========================
        Route::resource('students', AdminStudent::class);
        /*
            admin.students.index
            admin.students.create
            admin.students.store
            admin.students.show      -> xem chi tiết (student + passport + visa)
            admin.students.edit
            admin.students.update
            admin.students.destroy
        */

        // =========================
        // CRUD PASSPORT
        // =========================
        Route::resource('passports', AdminPassport::class);
        /*
            admin.passports.index
            admin.passports.create
            admin.passports.store
            admin.passports.edit
            admin.passports.update
            admin.passports.destroy
        */

        // =========================
        // CRUD VISA (chuẩn bị làm tiếp)
        // =========================
        Route::resource('visas', AdminVisa::class);
    });


/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Dashboard sinh viên
        Route::get('/dashboard', [StudentDashboard::class, 'index'])
            ->name('dashboard');

        // PROFILE GỘP: thông tin SV + Passport + Visa
        Route::get('/profile', [ProfilesController::class, 'show'])
            ->name('profile.show');

        // Update thông tin sinh viên
        Route::put('/profile/student', [ProfilesController::class, 'updateStudent'])
            ->name('profile.student.update');

        // Update passport
        Route::put('/profile/passport', [StudentPassport::class, 'update'])
            ->name('profile.passport.update');

        // Update visa
        Route::put('/profile/visa', [StudentVisa::class, 'update'])
            ->name('profile.visa.update');
    });

use App\Http\Controllers\Admin\NotificationReportController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {


    // XÓA NHIỀU BÁO CÁO (bulk delete)
    Route::post('/notification-reports/bulk-destroy', [NotificationReportController::class, 'bulkDestroy'])
        ->name('notification-reports.bulk-destroy');

    // XÓA BÁO CÁO CŨ (> 30 ngày)
    Route::delete('/notification-reports/delete-old', [NotificationReportController::class, 'deleteOld'])
        ->name('notification-reports.delete-old');

    // XÓA TẤT CẢ BÁO CÁO
    Route::delete('/notification-reports/delete-all', [NotificationReportController::class, 'deleteAll'])
        ->name('notification-reports.delete-all');

    // Danh sách báo cáo
    Route::get('/notification-reports', [NotificationReportController::class, 'index'])
        ->name('notification-reports.index');

    // Chi tiết một báo cáo
    Route::get('/notification-reports/{id}', [NotificationReportController::class, 'show'])
        ->name('notification-reports.show');

    // XÓA MỘT BÁO CÁO CỤ THỂ
    Route::delete('/notification-reports/{id}', [NotificationReportController::class, 'destroy'])
        ->name('notification-reports.destroy');
});

/*
|--------------------------------------------------------------------------
| PROFILE MẶC ĐỊNH (BREEZE / AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


require __DIR__ . '/auth.php';
