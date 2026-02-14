<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationReportController extends Controller
{
    public function index()
    {
        $reports = NotificationReport::latest('run_at')->paginate(20);

        $stats = [
            'today' => NotificationReport::whereDate('run_at', today())
                ->sum('total_count'),

            'this_week' => NotificationReport::whereBetween('run_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->sum('total_count'),

            'this_month' => NotificationReport::whereMonth('run_at', now()->month)
                ->whereYear('run_at', now()->year)
                ->sum('total_count'),
        ];

        return view('admin.notification-reports.index', compact('reports', 'stats'));
    }

    public function show($id)
    {
        $report = NotificationReport::findOrFail($id);
        return view('admin.notification-reports.show', compact('report'));
    }

    /**
     * Xóa một báo cáo
     */
    public function destroy($id)
    {
        try {
            $report = NotificationReport::findOrFail($id);
            $runAt = $report->run_at->format('d/m/Y H:i');

            $report->delete();

            return redirect()
                ->route('admin.notification-reports.index')
                ->with('success', "✅ Đã xóa báo cáo ngày {$runAt}");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.notification-reports.index')
                ->with('error', '❌ Không thể xóa báo cáo: ' . $e->getMessage());
        }
    }

    /**
     * Xóa nhiều báo cáo cùng lúc
     */
    /**
     * Xóa nhiều báo cáo cùng lúc
     */
    public function bulkDestroy(Request $request)
    {
        try {
            // ✅ Validate đảm bảo nhận đúng array
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'required|integer|exists:notification_reports,id'
            ], [
                'ids.required' => 'Vui lòng chọn ít nhất một báo cáo để xóa',
                'ids.array' => 'Dữ liệu không hợp lệ',
                'ids.min' => 'Vui lòng chọn ít nhất một báo cáo',
            ]);

            // ✅ Lấy IDs và ép kiểu về array (để chắc chắn)
            $ids = (array) $request->input('ids');

            // ✅ Xóa các báo cáo
            $count = NotificationReport::whereIn('id', $ids)->delete();

            return redirect()
                ->route('admin.notification-reports.index')
                ->with('success', "✅ Đã xóa thành công {$count} báo cáo");
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('admin.notification-reports.index')
                ->with('error', '⚠️ ' . $e->validator->errors()->first());
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.notification-reports.index')
                ->with('error', '❌ Không thể xóa báo cáo: ' . $e->getMessage());
        }
    }

    /**
     * Xóa các báo cáo cũ (> 30 ngày)
     */
    public function deleteOld()
    {
        try {
            $thirtyDaysAgo = now()->subDays(30);
            $count = NotificationReport::where('run_at', '<', $thirtyDaysAgo)->delete();

            return redirect()
                ->route('admin.notification-reports.index')
                ->with('success', "✅ Đã xóa {$count} báo cáo cũ (> 30 ngày)");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.notification-reports.index')
                ->with('error', '❌ Không thể xóa báo cáo cũ: ' . $e->getMessage());
        }
    }

    /**
     * Xóa TẤT CẢ báo cáo (cẩn thận!)
     */
    public function deleteAll()
    {
        try {
            $count = NotificationReport::count();
            NotificationReport::truncate();

            return redirect()
                ->route('admin.notification-reports.index')
                ->with('success', "✅ Đã xóa tất cả {$count} báo cáo");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.notification-reports.index')
                ->with('error', '❌ Không thể xóa tất cả báo cáo: ' . $e->getMessage());
        }
    }
}
