<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-red-50 via-indigo-50 to-purple-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- WELCOME HEADER --}}
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            Xin chào, {{ $student->full_name }}! 👋
                        </h1>
                        <p class="text-gray-600 text-lg">
                            Mã sinh viên: <span class="font-semibold text-blue-600">{{ $student->student_code }}</span>
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Hôm nay</p>
                            <p class="text-xl font-bold text-gray-900">{{ now()->format('d/m/Y') }}</p>
                            <p class="text-sm text-gray-600 uppercase">{{ now()->locale('vi')->dayName }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- QUICK STATS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Passport Status --}}
                <div
                    class="bg-white rounded-2xl shadow-lg p-6 border-2 border-transparent hover:border-blue-200 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                            <span class="text-3xl">📘</span>
                        </div>
                        @php
                            $passportStatus = $student->getPassportStatus();
                            $passportColor = $student->getPassportStatusColor();
                            $statusBg = match ($passportColor) {
                                'green' => 'bg-green-100 text-green-800',
                                'yellow' => 'bg-yellow-100 text-yellow-800',
                                'red' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusBg }}">
                            {{ $student->getPassportStatusText() }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hộ chiếu</h3>
                    <p class="text-sm text-gray-600">
                        @if ($student->passport)
                            Số: {{ $student->passport->passport_number }}
                        @else
                            Chưa có thông tin
                        @endif
                    </p>
                </div>

                {{-- Visa Status --}}
                <div
                    class="bg-white rounded-2xl shadow-lg p-6 border-2 border-transparent hover:border-purple-200 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                            <span class="text-3xl">📗</span>
                        </div>
                        @php
                            $visaStatus = $student->getVisaStatus();
                            $visaColor = $student->getVisaStatusColor();
                            $statusBg = match ($visaColor) {
                                'green' => 'bg-green-100 text-green-800',
                                'yellow' => 'bg-yellow-100 text-yellow-800',
                                'red' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusBg }}">
                            {{ $student->getVisaStatusText() }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Visa</h3>
                    <p class="text-sm text-gray-600">
                        @if ($student->visa)
                            Số: {{ $student->visa->visa_number }}
                        @else
                            Chưa có thông tin
                        @endif
                    </p>
                </div>

                {{-- Email --}}
                <div
                    class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>

                        </div>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Email</h3>
                    <p class="text-sm text-blue-100 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>

            {{-- MAIN CONTENT - PASSPORT & VISA DETAILS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

                {{-- HỘ CHIẾU --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-2">
                                    <span class="text-3xl">📘</span>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">Hộ chiếu</h2>
                                    <p class="text-blue-100 text-sm">Passport Information</p>
                                </div>
                            </div>
                            @php
                                $passportStatusColor = match ($student->getPassportStatusColor()) {
                                    'green' => 'bg-green-500',
                                    'yellow' => 'bg-yellow-400',
                                    'red' => 'bg-red-500',
                                    default => 'bg-gray-400',
                                };
                            @endphp
                            <div class="w-3 h-3 {{ $passportStatusColor }} rounded-full animate-pulse"></div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if ($student->passport)
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Số hộ chiếu</span>
                                    <span
                                        class="text-base font-bold text-gray-900">{{ $student->passport->passport_number }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Ngày cấp</span>
                                    <span class="text-base font-semibold text-gray-900">
                                        {{ $student->passport->issue_date ? date('d/m/Y', strtotime($student->passport->issue_date)) : 'Chưa có' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center py-3">
                                    <span class="text-sm font-medium text-gray-600">Ngày hết hạn</span>
                                    <span class="text-base font-semibold text-gray-900">
                                        {{ date('d/m/Y', strtotime($student->passport->expiry_date)) }}
                                    </span>
                                </div>
                            </div>

                            {{-- COUNTDOWN --}}
                            <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-5 mb-4">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm font-bold text-gray-700">Thời gian </p>
                                </div>
                                <p id="passport-countdown" class="text-2xl font-bold text-center"></p>
                            </div>

                            {{-- Cảnh báo --}}
                            <div id="passport-warning"></div>

                            @if ($student->passport->image)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-600 mb-2">Ảnh hộ chiếu</p>
                                    <img src="{{ asset('storage/' . $student->passport->image) }}"
                                        class="w-full rounded-lg shadow-md border border-gray-200 cursor-pointer hover:scale-105 transition-transform duration-300 preview-image"
                                        onclick="openImageModal(this.src)" alt="Passport">

                                </div>
                            @endif
                        @else
                            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                                <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 font-medium text-center">Chưa có thông tin hộ chiếu</p>
                                <p class="text-sm text-gray-400 text-center mt-1">Vui lòng cập nhật hồ sơ</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- VISA --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-2">
                                    <span class="text-3xl">📗</span>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">Visa</h2>
                                    <p class="text-purple-100 text-sm">Visa Information</p>
                                </div>
                            </div>
                            @php
                                $visaStatusColor = match ($student->getVisaStatusColor()) {
                                    'green' => 'bg-green-500',
                                    'yellow' => 'bg-yellow-400',
                                    'red' => 'bg-red-500',
                                    default => 'bg-gray-400',
                                };
                            @endphp
                            <div class="w-3 h-3 {{ $visaStatusColor }} rounded-full animate-pulse"></div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if ($student->visa)
                            <div class="space-y-4 mb-6">

                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Số visa</span>
                                    <span
                                        class="text-base font-semibold text-gray-900">{{ $student->visa->visa_number ?? 'Chưa có' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Ngày cấp</span>
                                    <span class="text-base font-semibold text-gray-900">
                                        {{ $student->visa->issue_date ? date('d/m/Y', strtotime($student->visa->issue_date)) : 'Chưa có' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center py-3">
                                    <span class="text-sm font-medium text-gray-600">Ngày hết hạn</span>
                                    <span class="text-base font-semibold text-gray-900">
                                        {{ date('d/m/Y', strtotime($student->visa->expiry_date)) }}
                                    </span>
                                </div>
                            </div>

                            {{-- COUNTDOWN --}}
                            <div class="bg-gradient-to-br from-gray-50 to-purple-50 rounded-xl p-5 mb-4">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm font-bold text-gray-700">Thời gian </p>
                                </div>
                                <p id="visa-countdown" class="text-2xl font-bold text-center"></p>
                            </div>

                            {{-- Cảnh báo --}}
                            <div id="visa-warning"></div>

                            @if ($student->visa->image)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-600 mb-2">Ảnh visa</p>
                                    <img src="{{ asset('storage/' . $student->visa->image) }}"
                                        class="w-full rounded-lg shadow-md border border-gray-200 cursor-pointer hover:scale-105 transition-transform duration-300 preview-image"
                                        onclick="openImageModal(this.src)" alt="Visa">

                                </div>
                            @endif
                        @else
                            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                                <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 font-medium text-center">Chưa có thông tin visa</p>
                                <p class="text-sm text-gray-400 text-center mt-1">Vui lòng cập nhật hồ sơ</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- CTA BUTTON --}}
            <div class="text-center">
                <a href="{{ route('student.profile.show') }}"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Cập nhật hồ sơ
                </a>
            </div>

        </div>
    </div>

    {{-- JS Countdown --}}
    <script>
        const passportExpiredAt =
            @if ($student->passport && $student->passport->expiry_date)
                new Date("{{ $student->passport->expiry_date }}T23:59:59").getTime();
            @else
                null;
            @endif

        const visaExpiredAt =
            @if ($student->visa && $student->visa->expiry_date)
                new Date("{{ $student->visa->expiry_date }}T23:59:59").getTime();
            @else
                null;
            @endif

        function startCountdown(targetTime, countdownId, warningId, typeText) {
            if (!targetTime) return;

            const el = document.getElementById(countdownId);
            const warningEl = document.getElementById(warningId);

            function update() {
                const now = new Date().getTime();
                let distance = targetTime - now;
                let expired = false;

                if (distance < 0) {
                    expired = true;
                    distance = Math.abs(distance);
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                let color = "text-green-600";
                if (days < 30) color = "text-yellow-600";
                if (expired) color = "text-red-600";

                el.innerHTML = `
                    <span class="${color}">
                        ${expired ? 'Đã hết hạn' : 'Còn'}
                        <strong>${days}</strong> ngày
                        <strong>${hours}</strong> giờ
                        <strong>${minutes}</strong> phút
                        <strong>${seconds}</strong> giây
                    </span>
                `;

                if (days < 30 || expired) {
                    const alertColor = expired ? 'red' : 'yellow';
                    warningEl.innerHTML = `
                        <div class="p-4 bg-${alertColor}-50 border-l-4 border-${alertColor}-500 rounded-lg animate-pulse">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-${alertColor}-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-${alertColor}-800 text-sm font-bold">
                                        ${expired ? '⚠️ Đã hết hạn!' : '⏰ Sắp hết hạn!'}
                                    </p>
                                    <p class="text-${alertColor}-700 text-sm mt-1">
                                        ${typeText} của bạn ${expired ? 'đã hết hạn' : 'sắp hết hạn'}. Vui lòng cập nhật sớm!
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    warningEl.innerHTML = '';
                }
            }

            update();
            setInterval(update, 1000);
        }

        startCountdown(passportExpiredAt, "passport-countdown", "passport-warning", "Hộ chiếu");
        startCountdown(visaExpiredAt, "visa-countdown", "visa-warning", "Visa");
    </script>


    {{-- SCRIPTS --}}
    <script>
        let scale = 1;
        let isDragging = false;
        let hasDragged = false;
        let startX = 0;
        let startY = 0;
        let translateX = 0;
        let translateY = 0;

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

        document.addEventListener("DOMContentLoaded", () => {
            const img = document.getElementById('modalImage');
            const modal = document.getElementById('imageModal');

            img.addEventListener('mousedown', (e) => {
                if (scale <= 1) return;
                e.preventDefault();
                e.stopPropagation();
                isDragging = true;
                hasDragged = false;
                startX = e.clientX - translateX;
                startY = e.clientY - translateY;
                img.style.cursor = "grabbing";
            });

            window.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                hasDragged = true;
                translateX = e.clientX - startX;
                translateY = e.clientY - startY;
                limitTranslate();
                applyTransform();
            });

            window.addEventListener('mouseup', () => {
                if (!isDragging) return;
                isDragging = false;
                img.style.cursor = scale > 1 ? "grab" : "default";
            });

            img.addEventListener('click', (e) => {
                if (hasDragged) {
                    e.preventDefault();
                    e.stopPropagation();
                    hasDragged = false;
                }
            });

            img.addEventListener('wheel', (e) => {
                e.preventDefault();
                if (e.deltaY < 0) zoomIn();
                else zoomOut();
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeImageModal();
            });
        });
    </script>

    {{-- <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50 p-8">
        <!-- Thêm p-4 để có padding xung quanh -->

        <div class="relative max-w-5xl w-full h-full flex items-center justify-center">
            <!-- Đổi max-w-4xl thành max-w-6xl -->

            <!-- Thanh điều khiển -->
            <div class="absolute top-4 right-4 flex gap-2 z-40">
                <button onclick="zoomIn()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow text-xl hover:bg-gray-100">
                    ＋
                </button>
                <button onclick="zoomOut()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow text-xl hover:bg-gray-100">
                    －
                </button>
                <button onclick="closeImageModal()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center
                   shadow text-red-500 text-xl font-bold hover:bg-red-50">
                    ✕
                </button>
            </div>

            <!-- Khung ảnh -->
            <div id="imageContainer"
                class="overflow-hidden rounded-xl w-full h-full flex items-center justify-center">
                <img id="modalImage" src=""
                    class="max-w-full max-h-full object-contain
                    rounded-xl shadow-2xl border-4 border-red-500
                    cursor-grab select-none"
                    style="will-change: transform;">
            </div>

        </div>
    </div> --}}
 {{-- IMAGE MODAL (Tái sử dụng từ dashboard) --}}
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
        <div class="relative max-w-4xl w-full mx-4">
            <div class="absolute -top-12 right-0 flex gap-2 z-40">
                <button onclick="zoomIn()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow text-xl hover:bg-gray-100 transition-colors">
                    ＋
                </button>
                <button onclick="zoomOut()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow text-xl hover:bg-gray-100 transition-colors">
                    －
                </button>
                <button onclick="closeImageModal()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow text-red-500 text-xl font-bold hover:bg-red-50 transition-colors">
                    ✕
                </button>
            </div>

            <div id="imageContainer" class="overflow-hidden rounded-xl flex items-center justify-center">
                <img id="modalImage" src=""
                    class="max-h-[90vh] object-contain rounded-xl shadow-2xl border-4 border-white cursor-grab select-none"
                    style="will-change: transform;">
            </div>
        </div>
    </div>
</x-app-layout>
