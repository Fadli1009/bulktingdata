<?php
$ic = fn($d, $c = 'h-4 w-4') => '<svg class="' . $c . ' shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="' . $d . '"/></svg>';
$pill = 'inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-[11px] font-medium text-blue-700';
?>
<section id="fitur" class="scroll-mt-14 py-20">
    <div class="mx-auto max-w-6xl px-5">
        <div class="text-center">
            <span class="rv {{ $pill }}">Fitur Admin Cockpit</span>
            <h2 class="rv mt-4 text-2xl font-bold text-slate-900 sm:text-3xl" style="--d:.06s">Presisi Maksimal untuk Operasional Multi-Channel</h2>
            <p class="rv mx-auto mt-3 max-w-xl text-sm text-slate-500" style="--d:.12s">
                Dibangun dengan arsitektur enterprise Laravel untuk menangani ribuan baris data tanpa bottleneck.
            </p>
        </div>
        <div class="mt-12 grid gap-4 md:grid-cols-3">
            <article class="rv rv-l rounded-xl border border-slate-200 p-6 md:col-span-2">
                <p class="text-[11px] font-semibold text-blue-600">Engine Bundle Dinamis</p>
                <h3 class="mt-2 text-lg font-bold text-slate-900">Pencocokan Aturan Part Number <code class="font-mono text-base text-blue-700">{PN}.{VAR}.{INDEX}</code></h3>
                <p class="mt-2 text-[13px] leading-relaxed text-slate-500">
                    Otomatis memecah pola bundling kombo dan varian warna/ukuran menjadi line-item terpisah untuk verifikasi ketersediaan stok di gudang utama.
                </p>
                <div class="mt-5 rounded-lg border border-slate-200 bg-slate-50 p-4 font-mono text-[11px]">
                    <p class="text-[10px] text-slate-400">// Endpoint Transformasi Bundling</p>
                    <p class="mt-1 overflow-x-auto whitespace-nowrap text-slate-700">
                        PROD-BUNDLE-14 <span class="text-blue-600">→</span> [PROD-01 (1x), PROD-02 (1x), PROD-03 (2x)] <span class="font-semibold text-emerald-600">✓ Auto-Resolved</span>
                    </p>
                </div>
            </article>
            <article class="rv rv-r rounded-xl border border-slate-200 p-6" style="--d:.1s">
                <p class="text-[11px] font-semibold text-blue-600">Smart Injection</p>
                <h3 class="mt-2 text-lg font-bold text-slate-900">Auto Mapping Kolom Excel</h3>
                <p class="mt-2 text-[13px] leading-relaxed text-slate-500">
                    Header kolom Tokopedia & Shopee terdeteksi otomatis tanpa perlu setup manual.
                </p>
                <div class="mt-5 grid place-items-center rounded-lg border border-dashed border-slate-300 bg-slate-50 py-5 text-center">
                    <span class="text-blue-600">{!! $ic('M7 3h7l5 5v13H7zM14 3v5h5', 'h-7 w-7') !!}</span>
                    <p class="mt-2 text-[10px] text-slate-500">Seller File Campaign.xlsx<br>terdeteksi otomatis</p>
                </div>
            </article>
            <article class="rv rv-l rounded-xl border border-slate-200 p-6">
                <p class="text-[11px] font-semibold text-blue-600">Laravel Horizon</p>
                <h3 class="mt-2 text-lg font-bold text-slate-900">Worker Berkecepatan Tinggi</h3>
                <p class="mt-2 text-[13px] leading-relaxed text-slate-500">
                    Proses puluhan ribu data di latar belakang tanpa membuat browser freeze atau timeout.
                </p>
                <div class="mt-5 flex items-end justify-between text-xs">
                    <span class="text-slate-500">Average Throughput</span><span class="font-mono font-bold text-slate-900">1.800 rows/sec</span>
                </div>
                <div class="mt-2 h-1.5 rounded bg-slate-100">
                    <div class="bar h-1.5 rounded bg-blue-600" style="--w:82%"></div>
                </div>
            </article>
            <article class="rv rv-r rounded-xl border border-slate-200 p-6 md:col-span-2" style="--d:.1s">
                <p class="text-[11px] font-semibold text-blue-600">System Telemetry Audit</p>
                <h3 class="mt-2 text-lg font-bold text-slate-900">Audit Trail SKU <code class="font-mono text-base text-blue-700">NOT_FOUND</code> & Anomali Harga</h3>
                <p class="mt-2 text-[13px] leading-relaxed text-slate-500">
                    Laporan komprehensif mengidentifikasi setiap SKU yang tidak terdeteksi sebelum dibawa ke tim marketing, mengurangi kerugian akibat salah input (pricing error).
                </p>
                <div class="mt-5 flex flex-wrap gap-2 text-[11px] font-medium">
                    <span class="rounded-md bg-emerald-50 px-2.5 py-1 text-emerald-700">0 False Positive</span>
                    <span class="rounded-md bg-amber-50 px-2.5 py-1 text-amber-700">Salary Block Check OK</span>
                    <span class="rounded-md bg-blue-50 px-2.5 py-1 text-blue-700">FBS Checksum Verified</span>
                </div>
            </article>
        </div>
    </div>
</section>
