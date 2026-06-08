{{-- STUDENT SIDEBAR OVERLAY (mobile) --}}
<div id="studentSidebarOverlay" onclick="closeSidebar()"
    class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

{{-- STUDENT SIDEBAR --}}
<aside id="adminSidebar"
    class="fixed top-0 left-0 h-full w-64 bg-slate-900 flex flex-col z-50 -translate-x-full lg:translate-x-0 transition-transform duration-300">

    {{-- LOGO --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-700/50 flex-shrink-0">
        <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <div>
            <div class="text-sm font-bold text-slate-100 leading-tight">GIRC System</div>
            <div class="text-xs text-slate-500 leading-tight">Visa & Passport</div>
        </div>
    </div>

    {{-- NAV --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-1">

        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 px-3 pb-1">Menu</p>

        <a href="{{ route('student.dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-150
            {{ request()->routeIs('student.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Tổng quan
        </a>

        <a href="{{ route('student.profile.show') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-150
            {{ request()->routeIs('student.profile.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Hồ sơ của tôi
        </a>

    </nav>

    {{-- USER FOOTER --}}
    <div class="px-3 py-4 border-t border-slate-700/50 flex-shrink-0">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-slate-800/60">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-xs font-semibold text-slate-200 truncate">{{ Auth::user()->name }}</div>
                <div class="text-[10px] text-slate-500">Sinh viên</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Đăng xuất"
                    class="text-slate-500 hover:text-red-400 transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- TOPBAR --}}
<header class="fixed top-0 left-0 lg:left-64 right-0 bg-white border-b border-slate-200 z-30 flex items-center justify-between px-4 lg:px-6" style="height:60px;">
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()"
            class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <div>
            <div class="text-sm font-semibold text-slate-800">
                @if(request()->routeIs('student.dashboard')) Tổng quan
                @elseif(request()->routeIs('student.profile.*')) Hồ sơ của tôi
                @else Student Portal
                @endif
            </div>
            <div class="text-[11px] text-slate-400 hidden sm:block">
                {{ now()->locale('vi')->isoFormat('dddd, D/M/YYYY') }}
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2" x-data="{ open: false }">
        <button @click="open = !open"
            class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-100 transition-colors">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="hidden sm:block text-left">
                <div class="text-xs font-semibold text-slate-700 leading-tight">{{ Auth::user()->name }}</div>
                <div class="text-[10px] text-slate-400 leading-tight">Sinh viên</div>
            </div>
            <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" @click.outside="open = false" x-transition
            class="absolute right-4 top-16 w-48 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden z-50">
            <div class="px-4 py-3 border-b border-slate-100">
                <div class="text-xs font-semibold text-slate-800">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</div>
            </div>
            <div class="py-1">
                <a href="{{ route('student.profile.show') }}"
                    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Hồ sơ của tôi
                </a>
            </div>
            <div class="border-t border-slate-100 py-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

{{-- CONTENT WRAPPER --}}
<div id="adminContent" class="lg:ml-64 min-h-screen bg-slate-50" style="padding-top:60px;">