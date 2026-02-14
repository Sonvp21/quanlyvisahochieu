<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Hộ chiếu của tôi</h1>

        @if (session('success'))
    <div id="success-alert"
         class="bg-green-100 text-green-700 p-3 mb-4 rounded transition-opacity duration-500">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(function () {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500); // xóa hẳn sau khi mờ đi
            }
        }, 5000); // 5 giây
    </script>
@endif


        <div class="bg-white shadow rounded-lg p-6">

            @if (empty($edit))
                <p><b>Số hộ chiếu:</b> {{ $passport->passport_number ?? 'Chưa có' }}</p>
                <p><b>Ngày cấp:</b> {{ $passport->issue_date ?? 'Chưa có' }}</p>
                <p><b>Ngày hết hạn:</b> {{ $passport->expiry_date ?? 'Chưa có' }}</p>
                <p><b>Nơi cấp:</b> {{ $passport->place_of_issue ?? 'Chưa có' }}</p>

                <div class="text-right mt-4">
                    <a href="{{ route('student.passport.show', ['edit' => 1]) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded">
                        Cập nhật hộ chiếu
                    </a>
                </div>
            @else
                <form method="POST" action="{{ route('student.passport.update') }}">
                    @csrf
                    @method('PUT')

                    <input name="passport_number" placeholder="Số hộ chiếu"
                        value="{{ $passport->passport_number ?? '' }}" class="w-full border p-2 mb-2">

                    <input type="date" name="issue_date" value="{{ $passport->issue_date ?? '' }}"
                        class="w-full border p-2 mb-2">

                    <input type="date" name="expiry_date" value="{{ $passport->expiry_date ?? '' }}"
                        class="w-full border p-2 mb-2">

                    <input name="place_of_issue" placeholder="Nơi cấp" value="{{ $passport->place_of_issue ?? '' }}"
                        class="w-full border p-2 mb-4">

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('student.passport.show') }}" class="bg-gray-400 px-4 py-2 rounded">Hủy</a>
                        <button class="bg-green-600 text-white px-4 py-2 rounded">
                            Lưu
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</x-app-layout>
