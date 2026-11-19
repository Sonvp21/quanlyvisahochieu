<x-app-layout>
    <div class="space-y-6 m-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Sửa đoàn khách nước ngoài</h1>
            <p class="text-sm text-slate-500">{{ $delegation->document_no ?: 'Chưa có số công văn' }}</p>
        </div>

        <form method="POST" action="{{ route('admin.delegations.update', $delegation) }}">
            @csrf
            @method('PUT')
            @include('admin.delegations.form', ['delegation' => $delegation, 'isEdit' => true])
        </form>
    </div>
</x-app-layout>
