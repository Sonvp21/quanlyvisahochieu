<x-app-layout>
    <div class="mx-auto max-w-3xl space-y-5">
        <a href="{{ route('admin.people.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Hồ sơ nhân sự &amp; khách
        </a>
        <h2 class="text-xl font-semibold text-slate-900">Thêm hồ sơ</h2>
        <form method="POST" action="{{ route('admin.people.store') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @include('admin.people.form')
            <div class="mt-6 flex justify-end gap-2">
                <a href="{{ route('admin.people.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Hủy</a>
                <button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Lưu</button>
            </div>
        </form>
    </div>
</x-app-layout>
