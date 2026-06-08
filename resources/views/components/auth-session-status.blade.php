@props(['status'])

@if($status)
<div {{ $attributes->merge(['class' => 'text-sm text-emerald-400']) }}>
    {{ $status }}
</div>
@endif