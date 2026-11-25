<x-app-layout>
    <div class="mx-auto max-w-6xl space-y-5">
        <a href="{{ route('admin.mail-rules.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-blue-700">← Quy tắc email</a>
        <h2 class="text-xl font-semibold text-slate-900">Nhật ký gửi email</h2>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead><tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-5 py-3">Thời gian</th><th class="px-5 py-3">Tiêu đề</th><th class="px-5 py-3">Người nhận</th><th class="px-5 py-3">Kết quả</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $l)
                        <tr class="align-top">
                            <td class="whitespace-nowrap px-5 py-3 text-slate-500">{{ $l->created_at->format('H:i d/m/Y') }}</td>
                            <td class="px-5 py-3">
                                @if ($l->trip)<a href="{{ route('trips.show', $l->trip) }}" class="font-medium text-slate-900 hover:text-blue-700">{{ $l->subject }}</a>@else{{ $l->subject }}@endif
                            </td>
                            <td class="max-w-xs break-words px-5 py-3 text-slate-600">{{ $l->recipients }}</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $l->status === 'sent' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">{{ $l->status === 'sent' ? 'Đã gửi' : 'Lỗi' }}</span>
                                @if ($l->error)<p class="mt-1 max-w-xs text-xs text-red-600">{{ $l->error }}</p>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-slate-400">Chưa có email nào được gửi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</x-app-layout>
