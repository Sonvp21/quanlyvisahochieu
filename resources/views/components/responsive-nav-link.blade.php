@props(['active'])

@php
$classes = ($active ?? false)
    ? 'flex items-center gap-2.5 w-full ps-3 pe-4 py-2 border-l-2 border-blue-500 text-sm font-semibold text-blue-600 bg-blue-50 focus:outline-none transition'
    : 'flex items-center gap-2.5 w-full ps-3 pe-4 py-2 border-l-2 border-transparent text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 focus:outline-none transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>