<x-app-layout>
    {{-- Đầu trang tràn ngang: nền gradient, lời chào, thẻ người dùng --}}
    <section class="bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-600 pb-16 pt-5 text-white lg:pb-20 lg:pt-10"
             style="border-bottom-left-radius: 2rem; border-bottom-right-radius: 2rem;">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-3 lg:hidden">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/25">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ config('menu.icons.globe') }}"/></svg>
                    </span>
                    <span class="leading-tight">
                        <span class="block text-sm font-semibold">GIRC System</span>
                        <span class="block text-[11px] text-blue-100">Hợp tác quốc tế &amp; Đối ngoại</span>
                    </span>
                </div>
                <div class="relative shrink-0" x-data="{ userMenu: false }" @keydown.escape.window="userMenu = false">
                    <button type="button" @click="userMenu = !userMenu" aria-label="Tài khoản" class="flex h-9 items-center gap-0.5 rounded-full bg-white/20 pl-3 pr-2 text-sm font-semibold ring-1 ring-white/30 transition active:scale-90">{{ strtoupper(mb_substr($user->name, 0, 1)) }}<svg class="h-4 w-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"/></svg></button>
                    <div x-show="userMenu" x-cloak x-transition.opacity @click.outside="userMenu = false"
                         class="absolute right-0 top-full z-50 mt-2 w-64 overflow-hidden rounded-2xl bg-white text-left shadow-xl ring-1 ring-slate-200">
                        <div class="border-b border-slate-100 px-4 py-3">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="truncate text-xs text-slate-400">{{ $user->email }} · {{ \App\Models\User::ROLES[$user->role] ?? $user->role }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Thông tin cá nhân
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-6 lg:mt-0">
                <p class="text-sm text-blue-100 lg:text-base">{{ $greeting }},</p>
                <h1 class="mt-0.5 text-2xl font-bold leading-tight sm:text-3xl lg:text-4xl">{{ $user->name }}</h1>
                <p class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-medium ring-1 ring-white/25 lg:mt-3 lg:text-sm">
                    {{ \App\Models\User::ROLES[$user->role] ?? $user->role }}{{ $user->unit?->name ? ' · ' . $user->unit->name : '' }}
                </p>
            </div>
        </div>
    </section>

    <div class="mx-auto -mt-10 max-w-6xl space-y-6 px-4 sm:px-6 lg:-mt-12 lg:px-8">
        {{-- Thẻ số liệu nổi lên trên nền gradient --}}
        @if ($counters)
            <div class="grid gap-3 rounded-3xl bg-white p-4 shadow-lg shadow-blue-900/10 ring-1 ring-slate-100 lg:p-5" style="grid-template-columns: repeat({{ count($counters) }}, minmax(0, 1fr));">
                @foreach ($counters as $c)
                    <div class="text-center {{ !$loop->first ? 'border-l border-slate-100' : '' }}">
                        <p class="text-2xl font-bold lg:text-3xl {{ $c['alert'] && $c['value'] ? 'text-red-600' : 'text-slate-900' }}">{{ $c['value'] }}</p>
                        <p class="mt-0.5 text-[12px] font-medium text-slate-500 lg:text-sm">{{ $c['label'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Nhóm chức năng: điện thoại xếp dọc; máy tính là các thẻ trắng 2 cột (nhóm dài chiếm cả hàng) --}}
        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2 lg:gap-5">
            @foreach ($groups as $g)
                @php $wide = count($g['items']) > 4; @endphp
                <section class="lg:rounded-3xl lg:bg-white lg:p-6 lg:shadow-sm lg:ring-1 lg:ring-slate-100 {{ $wide ? 'lg:col-span-2' : '' }}">
                    <h2 class="mb-3 px-1 text-[13px] font-semibold uppercase tracking-wide text-slate-500 lg:mb-4">{{ $g['title'] }}</h2>
                    <div class="grid grid-cols-4 gap-2.5 sm:grid-cols-5 sm:gap-3 {{ $wide ? 'lg:grid-cols-8' : 'lg:grid-cols-4' }} lg:gap-2">
                        @foreach ($g['items'] as $item)
                            <x-app-tile :item="$item" :color="$g['color']" />
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</x-app-layout>
