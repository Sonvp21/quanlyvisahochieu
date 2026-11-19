<x-app-layout>
    <div class="space-y-6 m-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Thêm đoàn khách nước ngoài</h1>
            <p class="text-sm text-slate-500">Khai báo thông tin đoàn khách/chuyên gia nước ngoài đến làm việc</p>
        </div>

        <form method="POST" action="{{ route('admin.delegations.store') }}">
            @csrf
            @include('admin.delegations.form', ['delegation' => $delegation, 'isEdit' => false])
        </form>
    </div>
</x-app-layout>
