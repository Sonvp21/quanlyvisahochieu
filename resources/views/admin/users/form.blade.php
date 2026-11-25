@php $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp
<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div>
        <label class="text-sm font-medium text-slate-600">Họ và tên <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="{{ $in }}">
        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600">Email đăng nhập <span class="text-red-500">*</span></label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="{{ $in }}">
        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600">Mật khẩu @if(!$user->exists)<span class="text-red-500">*</span>@endif</label>
        <input type="password" name="password" @if(!$user->exists) required @endif autocomplete="new-password" class="{{ $in }}" placeholder="{{ $user->exists ? 'Để trống nếu không đổi' : 'Tối thiểu 6 ký tự' }}">
        @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600">Vai trò <span class="text-red-500">*</span></label>
        <select name="role" class="{{ $in }}">
            @foreach ($roles as $k => $v)
                <option value="{{ $k }}" @selected(old('role', $user->role) === $k)>{{ $v }}</option>
            @endforeach
        </select>
        @error('role')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600">Đơn vị</label>
        <select name="unit_id" class="{{ $in }}">
            <option value="">— Không thuộc đơn vị nào —</option>
            @foreach ($units as $u)
                <option value="{{ $u->id }}" @selected((string) old('unit_id', $user->unit_id) === (string) $u->id)>{{ str_repeat('— ', $u->depth) }}{{ $u->name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-400">Lãnh đạo đơn vị phải chọn đơn vị: họ chỉ duyệt hồ sơ của đơn vị đó và các đơn vị con.</p>
        @error('unit_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
<div class="mt-5 rounded-lg bg-slate-50 px-4 py-3 text-xs text-slate-500">
    <p class="font-medium text-slate-700">Quyền của từng vai trò</p>
    <ul class="mt-1.5 list-disc space-y-0.5 pl-4">
        <li><b>Quản trị viên</b>: toàn quyền, duyệt thay ở mọi bước.</li>
        <li><b>Ban Giám hiệu</b>: xem toàn bộ hồ sơ đã gửi, duyệt ở bước cuối.</li>
        <li><b>Phòng chuyên trách đối ngoại</b>: thẩm định, xem và hỗ trợ tất cả hồ sơ.</li>
        <li><b>Lãnh đạo đơn vị</b>: duyệt hồ sơ của đơn vị mình.</li>
        <li><b>Cán bộ, giảng viên</b>: tự lập hồ sơ xin đi công tác, nộp báo cáo.</li>
    </ul>
</div>
