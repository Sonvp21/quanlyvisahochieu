<x-app-layout>
    <div class="space-y-6">
        <x-alert-success />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Cơ cấu đơn vị</h2>
                <p class="mt-0.5 text-sm text-slate-500">Phòng ban, khoa, trung tâm, viện… dùng để phân quyền và phân loại hồ sơ.</p>
            </div>
            <a href="{{ route('admin.units.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-800">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Thêm đơn vị
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-5 py-3">Đơn vị</th>
                            <th class="px-5 py-3">Mã</th>
                            <th class="px-5 py-3">Loại</th>
                            <th class="px-5 py-3 text-center">Nhân sự</th>
                            <th class="px-5 py-3 text-center">Trạng thái</th>
                            <th class="px-5 py-3 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($units as $unit)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center" style="padding-left: {{ $unit->depth * 24 }}px">
                                        @if ($unit->depth > 0)
                                            <span class="mr-2 text-slate-300">└</span>
                                        @endif
                                        <span class="font-medium text-slate-900">{{ $unit->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5"><span class="rounded-md bg-slate-100 px-2 py-0.5 font-mono text-xs text-slate-600">{{ $unit->code }}</span></td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $unit->type_label }}</td>
                                <td class="px-5 py-3.5 text-center text-slate-600">{{ $counts[$unit->id] ?? 0 }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-block whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium {{ $unit->is_active ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $unit->is_active ? 'Đang hoạt động' : 'Ngừng' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.units.edit', $unit) }}" title="Sửa" class="inline-block rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="inline" onsubmit="return confirm('Xóa đơn vị này?')">
                                        @csrf @method('DELETE')
                                        <button title="Xóa" class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-14 text-center text-slate-400">Chưa có đơn vị nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
