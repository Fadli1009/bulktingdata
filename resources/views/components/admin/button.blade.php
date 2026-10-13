@props(['variant' => 'primary', 'size' => 'md', 'href' => null])

@php
    $sizes = [
        'sm' => 'gap-1.5 rounded-lg px-2.5 py-1.5 text-xs',
        'md' => 'gap-1.5 rounded-xl px-3.5 py-2 text-sm',
    ];
    $variants = [
        'primary' => 'bg-gradient-to-b from-blue-600 to-blue-700 font-semibold text-white shadow-sm shadow-blue-600/20 hover:from-blue-500 hover:to-blue-600 focus-visible:ring-blue-500/30',
        'secondary' => 'border border-slate-200 bg-white font-medium text-slate-700 hover:border-slate-300 hover:bg-slate-50 focus-visible:ring-slate-400/20',
        'ghost' => 'font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus-visible:ring-slate-400/20',
        'danger' => 'border border-rose-200 bg-white font-medium text-rose-600 hover:bg-rose-50 focus-visible:ring-rose-400/20',
    ];
    $classes = 'inline-flex items-center justify-center whitespace-nowrap transition-all duration-200 focus:outline-none focus-visible:ring-4 disabled:cursor-not-allowed disabled:opacity-50 '
        .($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
