@php
    $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600';
    $dt = fn ($v) => old($v, $trip->{$v}?->format('Y-m-d\TH:i'));
@endphp
<div class="space-y-6"
     x-data="tripMembers(@js($people), @js($selected))">

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Vui lòng kiểm tra lại các trường được đánh dấu.
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-600">Tên chuyến đi / nội dung <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $trip->title) }}" required class="{{ $in }}" placeholder="VD: Tham dự hội thảo quốc tế về nông nghiệp thông minh">
            @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Loại hình <span class="text-red-500">*</span></label>
            <select name="trip_type" class="{{ $in }}">
                @foreach (\App\Models\Trip::TYPES as $k => $v)
                    <option value="{{ $k }}" @selected(old('trip_type', $trip->trip_type) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Đơn vị đề xuất</label>
            <select name="unit_id" class="{{ $in }}">
                <option value="">— Chưa xác định —</option>
                @foreach ($units as $u)
                    <option value="{{ $u->id }}" @selected((string) old('unit_id', $trip->unit_id) === (string) $u->id)>{{ str_repeat('— ', $u->depth) }}{{ $u->name }}</option>
                @endforeach
            </select>
            @error('unit_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Quốc gia <span class="text-red-500">*</span></label>
            <input type="text" name="country" value="{{ old('country', $trip->country) }}" required class="{{ $in }}">
            @error('country')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Thành phố / địa điểm</label>
            <input type="text" name="city" value="{{ old('city', $trip->city) }}" class="{{ $in }}">
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-600">Đơn vị mời / tiếp nhận</label>
            <input type="text" name="host_org" value="{{ old('host_org', $trip->host_org) }}" class="{{ $in }}">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Thời gian đi <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="depart_at" value="{{ $dt('depart_at') }}" required class="{{ $in }}">
            @error('depart_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Thời gian về <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="return_at" value="{{ $dt('return_at') }}" required class="{{ $in }}">
            @error('return_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-600">Mục đích, nội dung công việc</label>
            <textarea name="purpose" rows="3" class="{{ $in }}">{{ old('purpose', $trip->purpose) }}</textarea>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Dự toán kinh phí (VNĐ)</label>
            <input type="number" min="0" name="budget_estimate" value="{{ old('budget_estimate', $trip->budget_estimate) }}" class="{{ $in }}">
            @error('budget_estimate')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Nguồn kinh phí</label>
            <input type="text" name="funding_source" value="{{ old('funding_source', $trip->funding_source) }}" class="{{ $in }}" placeholder="Ngân sách, đề tài, nhà tài trợ, tự túc…">
        </div>
    </div>

    {{-- Thành viên --}}
    <div class="border-t border-slate-100 pt-6">
        <h3 class="text-base font-semibold text-slate-900">Thành viên đi <span class="text-red-500">*</span></h3>
        <p class="mt-0.5 text-xs text-slate-500">Chọn từ danh sách hồ sơ. Nếu chưa có, hãy thêm ở mục “Hồ sơ nhân sự &amp; khách”.</p>
        @error('members.*.person_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror

        <div class="relative mt-3" @click.outside="open = false">
            <input type="text" x-model="q" @focus="open = true" @input="open = true" placeholder="Gõ tên để tìm và thêm thành viên…"
                   class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-600/20">
            <div x-show="open && results.length" x-cloak class="absolute z-20 mt-1 max-h-60 w-full overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-lg">
                <template x-for="p in results" :key="p.id">
                    <button type="button" @click="add(p)" class="flex w-full items-center justify-between px-3 py-2 text-left text-sm hover:bg-blue-50">
                        <span class="font-medium text-slate-800" x-text="p.name"></span>
                        <span class="ml-3 truncate text-xs text-slate-400" x-text="p.sub"></span>
                    </button>
                </template>
            </div>
        </div>

        <div class="mt-3 space-y-2">
            <template x-for="(m, i) in items" :key="m.person_id">
                <div class="flex flex-wrap items-center gap-2 rounded-lg bg-slate-50 px-3 py-2">
                    <input type="hidden" :name="`members[${i}][person_id]`" :value="m.person_id">
                    <span class="min-w-[160px] flex-1 text-sm font-medium text-slate-800" x-text="nameOf(m.person_id)"></span>
                    <input type="text" :name="`members[${i}][role]`" x-model="m.role" placeholder="Vai trò"
                           class="w-44 rounded-lg border-slate-300 py-1.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                    <button type="button" @click="items.splice(i, 1)" class="rounded-lg p-1.5 text-slate-400 hover:bg-red-50 hover:text-red-600" title="Bỏ">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
            <p x-show="!items.length" class="text-sm text-slate-400">Chưa chọn thành viên nào.</p>
        </div>
    </div>

    <div>
        <label class="text-sm font-medium text-slate-600">Ghi chú</label>
        <textarea name="notes" rows="2" class="{{ $in }}">{{ old('notes', $trip->notes) }}</textarea>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tripMembers', (people, selected) => ({
            people, items: selected.map(s => ({ person_id: Number(s.person_id), role: s.role || 'Thành viên' })),
            q: '', open: false,
            get results() {
                const q = this.q.trim().toLowerCase();
                const taken = new Set(this.items.map(i => i.person_id));
                return this.people.filter(p => !taken.has(p.id) && (!q || p.name.toLowerCase().includes(q))).slice(0, 8);
            },
            nameOf(id) { const p = this.people.find(x => x.id === Number(id)); return p ? p.name : '#' + id; },
            add(p) { this.items.push({ person_id: p.id, role: this.items.length ? 'Thành viên' : 'Trưởng đoàn' }); this.q = ''; this.open = false; },
        }));
    });
</script>
