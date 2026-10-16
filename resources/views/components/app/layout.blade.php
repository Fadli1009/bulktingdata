@props(['page' => 'home', 'title' => null, 'subtitle' => null])

@php
    $items = [
        ['home', 'Beranda', 'dashboard', 'M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10'],
        ['upload', 'Upload Excel', 'dashboard.upload', 'M12 16V4M7 9l5-5 5 5M4 20h16'],
        ['history', 'Riwayat', 'dashboard.orders', 'M12 8v4l3 2M3 12a9 9 0 1 0 9-9 9 9 0 0 0-7.7 4.4M3 4v4h4'],
        ['subscription', 'Paket Saya', 'dashboard.subscription', 'M21 8l-9-5-9 5v8l9 5 9-5zM3 8l9 5 9-5M12 13v8'],
        ['settings', 'Pengaturan', 'dashboard.settings', 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-2.9 1.2 2 2 0 1 1-4 0 1.7 1.7 0 0 0-2.9-1.2l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1A1.7 1.7 0 0 0 3 15a2 2 0 1 1 0-4 1.7 1.7 0 0 0 1.2-2.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1A1.7 1.7 0 0 0 10 4a2 2 0 1 1 4 0 1.7 1.7 0 0 0 2.9 1.2l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1A1.7 1.7 0 0 0 21 11a2 2 0 1 1 0 4'],
    ];

    $user = auth()->user();
    $displayName = $user?->name ?? 'Pengguna';
    $accountLabel = $user?->isBusiness() ? 'Akun Bisnis' : 'Akun Personal';
    $initials = collect(explode(' ', trim($displayName)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $pageTitle = $title ?? collect($items)->firstWhere(0, $page)[1] ?? 'Dashboard';
@endphp

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} — OmniSync Commerce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="h-full bg-canvas font-sans text-slate-600 antialiased"
      x-data="{ sidebar: false }"
      @keydown.escape.window="sidebar = false">

    <div class="pointer-events-none fixed inset-x-0 top-0 -z-10 h-80 bg-gradient-to-b from-indigo-100/40 via-canvas to-canvas"></div>

    <div x-cloak x-show="sidebar" x-transition.opacity class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm lg:hidden" @click="sidebar = false"></div>

    <aside class="fixed inset-y-0 left-0 z-40 flex w-[264px] -translate-x-full flex-col border-r border-slate-200/60 bg-white/85 backdrop-blur-xl transition-transform duration-300 ease-out lg:translate-x-0"
           :class="sidebar && 'translate-x-0'" aria-label="Menu utama">
        <a href="{{ route('dashboard') }}" class="flex h-16 items-center gap-3 px-5">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-lg shadow-indigo-600/25">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M7 7h11l-3-3M17 17H6l3 3"/></svg>
            </span>
            <span class="leading-tight">
                <span class="block text-sm font-bold text-slate-900">OmniSync</span>
                <span class="block text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">Workspace</span>
            </span>
        </a>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            @foreach ($items as [$key, $name, $routeName, $icon])
                @php $ready = Route::has($routeName); @endphp
                @if ($ready)
                    <a href="{{ route($routeName) }}" @class([
                        'group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200',
                        'bg-gradient-to-r from-blue-50 to-indigo-50/40 font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-600/10' => $page === $key,
                        'font-medium text-slate-500 hover:bg-slate-100/80 hover:text-slate-900' => $page !== $key,
                    ]) @if ($page === $key) aria-current="page" @endif>
                        @if ($page === $key)
                            <span class="absolute inset-y-2 left-0 w-1 rounded-full bg-gradient-to-b from-blue-600 to-indigo-600"></span>
                        @endif
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                        <span>{{ $name }}</span>
                    </a>
                @else
                    <span class="group flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400" title="Segera hadir">
                        <svg class="h-5 w-5 shrink-0 opacity-70" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                        <span>{{ $name }}</span>
                        <span class="ml-auto rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-400">Segera</span>
                    </span>
                @endif
            @endforeach
        </nav>

        <div class="border-t border-slate-200/60 p-3">
            <div class="flex items-center gap-3 rounded-xl bg-slate-50/70 px-3 py-2.5 ring-1 ring-inset ring-slate-200/60">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-gradient-to-br from-slate-700 to-slate-900 text-xs font-semibold text-white">{{ $initials ?: 'U' }}</span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-sm font-semibold text-slate-900">{{ $displayName }}</span>
                    <span class="block truncate text-[11px] font-medium text-slate-400">{{ $accountLabel }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-rose-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400/40">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 12H3m0 0l4-4m-4 4l4 4m6-10h3a2 2 0 012 2v10a2 2 0 01-2 2h-3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="lg:pl-[264px]">
        <header class="sticky top-0 z-20 border-b border-slate-200/60 bg-canvas/80 backdrop-blur-xl">
            <div class="mx-auto flex h-16 max-w-[1180px] items-center gap-3 px-4 sm:px-6 lg:px-8">
                <button type="button" class="grid h-9 w-9 place-items-center rounded-xl text-slate-600 transition-colors hover:bg-slate-100 lg:hidden" @click="sidebar = true" aria-label="Buka menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <nav class="flex min-w-0 items-center gap-2 text-sm" aria-label="Breadcrumb">
                    <span class="text-slate-400">Workspace</span>
                    <svg class="h-3.5 w-3.5 shrink-0 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    <span class="truncate font-semibold text-slate-900">{{ $pageTitle }}</span>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-[1180px] space-y-6 px-4 py-7 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="flex items-start gap-3 rounded-2xl border border-emerald-200/70 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-800 shadow-sm" role="status">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

</body>
</html>
