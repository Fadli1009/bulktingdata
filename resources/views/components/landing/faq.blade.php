<?php
$ic = fn($d, $c = 'h-4 w-4') => '<svg class="' . $c . ' shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="' . $d . '"/></svg>';
$pill = 'inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-[11px] font-medium text-blue-700';
?>
<section id="faq" class="scroll-mt-14 py-20">
    <div class="mx-auto max-w-3xl px-5">
        <div class="text-center">
            <span class="rv {{ $pill }}">Pertanyaan yang Sering Diajukan</span>
            <h2 class="rv mt-4 text-2xl font-bold text-slate-900 sm:text-3xl" style="--d:.06s">Pusat Informasi & Penjelasan Modul</h2>
            <p class="rv mt-3 text-sm text-slate-500" style="--d:.12s">
                Semua hal yang perlu Anda ketahui mengenai integrasi file dan ketentuan paket.
            </p>
        </div>
        <div class="mt-10 space-y-3">
            @foreach ($faqs as $i => [$q, $a])
                <div class="rv faq rounded-lg border border-slate-200 bg-white" style="--d:{{ $i * 0.08 }}s">
                    <button class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left text-[13px] font-medium text-slate-800" aria-expanded="false">
                        {{ $q }}<span class="chev text-slate-400 transition-transform">{!! $ic('M6 9l6 6 6-6') !!}</span>
                    </button>
                    <div class="faq-a">
                        <div>
                            <p class="px-5 pb-4 text-[13px] leading-relaxed text-slate-500">{{ $a }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<script>
    (() => {
        document.querySelectorAll('.faq button').forEach(b => b.addEventListener('click', () => {
            const item = b.closest('.faq'),
                open = item.classList.toggle('open');
            b.setAttribute('aria-expanded', String(open));
        }));
    })();
</script>
