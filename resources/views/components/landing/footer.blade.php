<?php
$ic = fn($d, $c = 'h-4 w-4') => '<svg class="' . $c . ' shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="' . $d . '"/></svg>';
$logo = '<span class="grid h-7 w-7 place-items-center rounded-lg bg-blue-600 text-white">' . $ic('M7 7h11l-3-3M17 17H6l3 3', 'h-4 w-4') . '</span>';
?>
<footer class="border-t border-slate-200 py-8">
    <div class="mx-auto flex max-w-6xl flex-col gap-6 px-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <a href="#" class="flex items-center gap-2 text-sm font-bold text-slate-900">{!! $logo !!} OmniSync Commerce</a>
            <p class="mt-2 max-w-sm text-[11px] leading-relaxed text-slate-500">
                © {{ date('Y') }} OmniSync Commerce Platform. All rights reserved. Built for Laravel marketplace automation.
            </p>
        </div>
        <nav class="flex flex-wrap gap-x-6 gap-y-2 text-[11px] font-medium text-slate-500">
            @foreach ($footLinks as $l)
                <a href="#" class="hover:text-blue-600">{{ $l }}</a>
            @endforeach
        </nav>
    </div>
</footer>
