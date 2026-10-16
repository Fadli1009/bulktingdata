@props(['padding' => null])

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white shadow-card ring-1 ring-slate-200/60 '.($padding ?? '')]) }}>
    {{ $slot }}
</div>
