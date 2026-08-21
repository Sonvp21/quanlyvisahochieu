@php
    $alerts = [
        'success' => ['bg' => 'bg-white', 'border' => 'border-emerald-200', 'text' => 'text-slate-700', 'icon' => 'text-emerald-500', 'iconBg' => 'bg-emerald-100', 'bar' => 'bg-emerald-500'],
        'error'   => ['bg' => 'bg-white', 'border' => 'border-red-200',     'text' => 'text-slate-700', 'icon' => 'text-red-500',     'iconBg' => 'bg-red-100',     'bar' => 'bg-red-500'],
        'warning' => ['bg' => 'bg-white', 'border' => 'border-amber-200',   'text' => 'text-slate-700', 'icon' => 'text-amber-500',   'iconBg' => 'bg-amber-100',   'bar' => 'bg-amber-500'],
        'info'    => ['bg' => 'bg-white', 'border' => 'border-blue-200',    'text' => 'text-slate-700', 'icon' => 'text-blue-500',    'iconBg' => 'bg-blue-100',    'bar' => 'bg-blue-500'],
    ];
    $hasAny = collect(array_keys($alerts))->contains(fn($t) => session($t));
@endphp

@if($hasAny)
<div class="fixed top-4 right-4 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
    @foreach($alerts as $type => $c)
        @if(session($type))
        <div
            x-data="{ show: false, progress: 100 }"
            x-init="
                requestAnimationFrame(() => show = true);
                const timer = setInterval(() => {
                    progress -= 1.5;
                    if (progress <= 0) { clearInterval(timer); show = false; }
                }, 60);
                $watch('show', v => { if (!v) clearInterval(timer); });
            "
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-6 scale-95"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-x-6 scale-95"
            class="pointer-events-auto relative overflow-hidden {{ $c['bg'] }} border {{ $c['border'] }} rounded-2xl shadow-xl shadow-slate-900/10"
        >
            <div class="flex items-start gap-3 px-4 py-3.5">
                {{-- ICON --}}
                <div class="flex-shrink-0 w-8 h-8 {{ $c['iconBg'] }} rounded-full flex items-center justify-center">
                    @if($type === 'success')
                    <svg class="w-4 h-4 {{ $c['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    @elseif($type === 'error')
                    <svg class="w-4 h-4 {{ $c['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    @elseif($type === 'warning')
                    <svg class="w-4 h-4 {{ $c['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    @else
                    <svg class="w-4 h-4 {{ $c['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    @endif
                </div>

                {{-- MESSAGE --}}
                <div class="flex-1 min-w-0 pt-1">
                    <p class="text-sm font-semibold {{ $c['text'] }} leading-snug">
                        @switch($type)
                            @case('success') Thành công @break
                            @case('error') Có lỗi xảy ra @break
                            @case('warning') Chú ý @break
                            @default Thông báo
                        @endswitch
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ session($type) }}</p>
                </div>

                {{-- CLOSE --}}
                <button @click="show = false" class="flex-shrink-0 text-slate-300 hover:text-slate-500 transition-colors mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- PROGRESS BAR --}}
            <div class="h-0.5 bg-slate-100">
                <div class="h-full {{ $c['bar'] }} transition-all duration-75 ease-linear" :style="`width: ${progress}%`"></div>
            </div>
        </div>
        @endif
    @endforeach
</div>
@endif