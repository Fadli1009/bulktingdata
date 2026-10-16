@props(['label', 'value' => null, 'icon' => 'file', 'accent' => 'indigo', 'hint' => null])

@php
    $accents = [
        'indigo' => 'from-blue-500 to-indigo-500 shadow-indigo-500/25',
        'emerald' => 'from-emerald-500 to-teal-500 shadow-emerald-500/25',
        'amber' => 'from-amber-500 to-orange-500 shadow-amber-500/25',
        'rose' => 'from-rose-500 to-pink-500 shadow-rose-500/25',
    ];
    $icons = [
        'upload' => 'M12 16V4M7 9l5-5 5 5M4 20h16',
        'check' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'alert' => 'M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z',
        'rows' => 'M4 6h16M4 12h16M4 18h16',
        'file' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-2xl bg-white p-5 shadow-card ring-1 ring-slate-200/60 transition-shadow duration-300 hover:shadow-lg hover:shadow-slate-900/5']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">
                {{ is_null($value) ? '—' : number_format($value, 0, ',', '.') }}
            </p>
            @if ($hint)
                <p class="mt-1 text-xs text-slate-400">{{ $hint }}</p>
            @endif
        </div>
        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br text-white shadow-lg {{ $accents[$accent] ?? $accents['indigo'] }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="{{ $icons[$icon] ?? $icons['file'] }}"/>
            </svg>
        </div>
    </div>
</div>
