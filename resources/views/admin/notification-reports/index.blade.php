<x-app-layout>
    <div class="container-fluid py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                📊 Báo cáo gửi email nhắc nhở</h1>

            <!-- Thông báo -->
            @if (session('success'))
                <div id="alert-success"
                    class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 transition-all duration-500 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                        <button onclick="closeAlert('alert-success')"
                            class="text-green-500 hover:text-green-700 ml-4 flex-shrink-0 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Progress bar -->
                    <div class="mt-2 h-1 bg-green-200 rounded-full overflow-hidden">
                        <div id="progress-success" class="h-full bg-green-600 transition-all ease-linear"
                            style="width: 100%"></div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div id="alert-error"
                    class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 transition-all duration-500 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                        <button onclick="closeAlert('alert-error')"
                            class="text-red-500 hover:text-red-700 ml-4 flex-shrink-0 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Progress bar -->
                    <div class="mt-2 h-1 bg-red-200 rounded-full overflow-hidden">
                        <div id="progress-error" class="h-full bg-red-600 transition-all ease-linear"
                            style="width: 100%"></div>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div id="alert-warning"
                    class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4 transition-all duration-500 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <p class="text-yellow-700 font-medium">{{ session('warning') }}</p>
                        </div>
                        <button onclick="closeAlert('alert-warning')"
                            class="text-yellow-500 hover:text-yellow-700 ml-4 flex-shrink-0 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Progress bar -->
                    <div class="mt-2 h-1 bg-yellow-200 rounded-full overflow-hidden">
                        <div id="progress-warning" class="h-full bg-yellow-600 transition-all ease-linear"
                            style="width: 100%"></div>
                    </div>
                </div>
            @endif

            <!-- Thống kê tổng quan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Hôm nay -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-blue-600 uppercase mb-2">Hôm nay</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['today'] }}</p>
                                <p class="text-sm text-gray-500">email</p>
                            </div>
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tuần này -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-green-600 uppercase mb-2">Tuần này</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['this_week'] }}</p>
                                <p class="text-sm text-gray-500">email</p>
                            </div>
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tháng này -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-purple-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-purple-600 uppercase mb-2">Tháng này</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['this_month'] }}</p>
                                <p class="text-sm text-gray-500">email</p>
                            </div>
                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng danh sách báo cáo -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                    <h2 class="text-xl font-semibold text-white">Lịch sử gửi email</h2>
                </div>

                <div class="p-6">
                    <!-- Toolbar xóa hàng loạt -->
                    @if ($reports->count() > 0)
                        <div class="mb-4 flex flex-wrap gap-3">
                            <!-- Xóa các mục đã chọn -->
                            <button type="button" onclick="confirmBulkDelete()"
                                class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                id="bulk-delete-btn" disabled>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Xóa đã chọn (<span id="selected-count">0</span>)
                            </button>

                            <!-- Xóa báo cáo cũ (> 30 ngày) -->
                            <button type="button" onclick="confirmDeleteOld()"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Xóa báo cáo cũ (>30 ngày)
                            </button>

                            <!-- Xóa tất cả -->
                            <button type="button" onclick="confirmDeleteAll()"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                Xóa tất cả
                            </button>
                        </div>

                        <!-- Hidden Forms -->
                        <form id="bulk-delete-form" action="{{ route('admin.notification-reports.bulk-destroy') }}"
                            method="POST" style="display: none;">
                            @csrf
                            {{-- <input type="hidden" name="ids" id="selected-ids"> --}}
                        </form>

                        <form id="delete-old-form" action="{{ route('admin.notification-reports.delete-old') }}"
                            method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        <form id="delete-all-form" action="{{ route('admin.notification-reports.delete-all') }}"
                            method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @if ($reports->count() > 0)
                                        <th class="px-6 py-3 text-left">
                                            <input type="checkbox" id="select-all"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </th>
                                    @endif
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Thời gian chạy
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Passport
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Visa
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tổng
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Thời lượng
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reports as $report)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox"
                                                class="report-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                value="{{ $report->id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $report->run_at->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($report->passport_count > 0)
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $report->passport_count }} email
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($report->visa_count > 0)
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $report->visa_count }} email
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            {{ $report->total_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->duration_format }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex gap-2">
                                                <!-- Nút xem chi tiết -->
                                                <a href="{{ route('admin.notification-reports.show', $report->id) }}"
                                                    class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Chi tiết
                                                </a>

                                                <!-- Nút xóa -->
                                                <form
                                                    action="{{ route('admin.notification-reports.destroy', $report->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('⚠️ Bạn có chắc muốn xóa báo cáo ngày {{ $report->run_at->format('d/m/Y H:i') }}?\n\nHành động này không thể hoàn tác!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                        Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                                <p class="text-gray-500 text-lg">Chưa có báo cáo nào</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($reports->hasPages())
                        <div class="mt-6">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        // Tự động ẩn thông báo sau 5 giây với progress bar
        function autoHideAlerts() {
            console.log('🔄 autoHideAlerts called'); // Debug log

            const alerts = [{
                    id: 'alert-success',
                    progressId: 'progress-success'
                },
                {
                    id: 'alert-error',
                    progressId: 'progress-error'
                },
                {
                    id: 'alert-warning',
                    progressId: 'progress-warning'
                }
            ];

            alerts.forEach(({
                id,
                progressId
            }) => {
                const alert = document.getElementById(id);
                const progress = document.getElementById(progressId);

                if (alert && progress) {
                    console.log(`✅ Found alert: ${id}`); // Debug log

                    // Bắt đầu animation progress bar ngay lập tức
                    requestAnimationFrame(() => {
                        progress.style.transition = 'width 5000ms linear';
                        progress.style.width = '0%';
                        console.log(`📊 Progress bar started for ${id}`); // Debug log
                    });

                    // Ẩn thông báo sau 5 giây
                    setTimeout(() => {
                        console.log(`🚫 Hiding alert: ${id}`); // Debug log
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateX(100%)';

                        // Xóa hoàn toàn sau animation
                        setTimeout(() => {
                            alert.remove();
                            console.log(`🗑️ Removed alert: ${id}`); // Debug log
                        }, 500);
                    }, 5000);
                } else {
                    console.log(`❌ Alert not found: ${id}`); // Debug log
                }
            });
        }

        // Đóng thông báo thủ công
        function closeAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Kích hoạt tự động ẩn thông báo
            autoHideAlerts();

            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.report-checkbox');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            const selectedCount = document.getElementById('selected-count');
            const selectedIds = document.getElementById('selected-ids');

            if (!selectAll || checkboxes.length === 0) return;

            // Chọn tất cả
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkDeleteButton();
            });

            // Cập nhật khi chọn từng checkbox
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    updateBulkDeleteButton();
                    updateSelectAllState();
                });
            });

            function updateBulkDeleteButton() {
                const selected = Array.from(checkboxes).filter(cb => cb.checked);
                const count = selected.length;

                selectedCount.textContent = count;
                bulkDeleteBtn.disabled = count === 0;

                // ✅ Xóa tất cả input cũ
                const form = document.getElementById('bulk-delete-form');
                form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());

                // ✅ Chỉ thêm input khi có checkbox được chọn
                if (count > 0) {
                    selected.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]'; // Quan trọng: phải có []
                        input.value = cb.value;
                        form.appendChild(input);
                    });
                }
            }

            function updateSelectAllState() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const someChecked = Array.from(checkboxes).some(cb => cb.checked);

                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked && !allChecked;
            }
        });

        // Xác nhận xóa các mục đã chọn
        function confirmBulkDelete() {
            const count = document.getElementById('selected-count').textContent;

            if (confirm(
                    `⚠️ BẠN CÓ CHẮC CHẮN?\n\n📊 Số lượng: ${count} báo cáo\n🗑️ Hành động: Xóa vĩnh viễn\n⚠️ Cảnh báo: Không thể hoàn tác!\n\n➡️ Nhấn OK để xác nhận xóa`
                )) {
                document.getElementById('bulk-delete-form').submit();
            }
        }

        // Xác nhận xóa báo cáo cũ (>30 ngày)
        function confirmDeleteOld() {
            if (confirm(
                    `⚠️ XÓA BÁO CÁO CŨ\n\n📅 Thời gian: Cũ hơn 30 ngày\n🗑️ Hành động: Xóa vĩnh viễn tất cả báo cáo cũ\n⚠️ Cảnh báo: Không thể hoàn tác!\n\n➡️ Bạn có chắc chắn muốn tiếp tục?`
                )) {
                document.getElementById('delete-old-form').submit();
            }
        }

        // Xác nhận xóa tất cả
        function confirmDeleteAll() {
            const firstConfirm = confirm(
                `🚨 CẢNH BÁO NGHIÊM TRỌNG 🚨\n\n❌ XÓA TẤT CẢ BÁO CÁO\n\n⚠️ Hành động này sẽ:\n• Xóa toàn bộ lịch sử báo cáo\n• Xóa tất cả dữ liệu thống kê\n• KHÔNG THỂ KHÔI PHỤC\n\n➡️ Bạn có CHẮC CHẮN muốn xóa TẤT CẢ?`
            );

            if (firstConfirm) {
                const secondConfirm = confirm(
                    `🔴 XÁC NHẬN LẦN CUỐI\n\n⚠️ Đây là cơ hội cuối cùng để hủy bỏ!\n\n❌ Bạn THỰC SỰ muốn xóa TẤT CẢ báo cáo?\n\n(Nhấn OK để xác nhận xóa, Cancel để hủy)`
                );

                if (secondConfirm) {
                    document.getElementById('delete-all-form').submit();
                }
            }
        }
    </script>

</x-app-layout>
