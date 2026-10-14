<?php
$ic = fn($d, $c = 'h-4 w-4') => '<svg class="' . $c . ' shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="' . $d . '"/></svg>';
$logo = '<span class="grid h-7 w-7 place-items-center rounded-lg bg-blue-600 text-white">' . $ic('M7 7h11l-3-3M17 17H6l3 3', 'h-4 w-4') . '</span>';
$loginUrl = url('/admin/login');
$registerUrl = url('/register');
?>
<header id="nav"
    class="fixed inset-x-0 top-0 z-50 border-b border-transparent bg-white/70 backdrop-blur transition-all">
    <div class="mx-auto flex h-14 max-w-6xl items-center justify-between px-5">
        <a href="#" class="flex items-center gap-2 text-sm font-bold text-slate-900">{!! $logo !!}
            OmniSync Commerce</a>
        <nav class="hidden items-center gap-8 text-xs font-medium text-slate-600 md:flex">
            <a href="#fitur" class="hover:text-blue-600">Fitur</a>
            <a href="#cara-kerja" class="hover:text-blue-600">Cara Kerja</a>
            <a href="#paket" class="hover:text-blue-600">Paket Harga</a>
            <a href="#faq" class="hover:text-blue-600">FAQ</a>
        </nav>
        <div class="hidden items-center gap-2 md:flex">
            {{-- <a href="{{ $loginUrl }}"
                class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">Masuk
                Portal Admin</a> --}}
            <a href="{{ $registerUrl }}"
                class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Daftar
                Sekarang</a>
        </div>
        <button id="burger" class="rounded-md p-2 md:hidden" aria-label="Menu" aria-expanded="false">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>
    </div>
    <div id="mmenu" class="hidden border-t border-slate-200 bg-white px-5 py-4 text-sm md:hidden">
        <div class="flex flex-col gap-3 font-medium">
            <a href="#fitur">Fitur</a>
            <a href="#cara-kerja">Cara Kerja</a>
            <a href="#paket">Paket Harga</a>
            <a href="#faq">FAQ</a>
            {{-- <a href="{{ $loginUrl }}" class="rounded-md border border-slate-300 px-3 py-2 text-center">Masuk Portal Admin</a> --}}
            <a href="{{ $registerUrl }}"
                class="rounded-md bg-blue-600 px-3 py-2 text-center font-semibold text-white">Daftar Sekarang</a>
        </div>
    </div>
</header>
<script>
    (() => {
        const nav = document.getElementById('nav');
        const onScroll = () => {
            nav.classList.toggle('border-slate-200', scrollY > 8);
            nav.classList.toggle('shadow-sm', scrollY > 8);
        };
        onScroll();
        addEventListener('scroll', onScroll, {
            passive: true
        });
        const burger = document.getElementById('burger'),
            mm = document.getElementById('mmenu');
        burger.addEventListener('click', () => {
            const o = mm.classList.toggle('hidden');
            burger.setAttribute('aria-expanded', String(!o));
        });
        mm.querySelectorAll('a').forEach(a => a.addEventListener('click', () => mm.classList.add('hidden')));
    })();
</script>
