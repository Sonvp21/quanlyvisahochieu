@php $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp
<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600">Tên đơn vị <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $unit->name) }}" required class="{{ $in }}">
        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600">Mã (viết tắt) <span class="text-red-500">*</span></label>
        <input type="text" name="code" value="{{ old('code', $unit->code) }}" required class="{{ $in }}">
        @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600">Loại đơn vị</label>
        <select name="type" class="{{ $in }}">
            @foreach (\App\Models\Unit::TYPES as $k => $v)
                <option value="{{ $k }}" @selected(old('type', $unit->type) === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600">Trực thuộc</label>
        <select name="parent_id" class="{{ $in }}">
            <option value="">— Cấp cao nhất —</option>
            @foreach ($parents as $p)
                <option value="{{ $p->id }}" @selected((string) old('parent_id', $unit->parent_id) === (string) $p->id)>{{ str_repeat('— ', $p->depth) }}{{ $p->name }}</option>
            @endforeach
        </select>
        @error('parent_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600">Thứ tự hiển thị</label>
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $unit->sort_order) }}" class="{{ $in }}">
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $unit->is_active ?? true)) class="rounded border-slate-300 text-blue-700 focus:ring-blue-600">
            Đơn vị đang hoạt động
        </label>
    </div>
</div>
