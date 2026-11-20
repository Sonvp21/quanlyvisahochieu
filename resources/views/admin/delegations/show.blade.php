<x-app-layout>
    <div class="space-y-6">
        <x-alert-success />

        @php
            $statusMap = [
                'draft'     => ['Dự thảo', 'bg-slate-100 text-slate-600'],
                'sent'      => ['Đã gửi công văn', 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'],
                'completed' => ['Hoàn tất', 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'],
            ];
            [$statusLabel, $statusCls] = $statusMap[$delegation->status] ?? ['—', 'bg-slate-100 text-slate-600'];
        @endphp

        {{-- Đầu trang --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <a href="{{ route('admin.delegations.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Danh sách đoàn khách
                </a>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <h2 class="text-2xl font-bold text-slate-900">{{ $delegation->document_no ?: 'Đoàn khách nước ngoài' }}</h2>
                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $statusCls }}">{{ $statusLabel }}</span>
                </div>
                <p class="mt-1 text-sm text-slate-500">{{ $delegation->host_unit ?: 'Chưa có đơn vị tiếp đón' }}</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.delegations.export-word', $delegation) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-800 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Xuất công văn Word
                </a>
                <a href="{{ route('admin.delegations.export', $delegation) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                    Excel phụ lục
                </a>
                <a href="{{ route('admin.delegations.edit', $delegation) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Sửa
                </a>
            </div>
        </div>

        {{-- Thông tin công văn --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Thông tin công văn</h3>
            <dl class="mt-5 grid grid-cols-1 gap-x-8 gap-y-5 text-sm md:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Nơi gửi công văn</dt>
                    <dd class="mt-1 font-medium text-slate-800">{{ $delegation->recipient_units ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Thời gian làm việc</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        @if($delegation->work_date_from)
                            {{ $delegation->work_date_from->format('d/m/Y') }}
                            @if($delegation->work_date_to && !$delegation->work_date_to->eq($delegation->work_date_from))
                                <span class="text-slate-400">→</span> {{ $delegation->work_date_to->format('d/m/Y') }}
                            @endif
                        @else — @endif
                    </dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Nội dung làm việc</dt>
                    <dd class="mt-1 font-medium text-slate-800">{{ $delegation->purpose ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Địa điểm công tác</dt>
                    <dd class="mt-1 font-medium text-slate-800">{{ $delegation->work_location ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Nơi tạm trú</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $delegation->accommodation_name ?: '—' }}
                        @if($delegation->accommodation_address)
                            <span class="block text-xs font-normal text-slate-500">{{ $delegation->accommodation_address }}</span>
                        @endif
                    </dd>
                </div>
                @if($delegation->notes)
                    <div class="md:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Ghi chú</dt>
                        <dd class="mt-1 text-slate-700">{{ $delegation->notes }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        {{-- Thành viên --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-semibold text-slate-900">Thành viên đoàn</h3>
                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $delegation->members->count() }} người</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-5 py-3">#</th>
                            <th class="px-5 py-3">Họ và tên</th>
                            <th class="px-5 py-3">Giới tính</th>
                            <th class="px-5 py-3">Năm sinh</th>
                            <th class="px-5 py-3">Quốc tịch</th>
                            <th class="px-5 py-3">Nghề nghiệp</th>
                            <th class="px-5 py-3">Hộ chiếu</th>
                            <th class="px-5 py-3">Hạn hộ chiếu</th>
                            <th class="px-5 py-3">Số thị thực</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($delegation->members as $i => $member)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-3.5 text-slate-400">{{ $i + 1 }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-700">
                                            {{ strtoupper(mb_substr($member->full_name, 0, 1)) }}
                                        </span>
                                        <span class="font-medium text-slate-900">{{ $member->full_name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $member->gender ?: '—' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-slate-600">{{ $member->date_of_birth?->format('d/m/Y') ?: '—' }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $member->nationality ?: '—' }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $member->occupation ?: '—' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-slate-600">{{ trim(($member->passport_type ?? '').', '.($member->passport_no ?? ''), ', ') ?: '—' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-slate-600">{{ $member->passport_expiry?->format('d/m/Y') ?: '—' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-slate-600">{{ $member->visa_no ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-5 py-12 text-center text-slate-400">Chưa có thành viên.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
