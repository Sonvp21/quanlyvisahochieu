<x-app-layout>
<div class="p-6 max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Danh sách Hộ chiếu</h1>
            <p class="text-sm text-slate-500 mt-0.5">Quản lý hộ chiếu của tất cả sinh viên</p>
        </div>
    </div>

    <x-alert-success />

    {{-- FILTER --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <form method="GET" action="{{ route('admin.passports.index') }}">
            <div class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-[220px] relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Tìm số hộ chiếu, tên sinh viên..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <select name="status" onchange="this.form.submit()"
                    class="px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white min-w-[160px]">
                    <option value="">Tất cả trạng thái</option>
                    <option value="valid"         {{ request('status')=='valid'        ?'selected':'' }}>Còn hạn</option>
                    <option value="expiring_soon" {{ request('status')=='expiring_soon'?'selected':'' }}>Sắp hết hạn</option>
                    <option value="expired"       {{ request('status')=='expired'      ?'selected':'' }}>Đã hết hạn</option>
                </select>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    Tìm kiếm
                </button>
                @if(request('search') || request('status'))
                <a href="{{ route('admin.passports.index') }}"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-lg transition-colors">
                    Đặt lại
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-10">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Sinh viên</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Số hộ chiếu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Quốc gia cấp</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Ngày hết hạn</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Cập nhật bởi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($passports as $index => $passport)
                    @php
                        $student = $passport->student;
                        $pStatus = $student ? $student->getPassportStatus() : 'none';
                        $pColor  = $student ? $student->getPassportStatusColor() : 'gray';
                        $badgeClass = match($pColor) {
                            'green'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'yellow' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'red'    => 'bg-red-100 text-red-700 border-red-200',
                            default  => 'bg-slate-100 text-slate-500 border-slate-200',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-xs text-slate-400">
                            {{ ($passports->currentPage()-1) * $passports->perPage() + $index + 1 }}
                        </td>
                        <td class="px-4 py-3">
                            @if($student)
                            <a href="{{ route('admin.students.show', $student) }}"
                                class="flex items-center gap-2.5 group">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800 group-hover:text-blue-600 transition-colors text-sm">{{ $student->full_name }}</div>
                                    <div class="text-xs text-slate-400">{{ $student->student_code }}</div>
                                </div>
                            </a>
                            @else
                            <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-mono text-sm font-semibold text-slate-700">{{ $passport->passport_number }}</span>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-sm text-slate-600">
                            {{ $passport->country_of_issue ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-sm font-medium text-slate-700">{{ date('d/m/Y', strtotime($passport->expiry_date)) }}</div>
                            @php
                                $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($passport->expiry_date)->endOfDay(), false);
                            @endphp
                            <div class="text-[10px] {{ $daysLeft < 0 ? 'text-red-500' : ($daysLeft <= 30 ? 'text-amber-500' : 'text-slate-400') }}">
                                {{ $daysLeft < 0 ? 'Đã hết hạn '.abs((int)$daysLeft).' ngày' : 'Còn '.(int)$daysLeft.' ngày' }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($student)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                {{ $student->getPassportStatusText() }}
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center hidden sm:table-cell">
                            <span class="text-xs text-slate-500">
                                {{ $passport->last_updated_by === 'admin' ? 'Admin' : 'Sinh viên' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                @if($student)
                                <a href="{{ route('admin.students.show', $student) }}"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors" title="Xem sinh viên">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.students.edit', $student) }}"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition-colors" title="Chỉnh sửa">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="font-medium text-slate-600">Không tìm thấy hộ chiếu</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($passports->hasPages())
    <div class="flex justify-center">{{ $passports->links() }}</div>
    @endif

</div>
</x-app-layout>