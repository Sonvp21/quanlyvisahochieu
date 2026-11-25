<x-app-layout>
    @php $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; $edit = $program->exists; $d = fn ($f) => old($f, $program->$f instanceof \Carbon\CarbonInterface ? $program->$f->format('Y-m-d') : $program->$f); @endphp
    <div class="mx-auto max-w-3xl space-y-5">
        <a href="{{ $edit ? route('admin.exchange-programs.show', $program) : route('admin.exchange-programs.index') }}" class="text-sm text-slate-500 hover:text-blue-700">← Quay lại</a>
        <form method="POST" action="{{ $edit ? route('admin.exchange-programs.update', $program) : route('admin.exchange-programs.store') }}" class="grid grid-cols-1 gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:grid-cols-2">
            @csrf @if ($edit) @method('PUT') @endif
            <h2 class="text-lg font-semibold text-slate-900 md:col-span-2">{{ $edit ? 'Sửa chương trình' : 'Tạo chương trình trao đổi' }}</h2>
            <div class="md:col-span-2"><label class="text-sm font-medium text-slate-600">Tên chương trình <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ $d('name') }}" class="{{ $in }}" required>@error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="text-sm font-medium text-slate-600">Đối tác</label>
                <select name="partner_id" class="{{ $in }}"><option value="">—</option>@foreach ($partners as $p)<option value="{{ $p->id }}" @selected($d('partner_id') == $p->id)>{{ $p->name }}</option>@endforeach</select></div>
            <div><label class="text-sm font-medium text-slate-600">Thỏa thuận làm căn cứ</label>
                <select name="agreement_id" class="{{ $in }}"><option value="">—</option>@foreach ($agreements as $a)<option value="{{ $a->id }}" @selected($d('agreement_id') == $a->id)>{{ $a->partner?->name }}: {{ $a->title }}</option>@endforeach</select></div>
            <div><label class="text-sm font-medium text-slate-600">Hình thức</label>
                <select name="program_type" class="{{ $in }}">@foreach (\App\Models\ExchangeProgram::TYPES as $k => $v)<option value="{{ $k }}" @selected($d('program_type') === $k)>{{ $v }}</option>@endforeach</select></div>
            <div><label class="text-sm font-medium text-slate-600">Bậc đào tạo</label>
                <select name="level" class="{{ $in }}"><option value="">Mọi bậc</option>@foreach (\App\Models\Person::LEVELS as $k => $v)<option value="{{ $k }}" @selected($d('level') === $k)>{{ $v }}</option>@endforeach</select></div>
            <div><label class="text-sm font-medium text-slate-600">Quốc gia</label><input type="text" name="country" value="{{ $d('country') }}" class="{{ $in }}"></div>
            <div><label class="text-sm font-medium text-slate-600">Chỉ tiêu</label><input type="number" min="1" name="slots" value="{{ $d('slots') }}" class="{{ $in }}"></div>
            <div><label class="text-sm font-medium text-slate-600">Hạn nộp hồ sơ</label><input type="date" name="apply_deadline" value="{{ $d('apply_deadline') }}" class="{{ $in }}"></div>
            <div><label class="text-sm font-medium text-slate-600">Trạng thái</label>
                <select name="status" class="{{ $in }}"><option value="open" @selected($d('status') === 'open')>Đang mở</option><option value="closed" @selected($d('status') === 'closed')>Đã đóng</option></select></div>
            <div><label class="text-sm font-medium text-slate-600">Bắt đầu</label><input type="date" name="start_date" value="{{ $d('start_date') }}" class="{{ $in }}"></div>
            <div><label class="text-sm font-medium text-slate-600">Kết thúc</label><input type="date" name="end_date" value="{{ $d('end_date') }}" class="{{ $in }}">@error('end_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
            <div class="md:col-span-2"><label class="text-sm font-medium text-slate-600">Học bổng, hỗ trợ</label><textarea name="benefits" rows="2" class="{{ $in }}">{{ $d('benefits') }}</textarea></div>
            <div class="md:col-span-2"><label class="text-sm font-medium text-slate-600">Điều kiện (GPA, ngoại ngữ…)</label><textarea name="requirements" rows="2" class="{{ $in }}">{{ $d('requirements') }}</textarea></div>
            <div class="flex justify-end md:col-span-2"><button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Lưu</button></div>
        </form>
    </div>
</x-app-layout>
