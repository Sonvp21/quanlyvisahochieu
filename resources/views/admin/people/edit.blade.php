<x-app-layout>
    <div class="mx-auto max-w-3xl space-y-5">
        <x-alert-success />
        <a href="{{ route('admin.people.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Hồ sơ nhân sự &amp; khách
        </a>
        <h2 class="text-xl font-semibold text-slate-900">{{ $person->full_name }}</h2>

        <form method="POST" action="{{ route('admin.people.update', $person) }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            @include('admin.people.form')
            <div class="mt-6 flex justify-end gap-2">
                <a href="{{ route('admin.people.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Quay lại</a>
                <button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Lưu thay đổi</button>
            </div>
        </form>

        {{-- Hộ chiếu & visa --}}
        @php $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" x-data="{ addP: {{ $errors->hasAny(['passport_number','expiry_date']) && old('_doc') === 'passport' ? 'true' : 'false' }}, addV: {{ $errors->hasAny(['visa_type','expiry_date']) && old('_doc') === 'visa' ? 'true' : 'false' }} }">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="text-base font-semibold text-slate-900">Hộ chiếu &amp; visa</h3>
                @unless ($person->student_id)
                    <div class="flex gap-2">
                        <button type="button" @click="addP = !addP" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">+ Hộ chiếu</button>
                        <button type="button" @click="addV = !addV" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">+ Visa</button>
                    </div>
                @endunless
            </div>

            <div class="mt-4 space-y-2 text-sm">
                @foreach ($person->passports as $pp)
                    <div class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-4 py-2.5">
                        <span><span class="font-medium text-slate-800">Hộ chiếu {{ $pp->passport_number }}</span> <span class="text-slate-500">· {{ $pp->country_of_issue }}</span></span>
                        <span class="flex items-center gap-3">
                            <span class="{{ \Carbon\Carbon::parse($pp->expiry_date)->isPast() ? 'text-red-600' : 'text-slate-500' }}">Hết hạn {{ \Carbon\Carbon::parse($pp->expiry_date)->format('d/m/Y') }}</span>
                            @unless ($person->student_id)
                                <form method="POST" action="{{ route('admin.people.passports.destroy', [$person, $pp]) }}" onsubmit="return confirm('Xóa hộ chiếu này?')">@csrf @method('DELETE')<button class="text-slate-400 hover:text-red-600" title="Xóa">✕</button></form>
                            @endunless
                        </span>
                    </div>
                @endforeach
                @foreach ($person->visas as $vv)
                    <div class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-4 py-2.5">
                        <span><span class="font-medium text-slate-800">Visa {{ $vv->visa_type }}</span> <span class="text-slate-500">· {{ $vv->visa_number }} {{ $vv->country }}</span></span>
                        <span class="flex items-center gap-3">
                            <span class="{{ \Carbon\Carbon::parse($vv->expiry_date)->isPast() ? 'text-red-600' : 'text-slate-500' }}">Hết hạn {{ \Carbon\Carbon::parse($vv->expiry_date)->format('d/m/Y') }}</span>
                            @unless ($person->student_id)
                                <form method="POST" action="{{ route('admin.people.visas.destroy', [$person, $vv]) }}" onsubmit="return confirm('Xóa visa này?')">@csrf @method('DELETE')<button class="text-slate-400 hover:text-red-600" title="Xóa">✕</button></form>
                            @endunless
                        </span>
                    </div>
                @endforeach
                @if ($person->passports->isEmpty() && $person->visas->isEmpty())
                    <p class="text-slate-400">Chưa có giấy tờ.</p>
                @endif
                @if ($person->student_id)
                    <p class="text-xs text-slate-400">Giấy tờ của sinh viên quốc tế được quản lý ở mục Sinh viên.</p>
                @endif
            </div>

            @unless ($person->student_id)
                <form x-show="addP" x-cloak method="POST" action="{{ route('admin.people.passports.store', $person) }}" class="mt-4 grid grid-cols-1 gap-4 border-t border-slate-100 pt-4 md:grid-cols-2">
                    @csrf
                    <input type="hidden" name="_doc" value="passport">
                    <div><label class="text-sm font-medium text-slate-600">Số hộ chiếu *</label><input name="passport_number" value="{{ old('passport_number') }}" required class="{{ $in }}">@error('passport_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="text-sm font-medium text-slate-600">Quốc gia cấp</label><input name="country_of_issue" value="{{ old('country_of_issue') }}" class="{{ $in }}"></div>
                    <div><label class="text-sm font-medium text-slate-600">Nơi cấp</label><input name="place_of_issue" value="{{ old('place_of_issue') }}" class="{{ $in }}"></div>
                    <div><label class="text-sm font-medium text-slate-600">Ngày cấp</label><input type="date" name="issue_date" value="{{ old('issue_date') }}" class="{{ $in }}"></div>
                    <div><label class="text-sm font-medium text-slate-600">Ngày hết hạn *</label><input type="date" name="expiry_date" value="{{ old('expiry_date') }}" required class="{{ $in }}">@error('expiry_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div class="flex items-end justify-end"><button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Lưu hộ chiếu</button></div>
                </form>

                <form x-show="addV" x-cloak method="POST" action="{{ route('admin.people.visas.store', $person) }}" class="mt-4 grid grid-cols-1 gap-4 border-t border-slate-100 pt-4 md:grid-cols-2">
                    @csrf
                    <input type="hidden" name="_doc" value="visa">
                    <div><label class="text-sm font-medium text-slate-600">Loại visa *</label><input name="visa_type" value="{{ old('visa_type') }}" required placeholder="VD: DN, LĐ, NG…" class="{{ $in }}">@error('visa_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="text-sm font-medium text-slate-600">Số visa</label><input name="visa_number" value="{{ old('visa_number') }}" class="{{ $in }}"></div>
                    <div><label class="text-sm font-medium text-slate-600">Quốc gia cấp</label><input name="country" value="{{ old('country') }}" class="{{ $in }}"></div>
                    <div><label class="text-sm font-medium text-slate-600">Loại nhập cảnh</label><select name="entry_type" class="{{ $in }}"><option value="single">Một lần</option><option value="multiple">Nhiều lần</option></select></div>
                    <div><label class="text-sm font-medium text-slate-600">Ngày cấp</label><input type="date" name="issue_date" value="{{ old('issue_date') }}" class="{{ $in }}"></div>
                    <div><label class="text-sm font-medium text-slate-600">Ngày hết hạn *</label><input type="date" name="expiry_date" value="{{ old('expiry_date') }}" required class="{{ $in }}">@error('expiry_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div class="flex items-end justify-end md:col-span-2"><button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Lưu visa</button></div>
                </form>
            @endunless
        </div>

        {{-- Lịch sử chuyến công tác --}}
        @php
            $personTrips = $person->trips()->with('unit')->orderByDesc('depart_at')->get();
            $doneTrips = $personTrips->whereIn('status', ['approved', 'completed']);
            $daysAbroad = $doneTrips->sum(fn ($t) => $t->days);
        @endphp
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="text-base font-semibold text-slate-900">Lịch sử chuyến công tác</h3>
                <p class="text-xs text-slate-500">{{ $doneTrips->count() }} chuyến đã duyệt · {{ $daysAbroad }} ngày</p>
            </div>
            <div class="mt-4 space-y-2 text-sm">
                @forelse ($personTrips as $t)
                    <a href="{{ route('trips.show', $t) }}" class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-4 py-2.5 transition hover:bg-blue-50">
                        <span class="min-w-0">
                            <span class="block truncate font-medium text-slate-800">{{ $t->title }}</span>
                            <span class="text-xs text-slate-400">{{ $t->country }} · {{ $t->depart_at->format('d/m/Y') }} → {{ $t->return_at->format('d/m/Y') }}</span>
                        </span>
                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-medium {{ $t->status_class }}">{{ $t->status_label }}</span>
                    </a>
                @empty
                    <p class="text-slate-400">Chưa tham gia chuyến công tác nào.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
