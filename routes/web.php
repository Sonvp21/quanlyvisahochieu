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


use App\Http\Controllers\Admin\DelegationController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MailRuleController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\AnnualPlanController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\AgreementController;
use App\Http\Controllers\Admin\ExchangeProgramController;
use App\Http\Controllers\Admin\ExchangeApplicationController;
use App\Http\Controllers\TripExpenseController;
use App\Http\Controllers\Admin\PersonDocumentController;
use App\Http\Controllers\TripController;
use App\Http\Middleware\RoleAny;
use App\Http\Controllers\Admin\PersonController;
use App\Http\Controllers\Admin\DelegationExportController;
use App\Http\Controllers\Admin\DelegationWordExportController;
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

    return redirect()->route(in_array(auth()->user()->role, ['admin', 'bgh', 'htqt'], true) ? 'overview' : 'trips.index');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleAny::class . ':admin,htqt'])
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

        // Cơ cấu đơn vị & hồ sơ nhân sự/khách
        Route::resource('units', UnitController::class)->except('show');
        Route::resource('users', UserController::class)->except('show')->middleware(RoleAny::class . ':admin');
        Route::resource('people', PersonController::class)->except('show')->parameters(['people' => 'person']);
        Route::post('people/{person}/passports', [PersonDocumentController::class, 'storePassport'])->name('people.passports.store');
        Route::post('people/{person}/visas', [PersonDocumentController::class, 'storeVisa'])->name('people.visas.store');
        Route::delete('people/{person}/passports/{passport}', [PersonDocumentController::class, 'destroyPassport'])->name('people.passports.destroy');
        Route::delete('people/{person}/visas/{visa}', [PersonDocumentController::class, 'destroyVisa'])->name('people.visas.destroy');

        Route::middleware(RoleAny::class . ':admin')->group(function () {
            Route::get('mail-rules', [MailRuleController::class, 'index'])->name('mail-rules.index');
            Route::get('mail-rules/{mailRule}/edit', [MailRuleController::class, 'edit'])->name('mail-rules.edit');
            Route::put('mail-rules/{mailRule}', [MailRuleController::class, 'update'])->name('mail-rules.update');
            Route::patch('mail-rules/{mailRule}/toggle', [MailRuleController::class, 'toggle'])->name('mail-rules.toggle');
            Route::get('mail-logs', [MailRuleController::class, 'logs'])->name('mail-logs.index');
            Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        });

        // Đối tác, thỏa thuận, trao đổi sinh viên
        Route::resource('partners', PartnerController::class);
        Route::get('agreements', [AgreementController::class, 'index'])->name('agreements.index');
        Route::post('partners/{partner}/agreements', [AgreementController::class, 'store'])->name('partners.agreements.store');
        Route::patch('agreements/{agreement}/terminate', [AgreementController::class, 'terminate'])->name('agreements.terminate');
        Route::delete('agreements/{agreement}', [AgreementController::class, 'destroy'])->name('agreements.destroy');
        Route::get('agreements/{agreement}/file', [AgreementController::class, 'download'])->name('agreements.download');
        Route::resource('exchange-programs', ExchangeProgramController::class);
        Route::post('exchange-programs/{exchangeProgram}/applications', [ExchangeApplicationController::class, 'store'])->name('exchange-programs.applications.store');
        Route::patch('exchange-applications/{application}', [ExchangeApplicationController::class, 'update'])->name('exchange-applications.update');
        Route::delete('exchange-applications/{application}', [ExchangeApplicationController::class, 'destroy'])->name('exchange-applications.destroy');
        Route::post('exchange-applications/{application}/trip', [ExchangeApplicationController::class, 'toTrip'])->name('exchange-applications.trip');

        Route::resource('delegations', DelegationController::class);
        Route::get('delegations/{delegation}/export', [DelegationExportController::class, 'export'])
            ->name('delegations.export');
        Route::get('delegations/{delegation}/export-word', [DelegationWordExportController::class, 'export'])
            ->name('delegations.export-word');
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
| CHUYẾN CÔNG TÁC NƯỚC NGOÀI (đoàn ra) — mọi vai trò trừ sinh viên quốc tế
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleAny::class . ':admin,bgh,htqt,unit_manager,staff'])
    ->prefix('trips')
    ->name('trips.')
    ->group(function () {
        Route::get('/', [TripController::class, 'index'])->name('index');
        Route::get('/create', [TripController::class, 'create'])->name('create');
        Route::post('/', [TripController::class, 'store'])->name('store');

        Route::get('/files/{file}', [TripController::class, 'downloadFile'])->name('files.download');
        Route::delete('/files/{file}', [TripController::class, 'deleteFile'])->name('files.delete');

        Route::get('/{trip}', [TripController::class, 'show'])->name('show');
        Route::get('/{trip}/edit', [TripController::class, 'edit'])->name('edit');
        Route::put('/{trip}', [TripController::class, 'update'])->name('update');
        Route::delete('/{trip}', [TripController::class, 'destroy'])->name('destroy');
        Route::post('/{trip}/submit', [TripController::class, 'submit'])->name('submit');
        Route::post('/{trip}/decide', [TripController::class, 'decide'])->name('decide');
        Route::get('/{trip}/report', [TripController::class, 'reportForm'])->name('report');
        Route::post('/{trip}/report', [TripController::class, 'reportStore'])->name('report.store');
        Route::post('/{trip}/files', [TripController::class, 'uploadFile'])->name('files.upload');
        Route::post('/people', [TripController::class, 'quickPerson'])->name('people.quick');
        Route::get('/{trip}/cong-van', [TripController::class, 'exportWord'])->name('export.word');
        Route::get('/{trip}/phu-luc', [TripController::class, 'exportExcel'])->name('export.excel');
        Route::get('/{trip}/van-ban/{doc}', [TripController::class, 'document'])->name('document');
        Route::post('/{trip}/expenses', [TripExpenseController::class, 'store'])->name('expenses.store');
        Route::delete('/expenses/{expense}', [TripExpenseController::class, 'destroy'])->name('expenses.destroy');
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

// Tổng quan, thống kê, kế hoạch năm (BGH xem; admin và phòng chuyên trách được sửa kế hoạch)
Route::middleware(['auth', RoleAny::class . ':admin,bgh,htqt'])->group(function () {
    Route::get('/tong-quan', [OverviewController::class, 'index'])->name('overview');
    Route::get('/thong-ke', [StatsController::class, 'index'])->name('stats.index');
    Route::get('/thong-ke/xuat', [StatsController::class, 'export'])->name('stats.export');
    Route::get('/ke-hoach', [AnnualPlanController::class, 'index'])->name('plans.index');
    Route::post('/ke-hoach', [AnnualPlanController::class, 'store'])->name('plans.store');
    Route::patch('/ke-hoach/{plan}', [AnnualPlanController::class, 'update'])->name('plans.update');
    Route::delete('/ke-hoach/{plan}', [AnnualPlanController::class, 'destroy'])->name('plans.destroy');
    Route::post('/ke-hoach/{plan}/ho-so', [AnnualPlanController::class, 'toTrip'])->name('plans.trip');
});
