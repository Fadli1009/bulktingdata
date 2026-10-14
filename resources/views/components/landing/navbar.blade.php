<?php
$ic = fn($d, $c = 'h-4 w-4') => '<svg class="' . $c . ' shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="' . $d . '"/></svg>';
$logo = '<span class="grid h-7 w-7 place-items-center rounded-lg bg-blue-600 text-white">' . $ic('M7 7h11l-3-3M17 17H6l3 3', 'h-4 w-4') . '</span>';
?>
<header id="nav"
    class="fixed inset-x-0 top-0 z-50 border-b border-transparent bg-white/70 backdrop-blur transition-all">
    <div class="mx-auto flex h-14 max-w-6xl items-center justify-between gap-4 px-5">
        <a href="/" class="flex items-center gap-2 text-sm font-bold text-slate-900">{!! $logo !!}
            <span class="hidden sm:inline">OmniSync Commerce</span>
        </a>
        <nav class="hidden items-center gap-8 text-xs font-medium text-slate-600 md:flex">
            <a href="#fitur" class="hover:text-blue-600">Fitur</a>
            <a href="#cara-kerja" class="hover:text-blue-600">Cara Kerja</a>
            <a href="#paket" class="hover:text-blue-600">Paket Harga</a>
            <a href="#faq" class="hover:text-blue-600">FAQ</a>
        </nav>

        @auth
            @php
                $words = explode(' ', auth()->user()->name);
                $displayName = count($words) > 1 ? $words[1] : $words[0];
            @endphp
            <div class="hidden items-center md:flex" x-data="{ open: false }">
                <div class="relative">
                    <button @click="open = !open" type="button"
                        class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs transition hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <span class="text-slate-600">Hallo,</span>
                        <span class="font-semibold text-slate-900"
                            title="{{ auth()->user()->name }}">{{ $displayName }}</span>
                        <svg class="h-3 w-3 transition-transform" :class="open && 'rotate-180'" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-lg border border-slate-200 bg-white shadow-lg"
                        style="display: none;">
                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-slate-700 transition hover:bg-slate-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="hidden items-center gap-2 md:flex">
                <a href="{{ route('login') }}"
                    class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">Masuk</a>
                <a href="{{ route('register') }}"
                    class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Daftar
                    Sekarang</a>
            </div>
        @endauth

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
            @auth
                <div class="mt-2 rounded-lg border border-slate-200 bg-slate-50 p-3">
                    <p class="text-xs text-slate-600">Hallo,</p>
                    <p class="mt-0.5 truncate font-semibold text-slate-900" title="{{ auth()->user()->name }}">
                        {{ auth()->user()->name }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-center text-slate-700">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-md border border-slate-300 px-3 py-2 text-center">Masuk</a>
                <a href="{{ route('register') }}"
                    class="rounded-md bg-blue-600 px-3 py-2 text-center font-semibold text-white">Daftar Sekarang</a>
            @endauth
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
