<x-app-layout>
    <div class="mx-auto max-w-5xl space-y-5">
        <x-alert-success />
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Quy tắc email tự động</h2>
                <p class="mt-1 text-sm text-slate-500">Khi hồ sơ đổi trạng thái, hệ thống gửi email cho những người nêu dưới đây, kèm biểu mẫu Word nếu có.</p>
            </div>
            <a href="{{ route('admin.mail-logs.index') }}" class="rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Nhật ký gửi</a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead><tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-5 py-3">Sự kiện</th><th class="px-5 py-3">Gửi đến</th><th class="px-5 py-3">Đính kèm</th><th class="px-5 py-3">Trạng thái</th><th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($rules as $r)
                        @php
                            $who = array_filter([
                                $r->to_next_approver ? 'Người duyệt kế tiếp' : null,
                                $r->to_creator ? 'Người lập hồ sơ' : null,
                                $r->to_members ? 'Thành viên đoàn' : null,
                                $r->to_unit_manager ? 'Lãnh đạo đơn vị' : null,
                                ...array_map(fn ($x) => \App\Models\User::ROLES[$x] ?? $x, (array) $r->to_roles),
                                $r->extra_emails ?: null,
                            ]);
                        @endphp
                        <tr class="align-top">
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-900">{{ $r->name }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ \App\Models\MailRule::EVENTS[$r->event] ?? $r->event }} · {{ $r->direction === 'in' ? 'Đoàn vào' : ($r->direction === 'out' ? 'Đoàn ra' : 'Cả hai') }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $who ? implode(', ', $who) : '—' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $r->attachments ? implode(', ', array_map(fn ($k) => \App\Models\MailRule::DOCS[$k] ?? $k, $r->attachments)) : '—' }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.mail-rules.toggle', $r) }}">
                                    @csrf @method('PATCH')
                                    <button class="rounded-full px-2.5 py-1 text-xs font-medium {{ $r->enabled ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-500' }}">{{ $r->enabled ? 'Đang bật' : 'Đã tắt' }}</button>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-right"><a href="{{ route('admin.mail-rules.edit', $r) }}" class="font-medium text-blue-700 hover:text-blue-800">Sửa</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
