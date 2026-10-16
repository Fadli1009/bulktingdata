@props(['status' => null, 'label' => null])

@php
    $map = [
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'done' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'inactive' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'processing' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
        'expired' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
        'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'failed' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
    ];
    $labels = [
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
        'pending' => 'Pending',
        'processing' => 'Diproses',
        'done' => 'Selesai',
        'expired' => 'Kedaluwarsa',
        'cancelled' => 'Dibatalkan',
        'success' => 'Berhasil',
        'failed' => 'Gagal',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset '.($map[$status] ?? 'bg-slate-100 text-slate-600 ring-slate-500/20')]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
    {{ $label ?? $labels[$status] ?? $status }}
</span>
