<x-app-layout>
    <div class="space-y-6">
        <x-alert-success />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Hồ sơ nhân sự &amp; khách</h2>
                <p class="mt-0.5 text-sm text-slate-500">Cán bộ, giảng viên, sinh viên và khách — dùng chung cho đoàn ra, đoàn vào, hộ chiếu, visa.</p>
            </div>
            <a href="{{ route('admin.people.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-800">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Thêm hồ sơ
            </a>
        </div>

        {{-- Thống kê theo loại (bấm để lọc) --}}
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach (\App\Models\Person::TYPES as $key => $label)
                @php $on = request('type') === $key; @endphp
                <a href="{{ route('admin.people.index', $on ? [] : ['type' => $key]) }}"
                   class="rounded-2xl border bg-white p-4 shadow-sm transition hover:border-blue-300 {{ $on ? 'border-blue-600 ring-1 ring-blue-600' : 'border-slate-200' }}">
                    <p class="text-2xl font-bold text-slate-900">{{ $typeCounts[$key] ?? 0 }}</p>
                    <p class="text-xs text-slate-500">{{ $label }}</p>
                </a>
            @endforeach
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <form method="GET" class="flex flex-wrap items-center gap-2 border-b border-slate-100 p-4">
                <input type="hidden" name="type" value="{{ request('type') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên, email, mã SV, cơ quan..."
                       class="min-w-[220px] max-w-md flex-1 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-600/20">
                <select name="unit_id" onchange="this.form.submit()" class="rounded-lg border border-slate-300 bg-slate-50 py-2 pl-3 pr-8 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                    <option value="">Mọi đơn vị</option>
                    @foreach ($units as $u)
                        <option value="{{ $u->id }}" @selected((string) request('unit_id') === (string) $u->id)>{{ str_repeat('— ', $u->depth) }}{{ $u->name }}</option>
                    @endforeach
                </select>
                <button class="rounded-lg bg-slate-800 px-3.5 py-2 text-sm font-medium text-white hover:bg-slate-900">Lọc</button>
                @if (request('search') || request('type') || request('unit_id'))
                    <a href="{{ route('admin.people.index') }}" class="px-1 text-sm text-slate-500 hover:text-slate-700">Xóa lọc</a>
                @endif
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-5 py-3">Họ và tên</th>
                            <th class="px-5 py-3">Loại</th>
                            <th class="px-5 py-3">Đơn vị / cơ quan</th>
                            <th class="px-5 py-3">Chức vụ</th>
                            <th class="px-5 py-3">Liên hệ</th>
                            <th class="px-5 py-3 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($people as $p)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-700">{{ strtoupper(mb_substr($p->full_name, 0, 1)) }}</span>
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.people.edit', $p) }}" class="font-medium text-slate-900 hover:text-blue-700">{{ trim(($p->academic_title ? $p->academic_title . '. ' : '') . $p->full_name) }}</a>
                                            @if ($p->student_code)<p class="text-xs text-slate-400">{{ $p->student_code }}</p>@endif
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3.5">
                                    <span class="inline-block rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">{{ $p->type_label }}</span>
                                    @if ($p->student_level)<span class="ml-1 text-xs text-slate-400">{{ \App\Models\Person::LEVELS[$p->student_level] ?? '' }}</span>@endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $p->unit?->name ?? ($p->organization ?: '—') }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $p->position ?: '—' }}</td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">{{ $p->email ?: '' }}<br>{{ $p->phone ?: '' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.people.edit', $p) }}" title="Sửa" class="inline-block rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    @unless ($p->student_id)
                                        <form action="{{ route('admin.people.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Xóa hồ sơ này?')">
                                            @csrf @method('DELETE')
                                            <button title="Xóa" class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-14 text-center text-slate-400">Không có hồ sơ phù hợp.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($people->hasPages())
                <div class="border-t border-slate-100 px-5 py-3">{{ $people->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
