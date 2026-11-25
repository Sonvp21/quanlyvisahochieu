<x-app-layout>
    <div class="space-y-6">
        <x-alert-success />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Tài khoản cán bộ &amp; lãnh đạo</h2>
                <p class="mt-0.5 text-sm text-slate-500">Tài khoản của Ban Giám hiệu, phòng ban, lãnh đạo đơn vị, giảng viên, cán bộ. Sinh viên quốc tế quản lý ở mục Sinh viên.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-800">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Thêm tài khoản
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <form method="GET" class="flex flex-wrap items-center gap-2 border-b border-slate-100 p-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên, email..."
                       class="min-w-[220px] max-w-md flex-1 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-600/20">
                <select name="role" onchange="this.form.submit()" class="rounded-lg border border-slate-300 bg-slate-50 py-2 pl-3 pr-8 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                    <option value="">Mọi vai trò</option>
                    @foreach ($roles as $k => $v)<option value="{{ $k }}" @selected(request('role') === $k)>{{ $v }}</option>@endforeach
                </select>
                <button class="rounded-lg bg-slate-800 px-3.5 py-2 text-sm font-medium text-white hover:bg-slate-900">Lọc</button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-5 py-3">Họ và tên</th><th class="px-5 py-3">Email</th><th class="px-5 py-3">Vai trò</th><th class="px-5 py-3">Đơn vị</th><th class="px-5 py-3 text-right">Thao tác</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $u)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-3.5 font-medium text-slate-900">{{ $u->name }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $u->email }}</td>
                                <td class="px-5 py-3.5"><span class="inline-block whitespace-nowrap rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">{{ \App\Models\User::ROLES[$u->role] ?? $u->role }}</span></td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $u->unit?->name ?? '—' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.users.edit', $u) }}" title="Sửa" class="inline-block rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Xóa tài khoản này?')">
                                        @csrf @method('DELETE')
                                        <button title="Xóa" class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-14 text-center text-slate-400">Không có tài khoản phù hợp.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())<div class="border-t border-slate-100 px-5 py-3">{{ $users->links() }}</div>@endif
        </div>
    </div>
</x-app-layout>
