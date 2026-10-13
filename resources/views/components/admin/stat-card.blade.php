@props(['label', 'value' => null, 'icon' => 'file', 'accent' => 'blue'])

@php
    $accents = [
        'blue' => 'from-blue-500 to-indigo-500 shadow-blue-500/30',
        'emerald' => 'from-emerald-500 to-teal-500 shadow-emerald-500/30',
        'amber' => 'from-amber-500 to-orange-500 shadow-amber-500/30',
        'violet' => 'from-violet-500 to-purple-500 shadow-violet-500/30',
    ];
    $icons = [
        'users' => 'M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0',
        'receipt' => 'M6 6h15l-1.5 9h-12zM6 6L5 3H2M9 20h.01M18 20h.01',
        'box' => 'M21 8l-9-5-9 5v8l9 5 9-5zM3 8l9 5 9-5M12 13v8',
        'file' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z',
    ];
@endphp

<div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-card ring-1 ring-slate-200/70 transition-shadow duration-300 hover:shadow-lg hover:shadow-slate-900/5">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">
                {{ is_null($value) ? '—' : number_format($value, 0, ',', '.') }}
            </p>
        </div>
        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br text-white shadow-lg {{ $accents[$accent] ?? $accents['blue'] }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="{{ $icons[$icon] ?? $icons['file'] }}"/>
            </svg>
        </div>
    </div>
</div>
