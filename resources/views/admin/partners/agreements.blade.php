<x-app-layout>
    <div class="mx-auto max-w-6xl space-y-5">
        <x-alert-success />
        <a href="{{ route('admin.partners.index') }}" class="text-sm text-slate-500 hover:text-blue-700">← Đối tác</a>
        <div class="flex flex-wrap items-end justify-between gap-3">
            <h2 class="text-xl font-semibold text-slate-900">Thỏa thuận hợp tác (MOU, MOA, hợp đồng)</h2>
            <div class="flex gap-2 text-sm">
                @php $st = request('state'); @endphp
                @foreach ([null => 'Tất cả', 'active' => 'Còn hiệu lực', 'expiring' => 'Sắp hết hạn (' . $counts['expiring'] . ')', 'expired' => 'Đã hết hạn (' . $counts['expired'] . ')'] as $k => $lbl)
                    <a href="{{ route('admin.agreements.index', $k ? ['state' => $k] : []) }}" class="rounded-lg px-3 py-1.5 font-medium {{ $st === $k ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100' }}">{{ $lbl }}</a>
                @endforeach
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead><tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-5 py-3">Đối tác</th><th class="px-5 py-3">Thỏa thuận</th><th class="px-5 py-3">Đơn vị phụ trách</th><th class="px-5 py-3">Hết hạn</th><th class="px-5 py-3">Tình trạng</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($agreements as $a)
                        <tr>
                            <td class="px-5 py-3"><a href="{{ route('admin.partners.show', $a->partner) }}" class="font-medium text-slate-900 hover:text-blue-700">{{ $a->partner->name }}</a></td>
                            <td class="px-5 py-3 text-slate-700">{{ $a->title }}<span class="block text-xs text-slate-400">{{ \App\Models\Agreement::TYPES[$a->agreement_type] ?? '' }}{{ $a->reference_no ? ' · ' . $a->reference_no : '' }}</span></td>
                            <td class="px-5 py-3 text-slate-600">{{ $a->unit?->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-slate-600">{{ $a->expires_at?->format('d/m/Y') ?? 'Không thời hạn' }}</td>
                            <td class="px-5 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $a->state_label[1] }}">{{ $a->state_label[0] }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Không có thỏa thuận nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $agreements->links() }}
    </div>
</x-app-layout>
