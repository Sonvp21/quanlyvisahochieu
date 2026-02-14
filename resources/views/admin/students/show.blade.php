<x-app-layout>
    {{-- <div class="max-w-5xl mx-auto p-8 space-y-6"> --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 p-8 space-y-6" >

{{-- HEADER --}}
            <div class="flex justify-between items-center mb-0">
                <div class="mb-0">
                <nav class="flex mb-0" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.students.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                    </path>
                                </svg>
                                Quản lý sinh viên
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500">Chi tiết sinh viên</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                    <div class="hidden md:block">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        <div class="flex justify-between items-center">

            <h1 class="text-3xl font-bold">Chi tiết sinh viên</h1>
            <div class="flex gap-2">

                <a href="{{ route('admin.students.edit', $student) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    ✏️ Chỉnh sửa
                </a>
                <a href="{{ route('admin.students.index') }}"
                    class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                    ← Quay lại
                </a>
            </div>
        </div>

        {{-- ===================== THÔNG TIN SINH VIÊN ===================== --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">👤 Thông tin sinh viên</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Họ tên</p>
                    <p class="font-semibold text-lg">{{ $student->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Mã sinh viên</p>
                    <p class="font-semibold">{{ $student->student_code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-semibold">{{ $student->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Loại sinh viên</p>
                    <p class="font-semibold">
                        @if ($student->student_type == 'exchange')
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Trao đổi</span>
                        @elseif($student->student_type == 'regular')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Chính quy</span>
                        @elseif($student->student_type == 'postgraduate')
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Sau đại học</span>
                        @else
                            <span class="text-gray-400">Chưa có</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ngày sinh</p>
                    <p class="font-semibold">
                        {{ $student->date_of_birth ? date('d/m/Y', strtotime($student->date_of_birth)) : 'Chưa có' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Giới tính</p>
                    <p class="font-semibold">
                        @if ($student->gender == 'male')
                            👨 Nam
                        @elseif($student->gender == 'female')
                            👩 Nữ
                        @elseif($student->gender == 'other')
                            🧑 Khác
                        @else
                            <span class="text-gray-400">Chưa có</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Quốc tịch</p>
                    <p class="font-semibold">{{ $student->nationality ?? 'Chưa có' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Số điện thoại</p>
                    <p class="font-semibold">{{ $student->phone ?? 'Chưa có' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ngành học</p>
                    <p class="font-semibold">{{ $student->major ?? 'Chưa có' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ngày nhập học</p>
                    <p class="font-semibold">
                        {{ $student->enrollment_date ? date('d/m/Y', strtotime($student->enrollment_date)) : 'Chưa có' }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600">Địa chỉ tại Việt Nam</p>
                    <p class="font-semibold">{{ $student->address ?? 'Chưa có' }}</p>
                </div>
            </div>
        </div>

        {{-- ===================== HỘ CHIẾU ===================== --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-xl font-bold">📘 Hộ chiếu</h2>
                @php
                    $passportStatus = $student->getPassportStatus();
                    $passportColor = $student->getPassportStatusColor();
                    $bgColor = match ($passportColor) {
                        'green' => 'bg-green-500',
                        'yellow' => 'bg-yellow-500',
                        'red' => 'bg-red-500',
                        default => 'bg-gray-400',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-white text-sm font-semibold {{ $bgColor }}">
                    {{ $student->getPassportStatusText() }}
                </span>
            </div>

            @if ($student->passport)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Số hộ chiếu</p>
                        <p class="font-semibold">{{ $student->passport->passport_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Quốc gia cấp</p>
                        <p class="font-semibold">{{ $student->passport->country_of_issue ?? 'Chưa có' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nơi cấp</p>
                        <p class="font-semibold">{{ $student->passport->place_of_issue ?? 'Chưa có' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ngày cấp</p>
                        <p class="font-semibold">
                            {{ $student->passport->issue_date ? date('d/m/Y', strtotime($student->passport->issue_date)) : 'Chưa có' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ngày hết hạn</p>
                        <p class="font-semibold">{{ date('d/m/Y', strtotime($student->passport->expiry_date)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Người cập nhật cuối</p>
                        <p class="font-semibold">
                            @if ($student->passport->last_updated_by == 'admin')
                                👨‍💼 Admin
                            @else
                                👤 Sinh viên
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cập nhật lần cuối</p>
                        <p class="font-semibold text-xs">
                            {{ date('d/m/Y H:i', strtotime($student->passport->updated_at)) }}
                        </p>
                    </div>
                </div>

                @php $days = $student->getDaysUntilPassportExpiry(); @endphp
                @if ($days !== null)
                    <div
                        class="mt-4 p-4 {{ $days < 0 ? 'bg-red-50 border-red-500' : ($days <= 30 ? 'bg-yellow-50 border-yellow-500' : 'bg-green-50 border-green-500') }} border-l-4 rounded">
                        <div class="flex items-center">
                            @if ($days < 0)
                                <span class="text-2xl mr-2">⚠️</span>
                            @elseif($days <= 30)
                                <span class="text-2xl mr-2">⏰</span>
                            @else
                                <span class="text-2xl mr-2">✅</span>
                            @endif
                            <div>
                                <p class="font-bold text-sm">
                                    @if ($days < 0)
                                        <span class="text-red-700">CẢNH BÁO: Hộ chiếu đã hết hạn!</span>
                                    @elseif($days <= 30)
                                        <span class="text-yellow-700">CHÚ Ý: Hộ chiếu sắp hết hạn!</span>
                                    @else
                                        <span class="text-green-700">Hộ chiếu còn hạn sử dụng</span>
                                    @endif
                                </p>
                                <p class="text-sm mt-1">
                                <p class="text-sm mt-1">
                                    @if ($days < 0)
                                        <span class="text-red-600 font-bold">
                                            Đã hết hạn
                                            <span class="countdown expired"
                                                data-expiry="{{ \Carbon\Carbon::parse($student->passport->expiry_date)->endOfDay()->timestamp * 1000 }}"
                                                </span>
                                                trước
                                            </span>
                                        @elseif($days <= 30)
                                            <span class="text-yellow-600 font-bold">
                                                Còn
                                                <span class="countdown"
                                                    data-expiry="{{ \Carbon\Carbon::parse($student->passport->expiry_date)->endOfDay()->timestamp * 1000 }}"
                                                    </span>
                                                    nữa hết hạn
                                                </span>
                                            @else
                                                <span class="text-green-600">
                                                    Còn
                                                    <span class="countdown"
                                                        data-expiry="{{ \Carbon\Carbon::parse($student->passport->expiry_date)->endOfDay()->timestamp * 1000 }}"
                                                        </span>
                                                    </span>
                                    @endif

                                </p>

                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($student->passport->image)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2 font-semibold">📄 Ảnh hộ chiếu</p>
                        <img src="{{ asset('storage/' . $student->passport->image) }}"
                            class="w-48 mt-2 rounded shadow cursor-pointer hover:scale-105 transition preview-image"
                            onclick="openImageModal(this.src)" alt="Passport Image">

                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 text-lg">📭 Chưa có thông tin hộ chiếu</p>
                    <p class="text-gray-400 text-sm mt-2">Sinh viên chưa cập nhật thông tin hộ chiếu</p>
                </div>
            @endif
        </div>

        {{-- ===================== VISA ===================== --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-xl font-bold">📗 Visa</h2>
                @php
                    $visaStatus = $student->getVisaStatus();
                    $visaColor = $student->getVisaStatusColor();
                    $bgColor = match ($visaColor) {
                        'green' => 'bg-green-500',
                        'yellow' => 'bg-yellow-500',
                        'red' => 'bg-red-500',
                        default => 'bg-gray-400',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-white text-sm font-semibold {{ $bgColor }}">
                    {{ $student->getVisaStatusText() }}
                </span>
            </div>

            @if ($student->visa)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Loại visa</p>
                        <p class="font-semibold">{{ $student->visa->visa_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Quốc gia cấp</p>
                        <p class="font-semibold">{{ $student->visa->country ?? 'Chưa có' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Số visa</p>
                        <p class="font-semibold">{{ $student->visa->visa_number ?? 'Chưa có' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Loại nhập cảnh</p>
                        <p class="font-semibold">
                            @if ($student->visa->entry_type == 'single')
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Đơn</span>
                            @else
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Nhiều lần</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Người cập nhật cuối</p>
                        <p class="font-semibold">
                            @if ($student->visa->last_updated_by == 'admin')
                                👨‍💼 Admin
                            @else
                                👤 Sinh viên
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ngày cấp</p>
                        <p class="font-semibold">
                            {{ $student->visa->issue_date ? date('d/m/Y', strtotime($student->visa->issue_date)) : 'Chưa có' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ngày hết hạn</p>
                        <p class="font-semibold">{{ date('d/m/Y', strtotime($student->visa->expiry_date)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cập nhật lần cuối</p>
                        <p class="font-semibold text-xs">
                            {{ date('d/m/Y H:i', strtotime($student->visa->updated_at)) }}
                        </p>
                    </div>
                </div>

                @php $days = $student->getDaysUntilVisaExpiry(); @endphp
                @if ($days !== null)
                    <div
                        class="mt-4 p-4 {{ $days < 0 ? 'bg-red-50 border-red-500' : ($days <= 30 ? 'bg-yellow-50 border-yellow-500' : 'bg-green-50 border-green-500') }} border-l-4 rounded">
                        <div class="flex items-center">
                            @if ($days < 0)
                                <span class="text-2xl mr-2">⚠️</span>
                            @elseif($days <= 30)
                                <span class="text-2xl mr-2">⏰</span>
                            @else
                                <span class="text-2xl mr-2">✅</span>
                            @endif
                            <div>
                                <p class="font-bold text-sm">
                                    @if ($days < 0)
                                        <span class="text-red-700">CẢNH BÁO: Visa đã hết hạn!</span>
                                    @elseif($days <= 30)
                                        <span class="text-yellow-700">CHÚ Ý: Visa sắp hết hạn!</span>
                                    @else
                                        <span class="text-green-700">Visa còn hạn sử dụng</span>
                                    @endif
                                </p>
                                <p class="text-sm mt-1">
                                    @if ($days < 0)
                                        <span class="text-red-600 font-bold">
                                            Đã hết hạn
                                            <span class="countdown expired"
                                                data-expiry="{{ \Carbon\Carbon::parse($student->visa->expiry_date)->endOfDay()->timestamp * 1000 }}"
                                                </span>
                                                trước
                                            </span>
                                        @elseif($days <= 30)
                                            <span class="text-yellow-600 font-bold">
                                                Còn
                                                <span class="countdown"
                                                    data-expiry="{{ \Carbon\Carbon::parse($student->visa->expiry_date)->endOfDay()->timestamp * 1000 }}"
                                                    </span>
                                                    nữa hết hạn
                                                </span>
                                            @else
                                                <span class="text-green-600">
                                                    Còn
                                                    <span class="countdown"
                                                        data-expiry="{{ \Carbon\Carbon::parse($student->visa->expiry_date)->endOfDay()->timestamp * 1000 }}"
                                                        </span>

                                                    </span>
                                    @endif

                                </p>

                            </div>
                        </div>
                    </div>
                @endif

                @if ($student->visa->image)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2 font-semibold">📄 Ảnh visa</p>
                        <img src="{{ asset('storage/' . $student->visa->image) }}"
                            class="w-48 mt-2 rounded shadow cursor-pointer hover:scale-105 transition preview-image"
                            onclick="openImageModal(this.src)" alt="Visa Image">

                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 text-lg">📭 Chưa có thông tin visa</p>
                    <p class="text-gray-400 text-sm mt-2">Sinh viên chưa cập nhật thông tin visa</p>
                </div>
            @endif
        </div>

        {{-- ===================== ACTIONS ===================== --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.students.index') }}"
                class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                ← Quay lại danh sách
            </a>

            <div class="flex gap-2">
                <a href="{{ route('admin.students.edit', $student) }}"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    ✏️ Chỉnh sửa thông tin
                </a>

                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline"
                    onsubmit="return confirm('⚠️ Bạn có chắc chắn muốn xóa sinh viên này?\n\nThao tác này sẽ xóa:\n- Thông tin sinh viên\n- Hộ chiếu\n- Visa\n- Tài khoản đăng nhập\n\nKhông thể hoàn tác!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">
                        🗑️ Xóa sinh viên
                    </button>
                </form>
            </div>
        </div>

    </div>

    <script>
        function startCountdown(el) {
            const expiry = Number(el.dataset.expiry);

            function update() {
                let diff = expiry - Date.now();
                const expired = diff < 0;
                diff = Math.abs(diff);

                const days = Math.floor(diff / 86400000);
                const hours = Math.floor((diff % 86400000) / 3600000);
                const minutes = Math.floor((diff % 3600000) / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);

                el.textContent = `${days} ngày ${hours} giờ ${minutes} phút ${seconds} giây`;
            }

            update();
            setInterval(update, 1000);
        }

        document.querySelectorAll('.countdown').forEach(startCountdown);
    </script>
    <script>
        let scale = 1;
        let isDragging = false;
        let hasDragged = false;
        let startX = 0;
        let startY = 0;
        let translateX = 0;
        let translateY = 0;

        /* ================= CORE ================= */

        function applyTransform() {
            const img = document.getElementById('modalImage');
            img.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
        }

        function clamp(v, min, max) {
            return Math.min(Math.max(v, min), max);
        }

        function limitTranslate() {
            const img = document.getElementById('modalImage');
            const container = document.getElementById('imageContainer');

            const cRect = container.getBoundingClientRect();
            const iRect = img.getBoundingClientRect();

            const maxX = Math.max(0, (iRect.width - cRect.width) / 2);
            const maxY = Math.max(0, (iRect.height - cRect.height) / 2);

            translateX = clamp(translateX, -maxX, maxX);
            translateY = clamp(translateY, -maxY, maxY);
        }

        /* ================= ZOOM ================= */

        function zoomIn() {
            scale += 0.2;
            applyTransform();
            limitTranslate();
        }

        function zoomOut() {
            scale -= 0.2;
            if (scale <= 1) {
                scale = 1;
                translateX = 0;
                translateY = 0;
            }
            applyTransform();
        }

        /* ================= MODAL ================= */

        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');

            img.src = src;

            scale = 1;
            translateX = 0;
            translateY = 0;
            applyTransform();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        /* ================= DRAG & PAN ================= */

        document.addEventListener("DOMContentLoaded", () => {
            const img = document.getElementById('modalImage');
            const modal = document.getElementById('imageModal');

            // Bắt đầu kéo
            img.addEventListener('mousedown', (e) => {
                if (scale <= 1) return; // chưa zoom thì không cho kéo

                e.preventDefault();
                e.stopPropagation();

                isDragging = true;
                hasDragged = false;

                startX = e.clientX - translateX;
                startY = e.clientY - translateY;

                img.style.cursor = "grabbing";
            });

            // Đang kéo
            window.addEventListener('mousemove', (e) => {
                if (!isDragging) return;

                hasDragged = true;

                translateX = e.clientX - startX;
                translateY = e.clientY - startY;

                limitTranslate();
                applyTransform();
            });

            // Thả chuột -> GIỮ NGUYÊN VỊ TRÍ ẢNH
            window.addEventListener('mouseup', () => {
                if (!isDragging) return;

                isDragging = false;
                img.style.cursor = scale > 1 ? "grab" : "default";
            });

            // Chặn click khi vừa kéo xong (không bị hiểu là click)
            img.addEventListener('click', (e) => {
                if (hasDragged) {
                    e.preventDefault();
                    e.stopPropagation();
                    hasDragged = false;
                }
            });

            // Zoom bằng con lăn chuột
            img.addEventListener('wheel', (e) => {
                e.preventDefault();
                if (e.deltaY < 0) zoomIn();
                else zoomOut();
            });

            // Click nền đen để đóng modal
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeImageModal();
                }
            });
        });
    </script>

    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">

        <div class="relative max-w-4xl w-full mx-4">

            <!-- Thanh điều khiển -->
            <div class="absolute -top-12 right-0 flex gap-2 z-40">
                <button onclick="zoomIn()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow text-xl">
                    ＋
                </button>
                <button onclick="zoomOut()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow text-xl">
                    －
                </button>
                <button onclick="closeImageModal()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center
                       shadow text-red-500 text-xl font-bold">
                    ✕
                </button>
            </div>

            <!-- Khung ảnh -->
            <div id="imageContainer" class="overflow-hidden rounded-xl flex items-center justify-center">
                <img id="modalImage" src=""
                    class="max-h-[90vh] object-contain
                        rounded-xl shadow-2xl border-4 border-white
                        cursor-grab select-none"
                    style="will-change: transform;">
            </div>

        </div>
    </div>

</x-app-layout>
