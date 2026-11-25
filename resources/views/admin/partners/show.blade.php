<x-app-layout>
    @php $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp
    <div class="mx-auto max-w-5xl space-y-6" x-data="{ addMou: {{ $errors->any() ? 'true' : 'false' }} }">
        <x-alert-success />
        @if (session('error'))<div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>@endif
        <a href="{{ route('admin.partners.index') }}" class="text-sm text-slate-500 hover:text-blue-700">← Đối tác</a>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">{{ $partner->name }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $partner->type_label }}{{ $partner->country ? ' · ' . trim(($partner->city ? $partner->city . ', ' : '') . $partner->country) : '' }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.partners.edit', $partner) }}" class="rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Sửa</a>
                    <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" onsubmit="return confirm('Xóa đối tác này?')">@csrf @method('DELETE')
                        <button class="rounded-lg border border-red-200 bg-white px-3.5 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Xóa</button></form>
                </div>
            </div>
            <dl class="mt-5 grid grid-cols-1 gap-4 text-sm md:grid-cols-3">
                <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Liên hệ</dt><dd class="mt-1 text-slate-800">{{ $partner->contact_name ?: '—' }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Email, điện thoại</dt><dd class="mt-1 text-slate-800">{{ collect([$partner->contact_email, $partner->contact_phone])->filter()->implode(' · ') ?: '—' }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Website</dt><dd class="mt-1 break-all text-slate-800">{{ $partner->website ?: '—' }}</dd></div>
                @if ($partner->notes)<div class="md:col-span-3"><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Ghi chú</dt><dd class="mt-1 whitespace-pre-line text-slate-700">{{ $partner->notes }}</dd></div>@endif
            </dl>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-900">Thỏa thuận hợp tác</h3>
                <button type="button" @click="addMou = !addMou" class="text-sm font-medium text-blue-700 hover:text-blue-800">+ Thêm thỏa thuận</button>
            </div>

            <form x-show="addMou" x-cloak method="POST" action="{{ route('admin.partners.agreements.store', $partner) }}" enctype="multipart/form-data" class="mt-4 grid grid-cols-1 gap-3 rounded-xl border border-blue-100 bg-blue-50/40 p-4 md:grid-cols-3">
                @csrf
                <div class="md:col-span-2"><label class="text-xs font-medium text-slate-600">Tên thỏa thuận *</label><input type="text" name="title" value="{{ old('title') }}" class="{{ $in }}" required>@error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div><label class="text-xs font-medium text-slate-600">Loại</label>
                    <select name="agreement_type" class="{{ $in }}">@foreach (\App\Models\Agreement::TYPES as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
                <div><label class="text-xs font-medium text-slate-600">Số hiệu</label><input type="text" name="reference_no" value="{{ old('reference_no') }}" class="{{ $in }}"></div>
                <div><label class="text-xs font-medium text-slate-600">Ngày ký</label><input type="date" name="signed_at" value="{{ old('signed_at') }}" class="{{ $in }}"></div>
                <div><label class="text-xs font-medium text-slate-600">Ngày hết hạn (để trống nếu không thời hạn)</label><input type="date" name="expires_at" value="{{ old('expires_at') }}" class="{{ $in }}">@error('expires_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div><label class="text-xs font-medium text-slate-600">Đơn vị phụ trách</label>
                    <select name="unit_id" class="{{ $in }}"><option value="">—</option>@foreach ($units as $u)<option value="{{ $u->id }}">{{ str_repeat('— ', $u->depth) }}{{ $u->name }}</option>@endforeach</select></div>
                <div class="md:col-span-2"><label class="text-xs font-medium text-slate-600">Nội dung, phạm vi hợp tác</label><input type="text" name="scope" value="{{ old('scope') }}" class="{{ $in }}"></div>
                <div><label class="text-xs font-medium text-slate-600">Tệp bản ký (PDF, Word, ảnh)</label><input type="file" name="file" class="mt-1 block w-full text-sm text-slate-600">@error('file')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div class="md:col-span-3"><button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Lưu thỏa thuận</button></div>
            </form>

            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($partner->agreements as $a)
                    <div class="flex flex-wrap items-start justify-between gap-3 py-3">
                        <div class="min-w-0">
                            <p class="font-medium text-slate-900">{{ $a->title }} <span class="ml-1 rounded-full px-2 py-0.5 text-xs font-medium {{ $a->state_label[1] }}">{{ $a->state_label[0] }}</span></p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ \App\Models\Agreement::TYPES[$a->agreement_type] ?? '' }}{{ $a->reference_no ? ' · Số ' . $a->reference_no : '' }}
                                · Ký: {{ $a->signed_at?->format('d/m/Y') ?? '—' }} · Hết hạn: {{ $a->expires_at?->format('d/m/Y') ?? 'không thời hạn' }}{{ $a->unit ? ' · ' . $a->unit->name : '' }}</p>
                            @if ($a->scope)<p class="mt-1 text-sm text-slate-600">{{ $a->scope }}</p>@endif
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            @if ($a->file_path)<a href="{{ route('admin.agreements.download', $a) }}" class="font-medium text-blue-700 hover:text-blue-800">Tải tệp</a>@endif
                            <form method="POST" action="{{ route('admin.agreements.terminate', $a) }}">@csrf @method('PATCH')<button class="text-slate-500 hover:text-slate-800">{{ $a->is_terminated ? 'Khôi phục' : 'Chấm dứt' }}</button></form>
                            <form method="POST" action="{{ route('admin.agreements.destroy', $a) }}" onsubmit="return confirm('Xóa thỏa thuận này?')">@csrf @method('DELETE')<button class="text-red-600 hover:text-red-700">Xóa</button></form>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-sm text-slate-400">Chưa có thỏa thuận nào.</p>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900">Chương trình trao đổi sinh viên</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    @forelse ($partner->programs as $pr)
                        <li><a href="{{ route('admin.exchange-programs.show', $pr) }}" class="font-medium text-slate-800 hover:text-blue-700">{{ $pr->name }}</a> <span class="text-xs text-slate-400">· {{ $pr->type_label }}</span></li>
                    @empty<li class="text-slate-400">Chưa có.</li>@endforelse
                </ul>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900">Đoàn ra, đoàn vào gần đây</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    @forelse ($partner->trips as $t)
                        <li><a href="{{ route('trips.show', $t) }}" class="font-medium text-slate-800 hover:text-blue-700">{{ $t->code }} · {{ $t->title }}</a> <span class="text-xs text-slate-400">· {{ $t->direction_label }}, {{ $t->depart_at?->format('m/Y') }}</span></li>
                    @empty<li class="text-slate-400">Chưa có. Chọn đối tác trong form hồ sơ đoàn để liên kết.</li>@endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
