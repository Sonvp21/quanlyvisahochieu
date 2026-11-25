@php
    $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600';
    $incoming = $trip->direction === 'in';
    $dt = fn ($v) => old($v, $trip->{$v}?->format('Y-m-d\TH:i'));
@endphp
<div class="space-y-6"
     x-data="tripMembers(@js($people), @js($selected), '{{ route('trips.people.quick') }}', '{{ csrf_token() }}')">
    <input type="hidden" name="direction" value="{{ $trip->direction ?: 'out' }}">

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Vui lòng kiểm tra lại các trường được đánh dấu.
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-600">{{ $incoming ? 'Nội dung làm việc của đoàn' : 'Tên chuyến đi / nội dung' }} <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $trip->title) }}" required class="{{ $in }}" placeholder="{{ $incoming ? 'VD: Đoàn đại biểu trường đối tác làm việc về hợp tác đào tạo' : 'VD: Tham dự hội thảo quốc tế về nông nghiệp thông minh' }}">
            @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Loại hình <span class="text-red-500">*</span></label>
            <select name="trip_type" class="{{ $in }}">
                @foreach ($types as $k => $v)
                    <option value="{{ $k }}" @selected(old('trip_type', $trip->trip_type) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">{{ $incoming ? 'Đơn vị tiếp đón' : 'Đơn vị đề xuất' }}</label>
            <select name="unit_id" class="{{ $in }}">
                <option value="">— Chưa xác định —</option>
                @foreach ($units as $u)
                    <option value="{{ $u->id }}" @selected((string) old('unit_id', $trip->unit_id) === (string) $u->id)>{{ str_repeat('— ', $u->depth) }}{{ $u->name }}</option>
                @endforeach
            </select>
            @error('unit_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">{{ $incoming ? 'Quốc gia / vùng lãnh thổ của đoàn' : 'Quốc gia' }} <span class="text-red-500">*</span></label>
            <input type="text" name="country" value="{{ old('country', $trip->country) }}" required class="{{ $in }}">
            @error('country')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">{{ $incoming ? 'Thành phố xuất phát' : 'Thành phố / địa điểm' }}</label>
            <input type="text" name="city" value="{{ old('city', $trip->city) }}" class="{{ $in }}">
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-600">{{ $incoming ? 'Trường / tổ chức / doanh nghiệp cử đoàn' : 'Đơn vị mời / tiếp nhận' }}</label>
            <input type="text" name="host_org" value="{{ old('host_org', $trip->host_org) }}" class="{{ $in }}">
        </div>
        @if (isset($partners) && $partners->isNotEmpty())
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-600">Đối tác trong danh bạ (nếu có)</label>
            <select name="partner_id" class="{{ $in }}">
                <option value="">— Không liên kết —</option>
                @foreach ($partners as $p)
                    <option value="{{ $p->id }}" @selected(old('partner_id', $trip->partner_id) == $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <label class="text-sm font-medium text-slate-600">{{ $incoming ? 'Bắt đầu làm việc' : 'Thời gian đi' }} <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="depart_at" value="{{ $dt('depart_at') }}" required class="{{ $in }}">
            @error('depart_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">{{ $incoming ? 'Kết thúc làm việc' : 'Thời gian về' }} <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="return_at" value="{{ $dt('return_at') }}" required class="{{ $in }}">
            @error('return_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        @if ($incoming)
        <div>
            <label class="text-sm font-medium text-slate-600">Địa điểm làm việc</label>
            <input type="text" name="work_location" value="{{ old('work_location', $trip->work_location) }}" class="{{ $in }}">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600">Nơi lưu trú của đoàn</label>
            <input type="text" name="accommodation" value="{{ old('accommodation', $trip->accommodation) }}" class="{{ $in }}">
        </div>
        @endif
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-600">Mục đích, nội dung công việc</label>
            <textarea name="purpose" rows="3" class="{{ $in }}">{{ old('purpose', $trip->purpose) }}</textarea>
        </div>
        @if ($incoming)
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-600">Chương trình làm việc, đón tiếp (xe, ăn nghỉ, phiên dịch…)</label>
            <textarea name="reception_notes" rows="3" class="{{ $in }}">{{ old('reception_notes', $trip->reception_notes) }}</textarea>
        </div>
        @endif
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
        <h3 class="text-base font-semibold text-slate-900">{{ $incoming ? 'Thành viên đoàn khách' : 'Thành viên đi' }} <span class="text-red-500">*</span></h3>
        <p class="mt-0.5 text-xs text-slate-500">
            Chọn từ danh sách hồ sơ{{ $incoming ? ', hoặc thêm khách mới ngay bên dưới. Hộ chiếu, visa của khách nhập ở mục “Hồ sơ nhân sự & khách”.' : '. Nếu chưa có, hãy thêm ở mục “Hồ sơ nhân sự & khách”.' }}
        </p>
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

        @if ($incoming)
        <div class="mt-4">
            <button type="button" @click="quick.open = !quick.open" class="text-sm font-medium text-blue-700 hover:text-blue-800">+ Thêm khách mới vào danh sách</button>
            <div x-show="quick.open" x-cloak class="mt-3 grid grid-cols-1 gap-3 rounded-xl border border-blue-100 bg-blue-50/40 p-4 md:grid-cols-3">
                <input type="text" x-model="quick.full_name" placeholder="Họ và tên *" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600 md:col-span-2">
                <input type="text" x-model="quick.academic_title" placeholder="Học hàm, học vị (GS.TS, TS…)" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                <select x-model="quick.gender" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                    <option value="">Giới tính</option><option value="male">Nam</option><option value="female">Nữ</option><option value="other">Khác</option>
                </select>
                <input type="date" x-model="quick.date_of_birth" title="Ngày sinh" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                <input type="text" x-model="quick.nationality" placeholder="Quốc tịch" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                <input type="text" x-model="quick.position" placeholder="Chức vụ / nghề nghiệp" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                <input type="text" x-model="quick.organization" placeholder="Cơ quan / doanh nghiệp" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600 md:col-span-2">
                <div class="flex items-center gap-3 md:col-span-3">
                    <button type="button" @click="saveQuick()" :disabled="quick.busy" class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800 disabled:opacity-50">Thêm vào đoàn</button>
                    <span x-show="quick.error" x-text="quick.error" class="text-sm text-red-600"></span>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div>
        <label class="text-sm font-medium text-slate-600">Ghi chú</label>
        <textarea name="notes" rows="2" class="{{ $in }}">{{ old('notes', $trip->notes) }}</textarea>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tripMembers', (people, selected, quickUrl, csrf) => ({
            people, items: selected.map(s => ({ person_id: Number(s.person_id), role: s.role || 'Thành viên' })),
            q: '', open: false,
            quick: { open: false, busy: false, error: '', full_name: '', academic_title: '', gender: '', date_of_birth: '', nationality: '', position: '', organization: '' },
            get results() {
                const q = this.q.trim().toLowerCase();
                const taken = new Set(this.items.map(i => i.person_id));
                return this.people.filter(p => !taken.has(p.id) && (!q || p.name.toLowerCase().includes(q))).slice(0, 8);
            },
            nameOf(id) { const p = this.people.find(x => x.id === Number(id)); return p ? p.name : '#' + id; },
            add(p) { this.items.push({ person_id: p.id, role: this.items.length ? 'Thành viên' : 'Trưởng đoàn' }); this.q = ''; this.open = false; },
            async saveQuick() {
                this.quick.error = '';
                if (!this.quick.full_name.trim()) { this.quick.error = 'Nhập họ và tên khách.'; return; }
                this.quick.busy = true;
                try {
                    const body = {};
                    ['full_name', 'academic_title', 'gender', 'date_of_birth', 'nationality', 'position', 'organization'].forEach(k => { if (this.quick[k]) body[k] = this.quick[k]; });
                    const res = await fetch(quickUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify(body) });
                    if (!res.ok) {
                        const err = await res.json().catch(() => ({}));
                        this.quick.error = Object.values(err.errors || {})[0]?.[0] || 'Không thêm được, vui lòng thử lại.';
                        return;
                    }
                    const p = await res.json();
                    this.people.push(p);
                    this.add(p);
                    Object.assign(this.quick, { full_name: '', academic_title: '', gender: '', date_of_birth: '', nationality: '', position: '', organization: '', open: false });
                } finally { this.quick.busy = false; }
            },
        }));
    });
</script>
