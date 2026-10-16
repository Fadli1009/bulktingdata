@props(['title', 'description' => null, 'icon' => 'inbox'])

@php
    $icons = [
        'inbox' => 'M3 13h4l2 3h6l2-3h4M5 6h14l2 7v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5l2-7z',
        'upload' => 'M12 16V4M7 9l5-5 5 5M4 20h16',
        'box' => 'M21 8l-9-5-9 5v8l9 5 9-5zM3 8l9 5 9-5M12 13v8',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center px-6 py-14 text-center']) }}>
    <div class="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-slate-100 to-slate-50 text-slate-400 ring-1 ring-inset ring-slate-200/60">
        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="{{ $icons[$icon] ?? $icons['inbox'] }}"/>
        </svg>
    </div>
    <h3 class="mt-4 text-sm font-semibold text-slate-900">{{ $title }}</h3>
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-slate-500">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
