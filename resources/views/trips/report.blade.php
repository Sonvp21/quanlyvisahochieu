<x-app-layout>
    <div class="mx-auto max-w-3xl space-y-5">
        <a href="{{ route('trips.show', $trip) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ $trip->title }}
        </a>
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Báo cáo kết quả chuyến công tác</h2>
            <p class="mt-0.5 text-sm text-slate-500">{{ $trip->code }} · {{ $trip->country }} · {{ $trip->time_range }}</p>
        </div>

        @php $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp
        <form method="POST" action="{{ route('trips.report.store', $trip) }}" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            <div>
                <label class="text-sm font-medium text-slate-600">Tóm tắt nội dung đã thực hiện <span class="text-red-500">*</span></label>
                <textarea name="summary" rows="5" required class="{{ $in }}">{{ old('summary', $trip->report?->summary) }}</textarea>
                @error('summary')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Kết quả đạt được</label>
                <textarea name="results" rows="4" class="{{ $in }}">{{ old('results', $trip->report?->results) }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Đề xuất, kiến nghị</label>
                <textarea name="recommendations" rows="3" class="{{ $in }}">{{ old('recommendations', $trip->report?->recommendations) }}</textarea>
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('trips.show', $trip) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Hủy</a>
                <button class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Nộp báo cáo</button>
            </div>
        </form>
    </div>
</x-app-layout>
