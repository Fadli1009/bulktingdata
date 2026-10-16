@props(['value' => 0, 'max' => null, 'tone' => 'indigo'])

@php
    $pct = ($max !== null && $max > 0) ? min(100, round(($value / $max) * 100)) : ($max === null ? 100 : 0);
    $tones = [
        'indigo' => 'from-blue-500 to-indigo-500',
        'emerald' => 'from-emerald-500 to-teal-500',
        'amber' => 'from-amber-500 to-orange-500',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'h-2 w-full overflow-hidden rounded-full bg-slate-100']) }}
     role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
    <div class="h-full rounded-full bg-gradient-to-r {{ $tones[$tone] ?? $tones['indigo'] }} transition-[width] duration-700 ease-out" style="width: {{ $pct }}%"></div>
</div>
