<x-app-layout>
<div class="p-6 max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Danh sách sinh viên</h1>
            <p class="text-sm text-slate-500 mt-0.5">Quản lý thông tin sinh viên, hộ chiếu và visa</p>
        </div>
        <a href="{{ route('admin.students.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Thêm sinh viên
        </a>
    </div>

    <x-alert-success />

    {{-- FILTER BAR --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <form method="GET" action="{{ route('admin.students.index') }}">
            <div class="flex flex-wrap gap-3">
                {{-- Search --}}
                <div class="flex-1 min-w-[220px] relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Tìm tên, mã SV, email..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"/>
                </div>

                {{-- Filter --}}
                <select name="filter" onchange="this.form.submit()"
                    class="px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white min-w-[180px]">
                    <option value="">Tất cả trạng thái</option>
                    <option value="passport_expiring" {{ request('filter')=='passport_expiring'?'selected':'' }}>Hộ chiếu sắp hết hạn</option>
                    <option value="passport_expired"  {{ request('filter')=='passport_expired' ?'selected':'' }}>Hộ chiếu đã hết hạn</option>
                    <option value="visa_expiring"     {{ request('filter')=='visa_expiring'    ?'selected':'' }}>Visa sắp hết hạn</option>
                    <option value="visa_expired"      {{ request('filter')=='visa_expired'     ?'selected':'' }}>Visa đã hết hạn</option>
                    <option value="recently_updated"  {{ request('filter')=='recently_updated' ?'selected':'' }}>Cập nhật gần đây</option>
                </select>

                {{-- Sort --}}
                <select name="sort" onchange="this.form.submit()"
                    class="px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white min-w-[140px]">
                    <option value="latest"    {{ request('sort')=='latest'   ?'selected':'' }}>Mới nhất</option>
                    <option value="oldest"    {{ request('sort')=='oldest'   ?'selected':'' }}>Cũ nhất</option>
                    <option value="name_asc"  {{ request('sort')=='name_asc' ?'selected':'' }}>Tên A-Z</option>
                    <option value="name_desc" {{ request('sort')=='name_desc'?'selected':'' }}>Tên Z-A</option>
                </select>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    Tìm kiếm
                </button>

                @if(request('search') || request('filter') || request('sort'))
                <a href="{{ route('admin.students.index') }}"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-lg transition-colors">
                    Đặt lại
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- RESULT COUNT --}}
    <div class="flex items-center justify-between text-sm text-slate-500">
        <span>Tìm thấy <strong class="text-slate-800">{{ $students->total() }}</strong> sinh viên</span>
        <span>Trang {{ $students->currentPage() }}/{{ $students->lastPage() }}</span>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Sinh viên</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Mã SV</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Quốc tịch</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Hộ chiếu</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Visa</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Ngày tạo</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $index => $student)
                    <tr class="hover:bg-slate-50 transition-colors">
                        {{-- STT --}}
                        <td class="px-4 py-3 text-slate-400 text-xs">
                            {{ ($students->currentPage()-1) * $students->perPage() + $index + 1 }}
                        </td>

                        {{-- SINH VIÊN --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-800 truncate">{{ $student->full_name }}</div>
                                    <div class="text-xs text-slate-400 truncate">{{ $student->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- MÃ SV --}}
                        <td class="px-4 py-3 hidden md:table-cell">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-md">{{ $student->student_code }}</span>
                                @if($student->student_type)
                                    @php
                                        $typeMap = ['exchange'=>['Trao đổi','bg-blue-100 text-blue-700'], 'regular'=>['Chính quy','bg-green-100 text-green-700'], 'postgraduate'=>['Sau ĐH','bg-purple-100 text-purple-700']];
                                        [$typeLabel, $typeClass] = $typeMap[$student->student_type] ?? ['?','bg-gray-100 text-gray-600'];
                                    @endphp
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $typeClass }}">{{ $typeLabel }}</span>
                                @endif
                            </div>
                        </td>

                        {{-- QUỐC TỊCH --}}
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="text-sm text-slate-600">{{ $student->nationality ?? '—' }}</span>
                        </td>

                        {{-- HỘ CHIẾU --}}
                        <td class="px-4 py-3 text-center">
                            @php
                                $pColor = $student->getPassportStatusColor();
                                $pClass = match($pColor) {
                                    'green'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'yellow' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'red'    => 'bg-red-100 text-red-700 border-red-200',
                                    default  => 'bg-slate-100 text-slate-500 border-slate-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $pClass }}">
                                {{ $student->getPassportStatusText() }}
                            </span>
                            @if($student->passport && $student->passport->expiry_date)
                                <div class="text-[10px] text-slate-400 mt-1">
                                    {{ \Carbon\Carbon::parse($student->passport->expiry_date)->format('d/m/Y') }}
                                </div>
                            @endif
                        </td>

                        {{-- VISA --}}
                        <td class="px-4 py-3 text-center">
                            @php
                                $vColor = $student->getVisaStatusColor();
                                $vClass = match($vColor) {
                                    'green'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'yellow' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'red'    => 'bg-red-100 text-red-700 border-red-200',
                                    default  => 'bg-slate-100 text-slate-500 border-slate-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $vClass }}">
                                {{ $student->getVisaStatusText() }}
                            </span>
                            @if($student->visa && $student->visa->expiry_date)
                                <div class="text-[10px] text-slate-400 mt-1">
                                    {{ \Carbon\Carbon::parse($student->visa->expiry_date)->format('d/m/Y') }}
                                </div>
                            @endif
                        </td>

                        {{-- NGÀY TẠO --}}
                        <td class="px-4 py-3 text-center hidden sm:table-cell">
                            <div class="text-xs text-slate-600">{{ $student->created_at->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-slate-400">{{ $student->created_at->format('H:i') }}</div>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.students.show', $student) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors" title="Xem">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.students.edit', $student) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition-colors" title="Sửa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                                    onsubmit="return confirm('Xóa sinh viên {{ addslashes($student->full_name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors" title="Xóa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
                                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-600">Không tìm thấy sinh viên</p>
                                    <p class="text-sm text-slate-400 mt-0.5">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                                </div>
                                <a href="{{ route('admin.students.index') }}"
                                    class="text-sm text-blue-600 hover:underline">Xem tất cả</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if($students->hasPages())
    <div class="flex justify-center">
        {{ $students->links() }}
    </div>
    @endif

</div>
</x-app-layout>
