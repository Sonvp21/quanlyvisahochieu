{{-- Sidebar sinh viên — được include từ layouts/app.blade.php (dùng chung biến Alpine sidebarOpen) --}}
@php
    $studentMenu = [
        'Tài khoản của tôi' => [
            ['Tổng quan', 'student.dashboard', 'student.dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['Hồ sơ của tôi', 'student.profile.show', 'student.profile.*', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['Hộ chiếu', 'student.passport.index', 'student.passport.*', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ],
    ];
@endphp

<div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"></div>

<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col
              transform transition-transform duration-200 lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:shrink-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-5 h-16 border-b border-slate-100 shrink-0">
        <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-700 to-indigo-600 text-white flex items-center justify-center shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        <span class="leading-tight">
            <span class="block text-sm font-bold text-slate-900">GIRC System</span>
            <span class="block text-[11px] text-slate-400">Cổng sinh viên quốc tế</span>
        </span>
    </a>

    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5">
        @foreach ($studentMenu as $group => $items)
            <div>
                <p class="px-3 mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ $group }}</p>
                <div class="space-y-0.5">
                    @foreach ($items as [$label, $routeName, $pattern, $icon])
                        @php $active = request()->routeIs($pattern); @endphp
                        <a href="{{ route($routeName) }}" @click="sidebarOpen = false"
                           class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                                  {{ $active ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            @if ($active)
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r bg-blue-600"></span>
                            @endif
                            <svg class="w-[18px] h-[18px] shrink-0 {{ $active ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/>
                            </svg>
                            <span class="truncate">{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="shrink-0 border-t border-slate-100 p-3">
        <div class="flex items-center gap-3 rounded-xl bg-slate-50 px-3 py-2.5">
            <span class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-semibold shrink-0">
                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
            </span>
            <div class="min-w-0 flex-1 leading-tight">
                <p class="truncate text-sm font-medium text-slate-800">{{ Auth::user()->name }}</p>
                <p class="text-[11px] text-slate-400">Sinh viên quốc tế</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Đăng xuất"
                        class="p-2 rounded-lg text-slate-400 hover:bg-white hover:text-red-600 transition">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
