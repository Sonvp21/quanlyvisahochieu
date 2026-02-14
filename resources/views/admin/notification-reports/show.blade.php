<x-app-layout>
<div class="container-fluid py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">📊 Chi tiết báo cáo</h1>
            <div class="flex gap-3">
                <!-- Nút xóa báo cáo này -->
                <button type="button"
                        onclick="confirmDelete()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Xóa báo cáo
                </button>

                <!-- Nút quay lại -->
                <a href="{{ route('admin.notification-reports.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>

        <!-- Hidden Form -->
        <form id="delete-form"
              action="{{ route('admin.notification-reports.destroy', $report->id) }}"
              method="POST"
              style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <!-- Thông báo -->
        @if(session('success'))
        <div id="alert-success" class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 transition-all duration-500 ease-in-out">
            <div class="flex items-center justify-between">
                <div class="flex items-center flex-1">
                    <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="closeAlert('alert-success')" class="text-green-500 hover:text-green-700 ml-4 flex-shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <!-- Progress bar -->
            <div class="mt-2 h-1 bg-green-200 rounded-full overflow-hidden">
                <div id="progress-success" class="h-full bg-green-600 transition-all ease-linear" style="width: 100%"></div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div id="alert-error" class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 transition-all duration-500 ease-in-out">
            <div class="flex items-center justify-between">
                <div class="flex items-center flex-1">
                    <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
                <button onclick="closeAlert('alert-error')" class="text-red-500 hover:text-red-700 ml-4 flex-shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <!-- Progress bar -->
            <div class="mt-2 h-1 bg-red-200 rounded-full overflow-hidden">
                <div id="progress-error" class="h-full bg-red-600 transition-all ease-linear" style="width: 100%"></div>
            </div>
        </div>
        @endif

        <!-- Thông tin tổng quan -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Thời gian chạy -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-500">
                <div class="p-6">
                    <p class="text-xs font-semibold text-blue-600 uppercase mb-2">Thời gian chạy</p>
                    <p class="text-lg font-bold text-gray-800">
                        {{ $report->run_at->format('d/m/Y') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $report->run_at->format('H:i:s') }}
                    </p>
                </div>
            </div>

            <!-- Passport -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-yellow-500 cursor-pointer hover:shadow-lg transition-shadow"
                 onclick="filterByType('passport')">
                <div class="p-6">
                    <p class="text-xs font-semibold text-yellow-600 uppercase mb-2">Passport</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $report->passport_count }}</p>
                    <p class="text-sm text-gray-500">email (click để lọc)</p>
                </div>
            </div>

            <!-- Visa -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-purple-500 cursor-pointer hover:shadow-lg transition-shadow"
                 onclick="filterByType('visa')">
                <div class="p-6">
                    <p class="text-xs font-semibold text-purple-600 uppercase mb-2">Visa</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $report->visa_count }}</p>
                    <p class="text-sm text-gray-500">email (click để lọc)</p>
                </div>
            </div>

            <!-- Thời lượng -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-green-500">
                <div class="p-6">
                    <p class="text-xs font-semibold text-green-600 uppercase mb-2">Thời lượng</p>
                    <p class="text-lg font-bold text-gray-800">
                        {{ $report->duration_format }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Chi tiết email đã gửi -->
        @if($report->details && count($report->details) > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Chi tiết email đã gửi ({{ count($report->details) }} email)</h2>

                <!-- Nút xuất/in -->
                <div class="flex gap-2">
                    <button onclick="exportToCSV()"
                            class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Xuất CSV
                    </button>
                    <button onclick="window.print()"
                            class="inline-flex items-center px-3 py-1.5 bg-blue-700 text-white text-sm rounded-lg hover:bg-blue-800 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        In
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- Bộ lọc -->
                <div class="mb-4 flex gap-3 items-center">
                    <div class="flex-1">
                        <input type="text"
                               id="search-input"
                               placeholder="🔍 Tìm kiếm sinh viên hoặc email..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <select id="type-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tất cả loại</option>
                        <option value="passport">📘 Passport</option>
                        <option value="visa">📗 Visa</option>
                    </select>
                    <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="expired">❌ Đã hết hạn</option>
                        <option value="expiring">⚠️ Sắp hết hạn</option>
                    </select>
                    <button onclick="resetFilters()"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table id="details-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    STT
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                    onclick="toggleTypeFilter()">
                                    Loại 🔽
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sinh viên
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                    onclick="toggleStatusFilter()">
                                    Trạng thái 🔽
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thời gian (lúc gửi)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($report->details as $index => $detail)
                            <tr class="hover:bg-gray-50 transition-colors detail-row"
                                data-type="{{ $detail['type'] }}"
                                data-status="{{ $detail['status'] }}"
                                data-name="{{ strtolower($detail['student_name']) }}"
                                data-email="{{ strtolower($detail['student_email']) }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 row-number">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($detail['type'] === 'passport')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            📘 Passport
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            📗 Visa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $detail['student_name'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $detail['student_email'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($detail['status'] === 'expired')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            ❌ Đã hết hạn
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                            ⚠️ Sắp hết hạn
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if(isset($detail['time_detail']))
                                        @if($detail['status'] === 'expired')
                                            <span class="font-bold text-red-600">{{ $detail['time_detail'] }}</span>
                                        @else
                                            <span class="font-medium text-yellow-600">{{ $detail['time_detail'] }}</span>
                                        @endif
                                    @else
                                        @if($detail['status'] === 'expired')
                                            <span class="font-bold text-red-600">Đã hết hạn</span>
                                        @else
                                            <span class="font-medium text-gray-900">{{ $detail['days_left'] }} ngày</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Thống kê sau lọc -->
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Hiển thị <span id="showing-count" class="font-semibold text-blue-600">{{ count($report->details) }}</span> /
                        <span class="font-semibold">{{ count($report->details) }}</span> email
                    </div>
                    <div id="filter-status" class="text-sm text-gray-600"></div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-800 mb-1">Không có dữ liệu</h3>
                    <p class="text-blue-700">Không có email nào được gửi trong lần chạy này.</p>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<script>
// Tự động ẩn thông báo sau 5 giây với progress bar
function autoHideAlerts() {
    console.log('🔄 autoHideAlerts called (show page)');

    const alerts = [
        { id: 'alert-success', progressId: 'progress-success' },
        { id: 'alert-error', progressId: 'progress-error' }
    ];

    alerts.forEach(({ id, progressId }) => {
        const alert = document.getElementById(id);
        const progress = document.getElementById(progressId);

        if (alert && progress) {
            console.log(`✅ Found alert: ${id}`);

            // Bắt đầu animation progress bar
            requestAnimationFrame(() => {
                progress.style.transition = 'width 5000ms linear';
                progress.style.width = '0%';
                console.log(`📊 Progress bar started for ${id}`);
            });

            // Ẩn thông báo sau 5 giây
            setTimeout(() => {
                console.log(`🚫 Hiding alert: ${id}`);
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';

                setTimeout(() => {
                    alert.remove();
                    console.log(`🗑️ Removed alert: ${id}`);
                }, 500);
            }, 5000);
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

// Xác nhận xóa báo cáo
function confirmDelete() {
    if (confirm('⚠️ Bạn có chắc muốn xóa báo cáo ngày {{ $report->run_at->format("d/m/Y H:i") }}?\n\nHành động này không thể hoàn tác!')) {
        document.getElementById('delete-form').submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOMContentLoaded fired (show page)');

    // Kích hoạt tự động ẩn thông báo
    autoHideAlerts();

    const searchInput = document.getElementById('search-input');
    const typeFilter = document.getElementById('type-filter');
    const statusFilter = document.getElementById('status-filter');
    const rows = document.querySelectorAll('.detail-row');
    const showingCount = document.getElementById('showing-count');
    const filterStatus = document.getElementById('filter-status');

    console.log(`🔍 Found ${rows.length} detail rows`);

    // Hàm lọc
    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const typeValue = typeFilter?.value || '';
        const statusValue = statusFilter?.value || '';
        let visibleCount = 0;

        console.log(`🔎 Filtering: search="${searchTerm}", type="${typeValue}", status="${statusValue}"`);

        rows.forEach((row) => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const type = row.dataset.type || '';
            const status = row.dataset.status || '';

            const matchSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchType = !typeValue || type === typeValue;
            const matchStatus = !statusValue || status === statusValue;

            if (matchSearch && matchType && matchStatus) {
                row.style.display = '';
                visibleCount++;
                const rowNumber = row.querySelector('.row-number');
                if (rowNumber) {
                    rowNumber.textContent = visibleCount;
                }
            } else {
                row.style.display = 'none';
            }
        });

        if (showingCount) {
            showingCount.textContent = visibleCount;
        }

        console.log(`✅ Filter complete: ${visibleCount} visible rows`);

        // Cập nhật trạng thái bộ lọc
        updateFilterStatus(searchTerm, typeValue, statusValue);
    }

    // Cập nhật text hiển thị bộ lọc đang active
    function updateFilterStatus(search, type, status) {
        const filters = [];

        if (search) filters.push(`🔍 "${search}"`);
        if (type === 'passport') filters.push('📘 Passport');
        if (type === 'visa') filters.push('📗 Visa');
        if (status === 'expired') filters.push('❌ Đã hết hạn');
        if (status === 'expiring') filters.push('⚠️ Sắp hết hạn');

        if (filters.length > 0) {
            filterStatus.innerHTML = `<span class="text-blue-600 font-medium">Đang lọc: ${filters.join(', ')}</span>`;
        } else {
            filterStatus.innerHTML = '';
        }
    }

    // Gắn sự kiện
    searchInput?.addEventListener('input', filterTable);
    typeFilter?.addEventListener('change', filterTable);
    statusFilter?.addEventListener('change', filterTable);

    console.log('✅ Event listeners attached');
});

// Reset bộ lọc
function resetFilters() {
    document.getElementById('search-input').value = '';
    document.getElementById('type-filter').value = '';
    document.getElementById('status-filter').value = '';

    const event = new Event('input', { bubbles: true });
    document.getElementById('search-input').dispatchEvent(event);
}

// Lọc nhanh theo type (click vào card)
function filterByType(type) {
    console.log(`🎯 Filter by type clicked: ${type}`);
    const typeFilter = document.getElementById('type-filter');

    if (typeFilter.value === type) {
        typeFilter.value = '';
    } else {
        typeFilter.value = type;
    }

    typeFilter.dispatchEvent(new Event('change', { bubbles: true }));

    document.getElementById('details-table').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Toggle lọc theo type (click header)
function toggleTypeFilter() {
    const typeFilter = document.getElementById('type-filter');
    const currentValue = typeFilter.value;

    if (currentValue === '') {
        typeFilter.value = 'passport';
    } else if (currentValue === 'passport') {
        typeFilter.value = 'visa';
    } else {
        typeFilter.value = '';
    }

    typeFilter.dispatchEvent(new Event('change', { bubbles: true }));
}

// Toggle lọc theo status (click header)
function toggleStatusFilter() {
    const statusFilter = document.getElementById('status-filter');
    const currentValue = statusFilter.value;

    if (currentValue === '') {
        statusFilter.value = 'expired';
    } else if (currentValue === 'expired') {
        statusFilter.value = 'expiring';
    } else {
        statusFilter.value = '';
    }

    statusFilter.dispatchEvent(new Event('change', { bubbles: true }));
}

// Xuất CSV
function exportToCSV() {
    const rows = document.querySelectorAll('#details-table .detail-row');
    let csv = 'STT,Loại,Sinh viên,Email,Trạng thái,Thời gian\n';

    let visibleIndex = 0;
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            visibleIndex++;
            const cols = row.querySelectorAll('td');
            const type = cols[1].textContent.trim();
            const name = cols[2].textContent.trim();
            const email = cols[3].textContent.trim();
            const status = cols[4].textContent.trim();
            const time = cols[5].textContent.trim();

            csv += `${visibleIndex},"${type}","${name}","${email}","${status}","${time}"\n`;
        }
    });

    const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);

    link.setAttribute('href', url);
    link.setAttribute('download', 'bao-cao-email-{{ $report->run_at->format("Y-m-d-His") }}.csv');
    link.style.visibility = 'hidden';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }

    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}

.cursor-pointer:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease;
}
</style>
</x-app-layout>
