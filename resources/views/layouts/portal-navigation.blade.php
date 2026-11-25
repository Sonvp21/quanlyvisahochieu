{{-- Sidebar cho BGH, phòng chuyên trách, lãnh đạo đơn vị, cán bộ — được include từ layouts/app.blade.php --}}
@php
    $u = Auth::user();
    $approver = in_array($u->role, ['bgh', 'htqt', 'unit_manager'], true);
    $dir = request('direction');
    $f = request('filter');
    $onTrips = request()->routeIs('trips.*') && !request()->routeIs('trips.create');

    $groups = [];
    if (in_array($u->role, ['admin', 'bgh', 'htqt'], true)) {
        $groups['Tổng quan'] = [
            ['Tổng quan', route('overview'), request()->routeIs('overview'), 'M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z'],
            ['Thống kê', route('stats.index'), request()->routeIs('stats.*'), 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['Kế hoạch năm', route('plans.index'), request()->routeIs('plans.*'), 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ];
    }
    $groups += [
        'Đoàn ra & đoàn vào' => array_values(array_filter([
            ['Tất cả hồ sơ', route('trips.index'), $onTrips && !$dir && !$f, 'M4 6h16M4 12h16M4 18h16'],
            ['Đoàn ra', route('trips.index', ['direction' => 'out']), $onTrips && $dir === 'out' && !$f, 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
            ['Đoàn vào', route('trips.index', ['direction' => 'in']), $onTrips && $dir === 'in' && !$f, 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4'],
            $approver ? ['Chờ tôi duyệt', route('trips.index', ['filter' => 'to_approve']), $f === 'to_approve', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'] : null,
            ['Hồ sơ của tôi', route('trips.index', ['filter' => 'mine']), $f === 'mine', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ])),
        'Tạo mới' => [
            ['Đi công tác (đoàn ra)', route('trips.create'), request()->routeIs('trips.create') && $dir !== 'in', 'M12 4v16m8-8H4'],
            ['Tiếp đoàn vào', route('trips.create', ['direction' => 'in']), request()->routeIs('trips.create') && $dir === 'in', 'M12 4v16m8-8H4'],
        ],
    ];
@endphp

<div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"></div>

<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col
              transform transition-transform duration-200 lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:shrink-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <a href="{{ route('trips.index') }}" class="flex items-center gap-3 px-5 h-16 border-b border-slate-100 shrink-0">
        <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-700 to-indigo-600 text-white flex items-center justify-center shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        <span class="leading-tight">
            <span class="block text-sm font-bold text-slate-900">GIRC System</span>
            <span class="block text-[11px] text-slate-400">Hợp tác quốc tế &amp; Đối ngoại</span>
        </span>
    </a>

    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5">
        @foreach ($groups as $group => $items)
            <div>
                <p class="px-3 mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ $group }}</p>
                <div class="space-y-0.5">
                    @foreach ($items as [$label, $href, $active, $icon])
                        <a href="{{ $href }}" @click="sidebarOpen = false"
                           class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                                  {{ $active ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            @if ($active)<span class="absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r bg-blue-600"></span>@endif
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
            <span class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-semibold shrink-0">{{ strtoupper(mb_substr($u->name, 0, 1)) }}</span>
            <div class="min-w-0 flex-1 leading-tight">
                <p class="truncate text-sm font-medium text-slate-800">{{ $u->name }}</p>
                <p class="truncate text-[11px] text-slate-400">{{ \App\Models\User::ROLES[$u->role] ?? $u->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Đăng xuất" class="p-2 rounded-lg text-slate-400 hover:bg-white hover:text-red-600 transition">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
