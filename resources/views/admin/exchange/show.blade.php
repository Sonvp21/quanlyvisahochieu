<x-app-layout>
    @php $in = 'rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp
    <div class="mx-auto max-w-5xl space-y-6">
        <x-alert-success />
        @if (session('error'))<div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>@endif
        <a href="{{ route('admin.exchange-programs.index') }}" class="text-sm text-slate-500 hover:text-blue-700">← Trao đổi sinh viên</a>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">{{ $program->name }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $program->type_label }} · {{ $program->level_label }} · {{ $program->partner?->name ?? 'Chưa gắn đối tác' }}{{ $program->country ? ' · ' . $program->country : '' }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.exchange-programs.edit', $program) }}" class="rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Sửa</a>
                    <form method="POST" action="{{ route('admin.exchange-programs.destroy', $program) }}" onsubmit="return confirm('Xóa chương trình?')">@csrf @method('DELETE')
                        <button class="rounded-lg border border-red-200 bg-white px-3.5 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Xóa</button></form>
                </div>
            </div>
            <dl class="mt-5 grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
                <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Hạn nộp</dt><dd class="mt-1 text-slate-800">{{ $program->apply_deadline?->format('d/m/Y') ?? '—' }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Thời gian</dt><dd class="mt-1 text-slate-800">{{ $program->start_date?->format('d/m/Y') ?? '—' }} → {{ $program->end_date?->format('d/m/Y') ?? '—' }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Chỉ tiêu</dt><dd class="mt-1 text-slate-800">{{ $program->slots ?? '—' }}</dd></div>
                <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Căn cứ</dt><dd class="mt-1 text-slate-800">{{ $program->agreement?->title ?? '—' }}</dd></div>
                @if ($program->benefits)<div class="col-span-2 md:col-span-4"><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Học bổng, hỗ trợ</dt><dd class="mt-1 whitespace-pre-line text-slate-700">{{ $program->benefits }}</dd></div>@endif
                @if ($program->requirements)<div class="col-span-2 md:col-span-4"><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Điều kiện</dt><dd class="mt-1 whitespace-pre-line text-slate-700">{{ $program->requirements }}</dd></div>@endif
            </dl>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Hồ sơ đăng ký ({{ $program->applications->count() }}{{ $program->slots ? ' / ' . $program->slots . ' chỉ tiêu' : '' }})</h3>

            <form method="POST" action="{{ route('admin.exchange-programs.applications.store', $program) }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-xl border border-blue-100 bg-blue-50/40 p-4">
                @csrf
                <div class="min-w-[14rem] flex-1"><label class="text-xs font-medium text-slate-600">Sinh viên</label>
                    <select name="person_id" required class="{{ $in }} mt-1 w-full">
                        <option value="">Chọn sinh viên…</option>
                        @foreach ($candidates as $c)<option value="{{ $c->id }}">{{ $c->full_name }}{{ $c->student_code ? ' (' . $c->student_code . ')' : '' }}</option>@endforeach
                    </select></div>
                <div><label class="text-xs font-medium text-slate-600">GPA</label><input type="number" step="0.01" min="0" max="10" name="gpa" class="{{ $in }} mt-1 w-24"></div>
                <div class="min-w-[10rem] flex-1"><label class="text-xs font-medium text-slate-600">Chứng chỉ ngoại ngữ</label><input type="text" name="language_cert" placeholder="IELTS 6.0…" class="{{ $in }} mt-1 w-full"></div>
                <button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Thêm</button>
                @if ($candidates->isEmpty())<p class="w-full text-xs text-slate-500">Chưa có sinh viên nào để chọn. Thêm ở “Hồ sơ nhân sự &amp; khách” (loại Sinh viên Việt Nam).</p>@endif
            </form>

            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($program->applications as $a)
                    <div class="flex flex-wrap items-center justify-between gap-3 py-3">
                        <div class="min-w-0">
                            <p class="font-medium text-slate-900">{{ $a->person->full_name }} <span class="ml-1 rounded-full px-2 py-0.5 text-xs font-medium {{ $a->status_class }}">{{ $a->status_label }}</span></p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ collect([$a->person->student_code, $a->person->unit?->name, $a->gpa ? 'GPA ' . $a->gpa : null, $a->language_cert])->filter()->implode(' · ') }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <form method="POST" action="{{ route('admin.exchange-applications.update', $a) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <select name="status" class="{{ $in }} py-1.5">@foreach (\App\Models\ExchangeApplication::STATUSES as $k => $v)<option value="{{ $k }}" @selected($a->status === $k)>{{ $v[0] }}</option>@endforeach</select>
                                <button class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cập nhật</button>
                            </form>
                            @if ($a->trip)
                                <a href="{{ route('trips.show', $a->trip) }}" class="text-sm font-medium text-blue-700 hover:text-blue-800">Hồ sơ đi {{ $a->trip->code }}</a>
                            @elseif (in_array($a->status, ['approved', 'ongoing'], true))
                                <form method="POST" action="{{ route('admin.exchange-applications.trip', $a) }}">@csrf<button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-emerald-700">Tạo hồ sơ đi</button></form>
                            @endif
                            @unless ($a->trip)
                                <form method="POST" action="{{ route('admin.exchange-applications.destroy', $a) }}" onsubmit="return confirm('Xóa hồ sơ đăng ký?')">@csrf @method('DELETE')<button class="text-sm text-red-600 hover:text-red-700">Xóa</button></form>
                            @endunless
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-sm text-slate-400">Chưa có hồ sơ đăng ký.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
