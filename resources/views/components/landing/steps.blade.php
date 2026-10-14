<?php
$ic = fn($d, $c = 'h-4 w-4') => '<svg class="' . $c . ' shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="' . $d . '"/></svg>';
$pill = 'inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-[11px] font-medium text-blue-700';
?>
<section id="cara-kerja" class="scroll-mt-14 py-20">
    <div class="mx-auto max-w-6xl px-5">
        <div class="text-center">
            <span class="rv {{ $pill }}">Alur Operasional Berkelanjutan</span>
            <h2 class="rv mt-4 text-2xl font-bold text-slate-900 sm:text-3xl" style="--d:.06s">Bagaimana Cara Kerjanya? Tiga Langkah Tuntas</h2>
            <p class="rv mx-auto mt-3 max-w-xl text-sm text-slate-500" style="--d:.12s">
                Dari input raw data export marketplace hingga file siap submit Flash Sale dan Mega Campaign.
            </p>
        </div>
        <div class="mt-12 grid gap-5 md:grid-cols-3">
            @foreach ($steps as $i => [$n, $t, $d, $f, $icon])
                <article class="rv group flex flex-col rounded-xl border p-6 transition hover:-translate-y-1 hover:shadow-lg {{ $i === 1 ? 'border-blue-200 bg-blue-50/50' : 'border-slate-200 bg-white' }}" style="--d:{{ $i * 0.12 }}s">
                    <span class="grid h-8 w-8 place-items-center rounded-lg text-sm font-bold {{ $i === 1 ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $n }}</span>
                    <h3 class="mt-4 text-base font-semibold text-slate-900">{{ $t }}</h3>
                    <p class="mt-2 flex-1 text-[13px] leading-relaxed text-slate-500">{{ $d }}</p>
                    <p class="mt-5 flex items-center gap-2 border-t border-slate-200/70 pt-4 text-[11px] text-slate-500">
                        {!! $ic($icon, 'h-3.5 w-3.5 text-blue-600') !!} {{ $f }}
                    </p>
                </article>
            @endforeach
        </div>
    </div>
</section>
