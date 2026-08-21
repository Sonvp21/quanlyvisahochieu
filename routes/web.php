<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

// Dashboard
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;

// ADMIN
use App\Http\Controllers\Admin\StudentController as AdminStudent;
use App\Http\Controllers\Admin\PassportController as AdminPassport;
use App\Http\Controllers\Admin\VisaController as AdminVisa;
use App\Http\Controllers\Admin\NotificationReportController;
use App\Http\Controllers\Admin\ResidenceController;
use App\Http\Controllers\Admin\StudentExportController;
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

/*
|--------------------------------------------------------------------------
| DASHBOARD — redirect theo role
|--------------------------------------------------------------------------
*/
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
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Xuất Excel danh sách sinh viên (đặt trước resource để không bị {student} nuốt mất)
        Route::get('/students/export', [StudentExportController::class, 'export'])->name('students.export');

        // Cập nhật thông tin tạm trú cho 1 sinh viên
        Route::put('/students/{student}/residence', [ResidenceController::class, 'update'])->name('students.residence.update');
        // Sinh viên
        Route::resource('students', AdminStudent::class);

        // Hộ chiếu
        Route::resource('passports', AdminPassport::class);

        // Visa
        Route::resource('visas', AdminVisa::class);

        // Báo cáo email — custom routes trước resource để tránh conflict
        Route::post('/notification-reports/bulk-destroy',  [NotificationReportController::class, 'bulkDestroy'])->name('notification-reports.bulk-destroy');
        Route::delete('/notification-reports/delete-old',  [NotificationReportController::class, 'deleteOld'])->name('notification-reports.delete-old');
        Route::delete('/notification-reports/delete-all',  [NotificationReportController::class, 'deleteAll'])->name('notification-reports.delete-all');
        Route::get('/notification-reports',                [NotificationReportController::class, 'index'])->name('notification-reports.index');
        Route::get('/notification-reports/{id}',           [NotificationReportController::class, 'show'])->name('notification-reports.show');
        Route::delete('/notification-reports/{id}',        [NotificationReportController::class, 'destroy'])->name('notification-reports.destroy');
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

        // Dashboard
        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

        // Profile gộp (thông tin SV + Passport + Visa)
        Route::get('/profile',          [ProfilesController::class, 'show'])->name('profile.show');
        Route::put('/profile/student',  [ProfilesController::class, 'updateStudent'])->name('profile.student.update');
        Route::put('/profile/passport', [StudentPassport::class, 'update'])->name('profile.passport.update');
        Route::put('/profile/visa',     [StudentVisa::class, 'update'])->name('profile.visa.update');

        // Trang hộ chiếu riêng (student/passport.blade.php)
        Route::get('/passport', [StudentPassport::class, 'index'])->name('passport.index');
    });

/*
|--------------------------------------------------------------------------
| PROFILE — Breeze/Auth mặc định
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
