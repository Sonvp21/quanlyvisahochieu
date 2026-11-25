<x-app-layout>
    <div class="mx-auto max-w-6xl space-y-5">
        <x-alert-success />
        @if (session('error'))<div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>@endif
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Trao đổi sinh viên</h2>
                <p class="mt-1 text-sm text-slate-500">Chương trình, học bổng, đăng ký và theo dõi sinh viên Việt Nam đi trao đổi.</p>
            </div>
            <a href="{{ route('admin.exchange-programs.create') }}" class="rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white hover:bg-blue-800">+ Tạo chương trình</a>
        </div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead><tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-5 py-3">Chương trình</th><th class="px-5 py-3">Đối tác</th><th class="px-5 py-3">Hạn nộp</th><th class="px-5 py-3">Thời gian</th><th class="px-5 py-3">Hồ sơ</th><th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($programs as $p)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3"><a href="{{ route('admin.exchange-programs.show', $p) }}" class="font-medium text-slate-900 hover:text-blue-700">{{ $p->name }}</a>
                                <span class="block text-xs text-slate-400">{{ $p->type_label }} · {{ $p->level_label }}</span></td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->partner?->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-slate-600">{{ $p->apply_deadline?->format('d/m/Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-slate-600">{{ $p->start_date?->format('m/Y') ?? '—' }} → {{ $p->end_date?->format('m/Y') ?? '—' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->applications_count }}{{ $p->slots ? ' / ' . $p->slots . ' chỉ tiêu' : '' }}</td>
                            <td class="px-5 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $p->status === 'open' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $p->status === 'open' ? 'Đang mở' : 'Đã đóng' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Chưa có chương trình nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $programs->links() }}
    </div>
</x-app-layout>
