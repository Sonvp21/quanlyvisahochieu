<x-app-layout>
    <div class="space-y-6 m-3">
        <x-alert-success />

        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $delegation->document_no ?: 'Đoàn khách nước ngoài' }}</h1>
                <p class="text-sm text-slate-500">{{ $delegation->host_unit }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.delegations.export-word', $delegation) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
                    Xuất công văn Word
                </a>
                <a href="{{ route('admin.delegations.export', $delegation) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm">
                    Xuất Excel phụ lục
                </a>
                <a href="{{ route('admin.delegations.edit', $delegation) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium bg-amber-500 text-white hover:bg-amber-600 shadow-sm">
                    Sửa
                </a>
                <a href="{{ route('admin.delegations.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100">
                    Quay lại
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Thông tin công văn</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500">Đơn vị tiếp đón</dt><dd class="font-medium text-slate-800">{{ $delegation->host_unit ?: '—' }}</dd></div>
                <div><dt class="text-slate-500">Nơi gửi công văn</dt><dd class="font-medium text-slate-800">{{ $delegation->recipient_units ?: '—' }}</dd></div>
                <div class="md:col-span-2"><dt class="text-slate-500">Nội dung làm việc</dt><dd class="font-medium text-slate-800">{{ $delegation->purpose ?: '—' }}</dd></div>
                <div><dt class="text-slate-500">Thời gian làm việc</dt>
                    <dd class="font-medium text-slate-800">
                        @if($delegation->work_date_from)
                            {{ $delegation->work_date_from->format('d/m/Y') }}
                            @if($delegation->work_date_to && !$delegation->work_date_to->eq($delegation->work_date_from))
                                - {{ $delegation->work_date_to->format('d/m/Y') }}
                            @endif
                        @else — @endif
                    </dd>
                </div>
                <div><dt class="text-slate-500">Địa điểm công tác</dt><dd class="font-medium text-slate-800">{{ $delegation->work_location ?: '—' }}</dd></div>
                <div><dt class="text-slate-500">Nơi tạm trú</dt><dd class="font-medium text-slate-800">{{ $delegation->accommodation_name ?: '—' }}</dd></div>
                <div><dt class="text-slate-500">Địa chỉ tạm trú</dt><dd class="font-medium text-slate-800">{{ $delegation->accommodation_address ?: '—' }}</dd></div>
                <div class="md:col-span-2"><dt class="text-slate-500">Ghi chú</dt><dd class="font-medium text-slate-800">{{ $delegation->notes ?: '—' }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-800">Danh sách thành viên ({{ $delegation->members->count() }})</h2>
            </div>
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Họ và tên</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Giới tính</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Năm sinh</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Quốc tịch</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Nghề nghiệp</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Hộ chiếu</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Hạn hộ chiếu</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Số thị thực</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($delegation->members as $i => $member)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-slate-500">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $member->full_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $member->gender ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $member->date_of_birth?->format('d/m/Y') ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $member->nationality ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $member->occupation ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ trim(($member->passport_type ?? '').', '.($member->passport_no ?? ''), ', ') ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $member->passport_expiry?->format('d/m/Y') ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $member->visa_no ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-4 py-10 text-center text-slate-400">Chưa có thành viên.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
