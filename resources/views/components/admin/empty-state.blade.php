@props(['title', 'description' => null, 'icon' => null])

@php
    $icons = [
        'inbox' => 'M3 13h4l2 3h6l2-3h4M5 6h14l2 7v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5l2-7z',
        'users' => 'M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0',
        'file' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z',
        'search' => 'M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center px-6 py-16 text-center']) }}>
    <div class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
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
