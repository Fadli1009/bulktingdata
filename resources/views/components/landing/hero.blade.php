<?php
$ic = fn($d, $c = 'h-4 w-4') => '<svg class="' . $c . ' shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="' . $d . '"/></svg>';
$arrow = $ic('M5 12h14M13 6l6 6-6 6');
$check = $ic('M5 13l4 4L19 7');
$pill = 'inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-[11px] font-medium text-blue-700';
?>
<section class="bg-gradient-to-b from-indigo-50/70 to-white pt-28 pb-20">
    <div class="mx-auto grid max-w-6xl items-center gap-12 px-5 lg:grid-cols-2">
        <div>
            <span class="rv {{ $pill }} !bg-white !text-slate-600">
                <span class="rounded bg-blue-600 px-1.5 py-0.5 text-[10px] font-bold text-white">Laravel 11</span>
                Worker Engine Ready · Rekonsiliasi 18.000+ Baris &lt; 3 Detik
            </span>
            <h1 class="rv mt-5 text-3xl font-extrabold leading-[1.15] tracking-tight text-slate-900 sm:text-4xl lg:text-[40px]" style="--d:.08s">
                Otomasi Katalog Marketplace & Eksekusi Campaign Excel dalam Hitungan Detik
            </h1>
            <p class="rv mt-5 max-w-lg text-sm leading-relaxed text-slate-600" style="--d:.16s">
                Sinkronisasi katalog Tokopedia & Shopee secara presisi. Eksekusi pencocokan PN bundle otomatis
                <code class="rounded bg-blue-50 px-1.5 py-0.5 font-mono text-xs text-blue-700">{PN}.{VAR}.{INDEX}</code>
                dengan pemrosesan queue Redis performa tinggi.
            </p>
            <div class="rv mt-7 flex flex-col gap-3 sm:flex-row" style="--d:.24s">
                <a href="#paket" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:-translate-y-0.5 hover:bg-blue-700">
                    Pilih Paket Langganan {!! $arrow !!}
                </a>
                <a href="#cara-kerja" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-800 transition hover:bg-slate-50">
                    {!! $ic('M5 4h14v12H5zM9 20h6M12 16v4') !!} Jadwalkan Demo Operasional
                </a>
            </div>
            <div class="rv mt-9 flex flex-wrap gap-x-6 gap-y-2 text-[11px] text-slate-500" style="--d:.32s">
                @foreach (['Dipercaya oleh 1.400+ seller enterprise', 'Memproses 2.800+ order/hari', 'Enkripsi AES-256'] as $t)
                    <span class="flex items-center gap-1.5 text-blue-600">{!! $check !!}<span class="text-slate-500">{{ $t }}</span></span>
                @endforeach
            </div>
        </div>
        <div class="rv rv-r overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl shadow-slate-900/5" style="--d:.2s">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50 px-4 py-2.5">
                <div class="flex items-center gap-2">
                    <div class="flex gap-1">
                        <i class="h-2 w-2 rounded-full bg-slate-300"></i>
                        <i class="h-2 w-2 rounded-full bg-slate-300"></i>
                        <i class="h-2 w-2 rounded-full bg-slate-300"></i>
                    </div>
                    <span class="font-mono text-[11px] text-slate-500">omnisync-worker.log</span>
                </div>
                <span class="rounded bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">STATUS: STACKED</span>
            </div>
            <div class="space-y-3 p-4">
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-lg border border-slate-200 p-3">
                        <p class="text-[10px] text-slate-500">Auto-Matched SKU</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">
                            <span data-count="98.4" data-dec="1">98.4</span>% <span class="text-[10px] font-semibold text-emerald-600">↑ 3.2%</span>
                        </p>
                        <p class="text-[10px] text-slate-500">4.920 dari 5.000 SKU</p>
                        <div class="mt-2 h-1 rounded bg-slate-100">
                            <div class="bar h-1 rounded bg-blue-600" style="--w:98.4%"></div>
                        </div>
                    </div>
                    <div class="rounded-lg border border-slate-200 p-3">
                        <p class="text-[10px] text-slate-500">Campaign Excel</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">
                            <span data-count="42" data-dec="0">42</span> Files <span class="text-[10px] font-semibold text-emerald-600">Ready</span>
                        </p>
                        <p class="text-[10px] text-slate-500">Tokopedia & Shopee Format</p>
                    </div>
                </div>
                <div class="rounded-lg bg-slate-900 p-4 font-mono text-[11px] leading-6 text-slate-300">
                    <div class="mb-2 flex justify-between text-[10px] text-slate-500">
                        <span>SKU Schema Resolution Engine</span><span>0.042ms</span>
                    </div>
                    <p class="text-emerald-400">[200 OK/FIX]</p>
                    <p>IN: <span class="text-amber-300">"TXP-A08-07-BLK-01"</span></p>
                    <p>&gt; SOLD: <span class="text-sky-300">{BRAND}.{MODEL}.{COLOR}.{ID}</span></p>
                    <p class="caret">MATCH: <span class="text-emerald-400">SKU-7739-OB (Master Warehouse X) [VALID]</span></p>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-500">
                    {!! $ic('M5 13l4 4L19 7', 'h-3 w-3 text-emerald-500') !!} Validasi Entri Sinkronisasi
                    <span class="font-mono font-semibold text-blue-600">Laravel 11 Worker</span>
                </div>
            </div>
        </div>
    </div>
</section>
