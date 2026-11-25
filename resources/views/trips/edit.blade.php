<x-app-layout>
    <div class="mx-auto max-w-4xl space-y-5">
        <a href="{{ route('trips.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Đoàn ra &amp; đoàn vào
        </a>
        <h2 class="text-xl font-semibold text-slate-900">Sửa hồ sơ {{ $trip->code }}</h2>
        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('trips.update', $trip) }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            @include('trips.form')
            <div class="mt-6 flex flex-wrap justify-end gap-2">
                <a href="{{ route('trips.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Hủy</a>
                <button name="action" value="save" class="rounded-lg border border-blue-700 bg-white px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50">Lưu dự thảo</button>
                <button name="action" value="submit" class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Lưu và gửi duyệt</button>
            </div>
        </form>
    </div>
</x-app-layout>
