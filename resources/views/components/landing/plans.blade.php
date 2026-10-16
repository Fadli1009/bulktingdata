<?php
$ic = fn($d, $c = 'h-4 w-4') => '<svg class="' . $c . ' shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="' . $d . '"/></svg>';
$pill = 'inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-[11px] font-medium text-blue-700';
$check = $ic('M5 13l4 4L19 7');
?>
<section id="paket" class="scroll-mt-14 bg-gradient-to-b from-indigo-50/60 to-white py-20">
    <div class="mx-auto max-w-6xl px-5">
        <div class="text-center">
            <span class="rv {{ $pill }}">Modul Paket Pembelian Terintegrasi</span>
            <h2 class="rv mx-auto mt-4 max-w-2xl text-2xl font-bold text-slate-900 sm:text-3xl" style="--d:.06s">
                Pilihan Paket Pembelian Fleksibel untuk Skala Bisnis Anda
            </h2>
            <p class="rv mx-auto mt-3 max-w-2xl text-sm text-slate-500" style="--d:.12s">
                Sesuai dengan modul Paket Pembelian pada sistem Admin kami. Skalakan kapasitas pencocokan SKU kapan saja seiring pertumbuhan omzet marketplace Anda.
            </p>
        </div>

        @if (empty($plans))
            <div class="mt-12 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="text-sm font-semibold text-slate-700">Belum ada paket tersedia</p>
                <p class="mt-1 text-xs text-slate-500">Paket pembelian akan muncul di sini setelah ditambahkan oleh admin.</p>
            </div>
        @else
            <div class="mt-12 grid items-stretch gap-5 lg:grid-cols-3">
                @foreach ($plans as $i => [$name, $tag, $desc, $price, $note, $head, $feat, $cta, $hot, $period, $slug])
                    <article class="rv relative flex flex-col rounded-xl p-6 transition hover:-translate-y-1 {{ $hot ? 'border-2 border-blue-600 bg-white shadow-xl shadow-blue-600/10 lg:-mt-3 lg:pb-9' : 'border border-slate-200 bg-white hover:shadow-lg' }}" style="--d:{{ $i * 0.12 }}s">
                        @if ($hot)
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-blue-600 px-3 py-1 text-[10px] font-bold text-white">PALING DIMINATI / TERLARIS</span>
                        @endif
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-slate-900">{{ $name }}</h3>
                            <span class="rounded {{ $hot ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }} px-2 py-0.5 text-[10px] font-semibold">{{ $tag }}</span>
                        </div>
                        <p class="mt-2 min-h-[48px] text-xs leading-relaxed text-slate-500">{{ $desc }}</p>
                        <p class="mt-5 text-slate-900">
                            <span class="text-xs text-slate-500">Rp</span>
                            <span class="text-3xl font-extrabold tracking-tight">{{ number_format($price, 0, ',', '.') }}</span>
                            <span class="text-xs text-slate-500">/{{ $period ?? 'bulan' }}</span>
                        </p>
                        <p class="mt-1 text-[11px] text-slate-500">{{ $note }}</p>
                        <p class="mt-6 text-[10px] font-semibold tracking-wide text-slate-400">{{ $head }}</p>
                        <ul class="mt-3 flex-1 space-y-2.5 text-xs">
                            @foreach ($feat as [$f, $on])
                                <li class="flex items-start gap-2 {{ $on ? 'text-slate-700' : 'text-slate-400 line-through' }}">
                                    <span class="{{ $on ? 'text-blue-600' : 'text-slate-300' }}">{!! $on ? $check : $ic('M6 12h12') !!}</span><span>{!! $f !!}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('checkout.show', $slug) }}" class="mt-7 block rounded-lg px-4 py-2.5 text-center text-sm font-semibold transition {{ $hot ? 'bg-blue-600 text-white hover:bg-blue-700' : 'border border-slate-300 text-slate-800 hover:bg-slate-50' }}">{{ $cta }}</a>
                    </article>
                @endforeach
            </div>
        @endif

        <div class="mt-8 grid gap-4 rounded-xl border border-slate-200 bg-white p-5 md:grid-cols-3">
            @foreach ($trust as $i => [$t, $d, $icon])
                <div class="rv flex gap-3" style="--d:{{ $i * 0.1 }}s">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-blue-50 text-blue-600">{!! $ic($icon) !!}</span>
                    <div>
                        <p class="text-xs font-semibold text-slate-900">{{ $t }}</p>
                        <p class="mt-0.5 text-[11px] leading-relaxed text-slate-500">{{ $d }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
