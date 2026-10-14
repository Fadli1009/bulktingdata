<?php
$ic = fn($d, $c = 'h-4 w-4') => '<svg class="' . $c . ' shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="' . $d . '"/></svg>';
$arrow = $ic('M5 12h14M13 6l6 6-6 6');
?>
<section class="px-5 pb-16">
    <div class="rv mx-auto max-w-6xl rounded-2xl bg-slate-900 px-6 py-12 sm:px-12">
        <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-[11px] font-medium text-blue-200">Setup Akun Instan 2 Menit</span>
        <h2 class="mt-4 max-w-md text-2xl font-bold leading-snug text-white sm:text-3xl">Siap Menghemat Waktu Operasional Marketplace Anda?</h2>
        <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-400">
            Hentikan pencocokan manual VLOOKUP yang rentan salah. Mulai uji coba gratis 14 hari dengan kuota matching lengkap tanpa perlu kartu kredit.
        </p>
        <form action="{{ $register }}" method="GET" class="mt-7 flex max-w-lg flex-col gap-2 sm:flex-row">
            <label for="cta-email" class="sr-only">Email bisnis</label>
            <input id="cta-email" type="email" name="email" required placeholder="Masukkan email bisnis atau kantor Anda." class="w-full rounded-lg border border-slate-700 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500">Daftar Sekarang {!! $arrow !!}</button>
        </form>
        <p class="mt-4 flex items-center gap-2 text-[11px] text-slate-400">
            {!! $ic('M5 13l4 4L19 7', 'h-3 w-3 text-emerald-400') !!} Uji coba mandiri tanpa kewajiban • Integrasi cepat ke Seller Center
        </p>
    </div>
</section>
