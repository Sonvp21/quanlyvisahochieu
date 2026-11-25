<x-app-layout>
    <div class="mx-auto max-w-6xl space-y-5">
        <x-alert-success />
        @if (session('error'))<div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>@endif
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Đối tác &amp; thỏa thuận hợp tác</h2>
                <p class="mt-1 text-sm text-slate-500">Trường, viện, doanh nghiệp, tổ chức đã và đang hợp tác với Trường.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.agreements.index') }}" class="rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Tất cả MOU</a>
                <a href="{{ route('admin.partners.create') }}" class="rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white hover:bg-blue-800">+ Thêm đối tác</a>
            </div>
        </div>
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm tên, quốc gia…" class="w-64 rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
            <select name="type" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                <option value="">Mọi loại</option>
                @foreach (\App\Models\Partner::TYPES as $k => $v)<option value="{{ $k }}" @selected(request('type') === $k)>{{ $v }}</option>@endforeach
            </select>
            <button class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Lọc</button>
        </form>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead><tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-5 py-3">Đối tác</th><th class="px-5 py-3">Loại</th><th class="px-5 py-3">Quốc gia</th><th class="px-5 py-3">Thỏa thuận</th><th class="px-5 py-3">Đoàn</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($partners as $p)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3"><a href="{{ route('admin.partners.show', $p) }}" class="font-medium text-slate-900 hover:text-blue-700">{{ $p->name }}</a>
                                @unless($p->is_active)<span class="ml-2 rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-500">Ngừng</span>@endunless</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->type_label }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->country ?: '—' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->agreements_count }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->trips_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Chưa có đối tác nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $partners->links() }}
    </div>
</x-app-layout>
