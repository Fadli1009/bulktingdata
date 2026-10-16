@props(['variant' => 'primary', 'size' => 'md', 'href' => null])

@php
    $sizes = [
        'sm' => 'gap-1.5 rounded-lg px-3 py-1.5 text-xs',
        'md' => 'gap-2 rounded-xl px-4 py-2.5 text-sm',
    ];
    $variants = [
        'primary' => 'bg-gradient-to-b from-blue-600 to-indigo-600 font-semibold text-white shadow-sm shadow-indigo-600/25 hover:from-blue-500 hover:to-indigo-500 focus-visible:ring-indigo-500/30',
        'secondary' => 'border border-slate-200 bg-white font-medium text-slate-700 hover:border-slate-300 hover:bg-slate-50 focus-visible:ring-slate-400/20',
        'ghost' => 'font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus-visible:ring-slate-400/20',
    ];
    $classes = 'inline-flex items-center justify-center whitespace-nowrap transition-all duration-200 focus:outline-none focus-visible:ring-4 disabled:cursor-not-allowed disabled:opacity-50 '
        .($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
