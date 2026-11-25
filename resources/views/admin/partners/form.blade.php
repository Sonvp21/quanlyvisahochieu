<x-app-layout>
    @php $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; $edit = $partner->exists; @endphp
    <div class="mx-auto max-w-3xl space-y-5">
        <a href="{{ $edit ? route('admin.partners.show', $partner) : route('admin.partners.index') }}" class="text-sm text-slate-500 hover:text-blue-700">← Quay lại</a>
        <form method="POST" action="{{ $edit ? route('admin.partners.update', $partner) : route('admin.partners.store') }}" class="grid grid-cols-1 gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:grid-cols-2">
            @csrf @if ($edit) @method('PUT') @endif
            <h2 class="text-lg font-semibold text-slate-900 md:col-span-2">{{ $edit ? 'Sửa đối tác' : 'Thêm đối tác' }}</h2>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-600">Tên đối tác <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $partner->name) }}" class="{{ $in }}" required>
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div><label class="text-sm font-medium text-slate-600">Tên viết tắt</label><input type="text" name="short_name" value="{{ old('short_name', $partner->short_name) }}" class="{{ $in }}"></div>
            <div>
                <label class="text-sm font-medium text-slate-600">Loại</label>
                <select name="partner_type" class="{{ $in }}">
                    @foreach (\App\Models\Partner::TYPES as $k => $v)<option value="{{ $k }}" @selected(old('partner_type', $partner->partner_type) === $k)>{{ $v }}</option>@endforeach
                </select>
            </div>
            <div><label class="text-sm font-medium text-slate-600">Quốc gia</label><input type="text" name="country" value="{{ old('country', $partner->country) }}" class="{{ $in }}"></div>
            <div><label class="text-sm font-medium text-slate-600">Thành phố</label><input type="text" name="city" value="{{ old('city', $partner->city) }}" class="{{ $in }}"></div>
            <div class="md:col-span-2"><label class="text-sm font-medium text-slate-600">Website</label><input type="text" name="website" value="{{ old('website', $partner->website) }}" class="{{ $in }}"></div>
            <div><label class="text-sm font-medium text-slate-600">Người liên hệ</label><input type="text" name="contact_name" value="{{ old('contact_name', $partner->contact_name) }}" class="{{ $in }}"></div>
            <div><label class="text-sm font-medium text-slate-600">Email liên hệ</label><input type="email" name="contact_email" value="{{ old('contact_email', $partner->contact_email) }}" class="{{ $in }}">@error('contact_email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="text-sm font-medium text-slate-600">Điện thoại</label><input type="text" name="contact_phone" value="{{ old('contact_phone', $partner->contact_phone) }}" class="{{ $in }}"></div>
            <div class="md:col-span-2"><label class="text-sm font-medium text-slate-600">Ghi chú</label><textarea name="notes" rows="3" class="{{ $in }}">{{ old('notes', $partner->notes) }}</textarea></div>
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700 md:col-span-2">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $partner->is_active)) class="rounded border-slate-300 text-blue-700 focus:ring-blue-600"> Đang hợp tác
            </label>
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 md:col-span-2">
                <button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Lưu</button>
            </div>
        </form>
    </div>
</x-app-layout>
