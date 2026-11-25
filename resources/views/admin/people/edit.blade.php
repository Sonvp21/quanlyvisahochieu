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

        {{-- Giấy tờ đã có --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Hộ chiếu &amp; visa</h3>
            <div class="mt-4 space-y-2 text-sm">
                @forelse ($person->passports as $pp)
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-2.5">
                        <span><span class="font-medium text-slate-800">Hộ chiếu {{ $pp->passport_number }}</span> <span class="text-slate-500">· {{ $pp->country_of_issue }}</span></span>
                        <span class="text-slate-500">Hết hạn {{ \Carbon\Carbon::parse($pp->expiry_date)->format('d/m/Y') }}</span>
                    </div>
                @empty
                @endforelse
                @forelse ($person->visas as $vv)
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-2.5">
                        <span><span class="font-medium text-slate-800">Visa {{ $vv->visa_type }}</span> <span class="text-slate-500">· {{ $vv->visa_number }}</span></span>
                        <span class="text-slate-500">Hết hạn {{ \Carbon\Carbon::parse($vv->expiry_date)->format('d/m/Y') }}</span>
                    </div>
                @empty
                @endforelse
                @if ($person->passports->isEmpty() && $person->visas->isEmpty())
                    <p class="text-slate-400">Chưa có giấy tờ. Việc nhập hộ chiếu, visa cho cán bộ và khách sẽ có ở giai đoạn tiếp theo.</p>
                @endif
            </div>
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
