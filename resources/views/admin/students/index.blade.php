<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-2">

            {{-- BREADCRUMB & HEADER --}}
            <div class="mb-6">
                {{-- Breadcrumb --}}
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dashboard') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                    </path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500">Quản lý sinh viên</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                    Danh sách sinh viên
                                </h1>
                                <p class="text-sm text-gray-500 mt-0.5">Quản lý thông tin sinh viên, hộ chiếu và visa</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.students.create') }}"
                        class="group inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Thêm sinh viên</span>
                    </a>
                </div>
            </div>

            {{-- SUCCESS MESSAGE --}}
            <x-alert-success />

            {{-- FILTER & SEARCH BAR --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 mb-6">
                <form method="GET" action="{{ route('admin.students.index') }}" id="filterForm">
                    <div class="flex flex-wrap items-end gap-3">
                        {{-- TÌM KIẾM --}}
                        <div class="flex-1 min-w-[250px]">
                            <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Tìm kiếm
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nhập tên, mã SV hoặc email..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>

                        {{-- LỌC THEO TRẠNG THÁI --}}
                        <div class="w-full sm:w-[240px]">
                            <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Trạng thái
                            </label>
                            <select name="filter" onchange="this.form.submit()"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Tất cả trạng thái</option>
                                <option value="passport_expiring" {{ request('filter') == 'passport_expiring' ? 'selected' : '' }}>
                                    📘 Hộ chiếu sắp hết hạn
                                </option>
                                <option value="passport_expired" {{ request('filter') == 'passport_expired' ? 'selected' : '' }}>
                                    📕 Hộ chiếu đã hết hạn
                                </option>
                                <option value="visa_expiring" {{ request('filter') == 'visa_expiring' ? 'selected' : '' }}>
                                    📗 Visa sắp hết hạn
                                </option>
                                <option value="visa_expired" {{ request('filter') == 'visa_expired' ? 'selected' : '' }}>
                                    📕 Visa đã hết hạn
                                </option>
                                <option value="recently_updated" {{ request('filter') == 'recently_updated' ? 'selected' : '' }}>
                                    🆕 Cập nhật gần đây
                                </option>
                            </select>
                        </div>

                        {{-- SẮP XẾP THEO --}}
                        <div class="w-full sm:w-[200px]">
                            <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                </svg>
                                Sắp xếp
                            </label>
                            <select name="sort" onchange="this.form.submit()"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            </select>
                        </div>

                        {{-- BUTTONS --}}
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold transition-all shadow-md hover:shadow-lg">
                            Tìm kiếm
                        </button>

                        <button type="button" onclick="window.location.href='{{ route('admin.students.index') }}'"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg font-semibold transition-all">
                            Đặt lại
                        </button>
                    </div>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap" style="width: 50px;">
                                    STT
                                </th>
                                <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap" style="width: 280px;">
                                    Họ và tên
                                </th>
                                <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap" style="width: 150px;">
                                    Mã sinh viên
                                </th>
                                <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap" style="width: 220px;">
                                    Email
                                </th>
                                <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap" style="width: 200px;">
                                    Hộ chiếu
                                </th>
                                <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap" style="width: 200px;">
                                    Visa
                                </th>
                                <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap" style="width: 120px;">
                                    Ngày tạo
                                </th>
                                <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap" style="width: 180px;">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($students as $index => $student)
                                <tr class="hover:bg-blue-50/50 transition-colors duration-150">
                                    {{-- STT --}}
                                    <td class="px-4 py-4">
                                        <div class="w-8 h-8 bg-gradient-to-br from-white-500 to-white-600 rounded-lg flex items-center justify-center">
                                            <span class="text-black text-sm font-bold">
                                                {{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- HỌ TÊN --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-bold text-sm">
                                                    {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-semibold text-gray-900 truncate">
                                                    {{ $student->full_name }}
                                                </div>
                                                @if ($student->student_type)
                                                    <div class="mt-1">
                                                        @if ($student->student_type == 'exchange')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-700">
                                                                Trao đổi
                                                            </span>
                                                        @elseif($student->student_type == 'regular')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-700">
                                                                Chính quy
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-purple-100 text-purple-700">
                                                                Sau ĐH
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- MÃ SINH VIÊN --}}
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-gray-100 text-gray-700 font-mono text-sm font-medium">
                                            {{ $student->student_code }}
                                        </span>
                                    </td>

                                    {{-- EMAIL --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="truncate">{{ $student->user->email ?? 'N/A' }}</span>
                                        </div>
                                    </td>

                                    {{-- HỘ CHIẾU --}}
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col items-center gap-1.5">
                                            @php
                                                $passportStatus = $student->getPassportStatus();
                                                $badgeClass = match ($student->getPassportStatusColor()) {
                                                    'green' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'yellow' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                    'red' => 'bg-red-100 text-red-700 border-red-200',
                                                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                                {{ $student->getPassportStatusText() }}
                                            </span>
                                            @if ($student->passport && $student->passport->expiry_date)
                                                <div id="passport-countdown-{{ $student->id }}"
                                                    data-expiry="{{ \Carbon\Carbon::parse($student->passport->expiry_date)->endOfDay()->timestamp * 1000 }}"
                                                    class="text-xs font-medium">
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- VISA --}}
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col items-center gap-1.5">
                                            @php
                                                $visaStatus = $student->getVisaStatus();
                                                $badgeClass = match ($student->getVisaStatusColor()) {
                                                    'green' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'yellow' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                    'red' => 'bg-red-100 text-red-700 border-red-200',
                                                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                                {{ $student->getVisaStatusText() }}
                                            </span>
                                            @if ($student->visa && $student->visa->expiry_date)
                                                <div id="visa-countdown-{{ $student->id }}"
                                                    data-expiry="{{ \Carbon\Carbon::parse($student->visa->expiry_date)->endOfDay()->timestamp * 1000 }}"
                                                    class="text-xs font-medium">
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- NGÀY TẠO --}}
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex flex-col items-center gap-0.5">
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $student->created_at->format('d/m/Y') }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $student->created_at->format('H:i') }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- HÀNH ĐỘNG --}}
                                    <td class="px-4 py-4">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.students.show', $student) }}"
                                                class="group relative inline-flex items-center justify-center p-2 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-all hover:scale-110"
                                                title="Xem chi tiết">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student) }}"
                                                class="group relative inline-flex items-center justify-center p-2 bg-amber-100 hover:bg-amber-200 text-amber-600 rounded-lg transition-all hover:scale-110"
                                                title="Chỉnh sửa">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                                                class="inline" onsubmit="return confirm('⚠️ Bạn có chắc chắn muốn xóa sinh viên này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="group relative inline-flex items-center justify-center p-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-all hover:scale-110"
                                                    title="Xóa">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 font-medium">Không có dữ liệu</p>
                                                <p class="text-gray-400 text-sm mt-1">Chưa có sinh viên nào trong hệ thống</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

            </div>

            {{-- PAGINATION --}}
            @if ($students->hasPages())
                <div class="mt-6">
                    {{ $students->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- COUNTDOWN SCRIPT --}}
    <script>
        function startCountdown(el) {
            const expiry = Number(el.dataset.expiry);

            function update() {
                let diff = expiry - Date.now();
                const expired = diff < 0;
                diff = Math.abs(diff);

                const d = Math.floor(diff / 86400000);
                const h = Math.floor((diff % 86400000) / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                const s = Math.floor((diff % 60000) / 1000);

                el.textContent = `${expired ? '⚠️ Hết hạn' : '⏱️ Còn'} ${d}d ${h}h ${m}m ${s}s`;


                if (expired) {
                    el.className = 'text-xs font-bold text-red-600';
                } else if (d < 30) {
                    el.className = 'còn text-xs font-bold text-amber-600';
                } else {
                    el.className = 'text-xs font-bold text-emerald-600';
                }
            }

            update();
            setInterval(update, 1000);
        }

        document.querySelectorAll('[data-expiry]').forEach(startCountdown);
    </script>
</x-app-layout>
