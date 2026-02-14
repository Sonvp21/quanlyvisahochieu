<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Passport;
use App\Models\Visa;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê cơ bản
        $totalStudents = Student::count();
        $totalUsers = User::count();

        // ==================== PASSPORT STATISTICS ====================


        $today = Carbon::today();          // 00:00:00 hôm nay
        $limit = Carbon::today()->addDays(29); // hết ngày thứ 30

        /*
|--------------------------------
| PASSPORT
|--------------------------------
*/

        // Đã hết hạn (trước hôm nay)
        $passportExpired = Passport::whereDate('expiry_date', '<', $today)->count();

        // Sắp hết hạn (hôm nay → 30 ngày)
        $passportExpiringSoon = Passport::whereDate('expiry_date', '>=', $today)
            ->whereDate('expiry_date', '<=', $limit)
            ->count();

        // Còn hạn (trên 30 ngày)
        $passportValid = Passport::whereDate('expiry_date', '>', $limit)->count();


        /*
|--------------------------------
| VISA
|--------------------------------
*/

        // Đã hết hạn
        $visaExpired = Visa::whereDate('expiry_date', '<', $today)->count();

        // Sắp hết hạn
        $visaExpiringSoon = Visa::whereDate('expiry_date', '>=', $today)
            ->whereDate('expiry_date', '<=', $limit)
            ->count();

        // Còn hạn
        $visaValid = Visa::whereDate('expiry_date', '>', $limit)->count();


        // ==================== RECENT UPDATES ====================

        // Sinh viên vừa cập nhật trong 7 ngày gần đây
        $recentlyUpdatedStudents = Student::where('updated_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // Passport vừa cập nhật trong 7 ngày
        $recentlyUpdatedPassports = Passport::where('updated_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // Visa vừa cập nhật trong 7 ngày
        $recentlyUpdatedVisas = Visa::where('updated_at', '>=', Carbon::now()->subDays(7))
            ->count();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalUsers',
            'passportExpiringSoon',
            'passportExpired',
            'passportValid',
            'visaExpiringSoon',
            'visaExpired',
            'visaValid',
            'recentlyUpdatedStudents',
            'recentlyUpdatedPassports',
            'recentlyUpdatedVisas'
        ));
    }
}
