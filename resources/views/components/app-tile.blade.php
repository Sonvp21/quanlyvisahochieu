@props(['item', 'color' => 'blue'])
@php
    // Giữ nguyên các chuỗi class đầy đủ để Tailwind nhận diện khi build
    $palette = [
        'blue'    => 'bg-blue-50 text-blue-600 ring-blue-100',
        'indigo'  => 'bg-indigo-50 text-indigo-600 ring-indigo-100',
        'emerald' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
        'amber'   => 'bg-amber-50 text-amber-600 ring-amber-100',
        'rose'    => 'bg-rose-50 text-rose-600 ring-rose-100',
        'sky'     => 'bg-sky-50 text-sky-600 ring-sky-100',
        'violet'  => 'bg-violet-50 text-violet-600 ring-violet-100',
        'slate'   => 'bg-slate-100 text-slate-600 ring-slate-200',
    ];
    $tone = $palette[$color] ?? $palette['blue'];
    // Điện thoại: mỗi ô là một thẻ trắng. Máy tính: ô phẳng nằm trong thẻ nhóm, hover mới nổi nền.
    $box = 'group relative flex w-full flex-col items-center gap-2 rounded-3xl bg-white px-1.5 pb-3 pt-3.5 text-center shadow-sm ring-1 ring-slate-100 transition active:scale-95 lg:bg-transparent lg:shadow-none lg:ring-0 lg:hover:bg-slate-50';
@endphp

@if ($item['post'])
    <form method="POST" action="{{ $item['href'] }}" class="contents">
        @csrf
        <button type="submit" class="{{ $box }}">
            <span class="flex h-14 w-14 items-center justify-center rounded-2xl ring-1 {{ $tone }}">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="{{ $item['icon'] }}"/></svg>
            </span>
            <span class="line-clamp-2 min-h-[2.1rem] text-[12px] font-medium leading-tight text-slate-700">{{ $item['label'] }}</span>
        </button>
    </form>
@else
    <a href="{{ $item['href'] }}" class="{{ $box }}">
        <span class="relative flex h-14 w-14 items-center justify-center rounded-2xl ring-1 transition group-hover:scale-105 {{ $tone }}">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="{{ $item['icon'] }}"/></svg>
            @if ($item['badge'])
                <span class="absolute -right-1.5 -top-1.5 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 px-1 text-[11px] font-semibold text-white ring-2 ring-white">{{ $item['badge'] > 99 ? '99+' : $item['badge'] }}</span>
            @endif
        </span>
        <span class="line-clamp-2 min-h-[2.1rem] text-[12px] font-medium leading-tight text-slate-700">{{ $item['label'] }}</span>
    </a>
@endif
