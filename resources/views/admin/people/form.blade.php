@php
    $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600';
    $err = fn ($f) => $errors->first($f);
@endphp
<div x-data="{ type: '{{ old('person_type', $person->person_type) }}' }" class="space-y-6">

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-slate-600">Đối tượng <span class="text-red-500">*</span></label>
            <select name="person_type" x-model="type" class="{{ $in }}">
                @foreach (\App\Models\Person::TYPES as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Họ và tên <span class="text-red-500">*</span></label>
            <input type="text" name="full_name" value="{{ old('full_name', $person->full_name) }}" required class="{{ $in }}">
            @if ($err('full_name'))<p class="mt-1 text-xs text-red-600">{{ $err('full_name') }}</p>@endif
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Giới tính</label>
            <select name="gender" class="{{ $in }}">
                <option value="">—</option>
                @foreach (['male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác'] as $k => $v)
                    <option value="{{ $k }}" @selected(old('gender', $person->gender) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Ngày sinh</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $person->date_of_birth?->format('Y-m-d')) }}" class="{{ $in }}">
            @if ($err('date_of_birth'))<p class="mt-1 text-xs text-red-600">{{ $err('date_of_birth') }}</p>@endif
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Quốc tịch</label>
            <input type="text" name="nationality" value="{{ old('nationality', $person->nationality) }}" class="{{ $in }}">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Học hàm, học vị</label>
            <input type="text" name="academic_title" value="{{ old('academic_title', $person->academic_title) }}" placeholder="GS.TS, PGS.TS, TS, ThS…" class="{{ $in }}">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 border-t border-slate-100 pt-6 md:grid-cols-2">
        {{-- Đơn vị: cán bộ, sinh viên --}}
        <div x-show="type !== 'guest'">
            <label class="text-sm font-medium text-slate-600">Đơn vị</label>
            <select name="unit_id" class="{{ $in }}">
                <option value="">— Chưa xác định —</option>
                @foreach ($units as $u)
                    <option value="{{ $u->id }}" @selected((string) old('unit_id', $person->unit_id) === (string) $u->id)>{{ str_repeat('— ', $u->depth) }}{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        {{-- Cơ quan: khách --}}
        <div x-show="type === 'guest'" x-cloak>
            <label class="text-sm font-medium text-slate-600">Cơ quan / doanh nghiệp</label>
            <input type="text" name="organization" value="{{ old('organization', $person->organization) }}" class="{{ $in }}">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600"><span x-text="type.startsWith('student') ? 'Ngành / lớp' : 'Chức vụ / nghề nghiệp'"></span></label>
            <input type="text" name="position" value="{{ old('position', $person->position) }}" class="{{ $in }}">
        </div>
        <div x-show="type.startsWith('student')" x-cloak>
            <label class="text-sm font-medium text-slate-600">Mã sinh viên</label>
            <input type="text" name="student_code" value="{{ old('student_code', $person->student_code) }}" class="{{ $in }}">
        </div>
        <div x-show="type.startsWith('student')" x-cloak>
            <label class="text-sm font-medium text-slate-600">Bậc đào tạo</label>
            <select name="student_level" class="{{ $in }}">
                <option value="">—</option>
                @foreach (\App\Models\Person::LEVELS as $k => $v)
                    <option value="{{ $k }}" @selected(old('student_level', $person->student_level) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 border-t border-slate-100 pt-6 md:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-slate-600">Email</label>
            <input type="email" name="email" value="{{ old('email', $person->email) }}" class="{{ $in }}">
            @if ($err('email'))<p class="mt-1 text-xs text-red-600">{{ $err('email') }}</p>@endif
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Điện thoại</label>
            <input type="text" name="phone" value="{{ old('phone', $person->phone) }}" class="{{ $in }}">
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-600">Tài khoản đăng nhập (nếu có)</label>
            <select name="user_id" class="{{ $in }}">
                <option value="">— Không liên kết —</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" @selected((string) old('user_id', $person->user_id) === (string) $u->id)>{{ $u->name }} — {{ $u->email }}</option>
                @endforeach
            </select>
            @if ($err('user_id'))<p class="mt-1 text-xs text-red-600">{{ $err('user_id') }}</p>@endif
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-600">Ghi chú</label>
            <textarea name="notes" rows="3" class="{{ $in }}">{{ old('notes', $person->notes) }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $person->is_active ?? true)) class="rounded border-slate-300 text-blue-700 focus:ring-blue-600">
                Đang hoạt động
            </label>
        </div>
    </div>
</div>
