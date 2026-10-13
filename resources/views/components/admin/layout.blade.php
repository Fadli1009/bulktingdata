@props(['page' => 'dashboard', 'title' => null])

@php
    $groups = [
        'Utama' => [
            ['dashboard', 'Beranda', '/admin', 'M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10'],
        ],
        'Pelanggan' => [
            ['customer-create', 'Tambah Pelanggan', '/admin/customers/create', 'M12 5v14M5 12h14'],
            ['customers', 'Data Pelanggan', '/admin/customers', 'M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0'],
        ],
        'Transaksi' => [
            ['orders', 'Order', '/admin/orders', 'M6 6h15l-1.5 9h-12zM6 6L5 3H2M9 20h.01M18 20h.01'],
            ['packages', 'Paket Pembelian', '/admin/packages', 'M21 8l-9-5-9 5v8l9 5 9-5zM3 8l9 5 9-5M12 13v8'],
        ],
        'Konten' => [
            ['faqs', 'FAQ', '/admin/faqs', 'M9 9a3 3 0 115 2c-1 1-2 1.5-2 3M12 18h.01'],
        ],
        'Sistem' => [
            ['uploads', 'Upload Excel', '/admin/uploads', 'M12 16V4M7 9l5-5 5 5M4 20h16'],
            ['logs', 'Sistem Logs', '/admin/logs', 'M4 6h16M4 12h16M4 18h10'],
        ],
    ];
    $titles = collect($groups)->flatten(1)->mapWithKeys(fn ($m) => [$m[0] => $m[1]]);
    $pageTitle = $title ?? ($titles[$page] ?? 'Admin');
    $btn  = 'inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-b from-blue-600 to-blue-700 px-3.5 py-2 text-sm font-semibold text-white shadow-sm shadow-blue-600/20 transition-all duration-200 hover:from-blue-500 hover:to-blue-600 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30 disabled:cursor-not-allowed disabled:opacity-50';
    $btn2 = 'inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 transition-all duration-200 hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus-visible:ring-4 focus-visible:ring-slate-400/20 disabled:cursor-not-allowed disabled:opacity-50';
@endphp

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} — {{ config('app.name', 'Admin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="h-full bg-canvas font-sans text-slate-700 antialiased"
      x-data="{
        sidebar: false,
        confirm: { open: false, action: '', method: 'POST', message: '', label: 'Ya, lanjutkan' },
        ask(action, method, message, label) { this.confirm = { open: true, action, method, message, label: label || 'Ya, lanjutkan' } },
        copied: '',
        copy(t) { navigator.clipboard.writeText(t).then(() => { this.copied = t; setTimeout(() => this.copied = '', 1800) }) },
        busy: false
      }"
      @keydown.escape.window="sidebar = false; confirm.open = false">

    <div class="pointer-events-none fixed inset-x-0 top-0 -z-10 h-72 bg-gradient-to-b from-blue-100/50 via-canvas to-canvas"></div>

    <div x-cloak x-show="sidebar" x-transition.opacity class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm lg:hidden" @click="sidebar = false"></div>

    <aside class="fixed inset-y-0 left-0 z-40 flex w-[260px] -translate-x-full flex-col border-r border-slate-200/70 bg-white/80 backdrop-blur-xl transition-transform duration-300 ease-out lg:translate-x-0"
           :class="sidebar && 'translate-x-0'" aria-label="Menu admin">
        <div class="flex h-16 items-center gap-3 px-5">
            <div class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-sm font-bold text-white shadow-lg shadow-blue-600/30">
                {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
            </div>
            <div class="leading-tight">
                <div class="text-sm font-semibold text-slate-900">{{ config('app.name', 'Aplikasi') }}</div>
                <div class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">Panel Admin</div>
            </div>
        </div>

        <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-4">
            @foreach ($groups as $label => $items)
                <div>
                    <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">{{ $label }}</p>
                    <div class="space-y-1">
                        @foreach ($items as [$key, $name, $url, $icon])
                            <a href="{{ url($url) }}" @class([
                                'group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200',
                                'bg-blue-50 font-semibold text-blue-700 ring-1 ring-inset ring-blue-600/10' => $page === $key,
                                'font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900' => $page !== $key,
                            ]) @if ($page === $key) aria-current="page" @endif>
                                @if ($page === $key)
                                    <span class="absolute inset-y-2 left-0 w-1 rounded-full bg-gradient-to-b from-blue-600 to-indigo-600"></span>
                                @endif
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                                <span>{{ $name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="border-t border-slate-200/70 p-3">
            <div class="flex items-center gap-3 rounded-xl px-2 py-2">
                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-slate-900 text-xs font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-semibold text-slate-900">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="truncate text-xs text-slate-400">{{ auth()->user()->email ?? 'admin@local' }}</div>
                </div>
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 12H3m0 0l4-4m-4 4l4 4m6-10h3a2 2 0 012 2v10a2 2 0 01-2 2h-3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="lg:pl-[260px]">
        <header class="sticky top-0 z-20 border-b border-slate-200/70 bg-canvas/80 backdrop-blur-xl">
            <div class="mx-auto flex h-16 max-w-[1400px] items-center gap-3 px-4 sm:px-6 lg:px-8">
                <button type="button" class="grid h-9 w-9 place-items-center rounded-xl text-slate-600 transition-colors hover:bg-slate-100 lg:hidden" @click="sidebar = true" aria-label="Buka menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <nav class="flex min-w-0 items-center gap-2 text-sm" aria-label="Breadcrumb">
                    <span class="text-slate-400">Admin</span>
                    <svg class="h-3.5 w-3.5 shrink-0 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    <span class="truncate font-semibold text-slate-900">{{ $pageTitle }}</span>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-[1400px] space-y-6 px-4 py-6 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="flex items-start gap-3 rounded-2xl border border-emerald-200/70 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-800 shadow-sm" role="status">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="flex items-start gap-3 rounded-2xl border border-rose-200/70 bg-rose-50/80 px-4 py-3 text-sm text-rose-800 shadow-sm" role="alert">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="flex items-start gap-3 rounded-2xl border border-rose-200/70 bg-rose-50/80 px-4 py-3 text-sm text-rose-800 shadow-sm" role="alert">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    <div>
                        <p class="font-semibold">Data belum bisa disimpan. Periksa isian berikut:</p>
                        <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <div x-cloak x-show="confirm.open" x-transition.opacity class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm" @click.self="confirm.open = false" role="dialog" aria-modal="true">
        <form method="POST" :action="confirm.action" x-transition class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-pop ring-1 ring-slate-200/70">
            @csrf
            <input type="hidden" name="_method" :value="confirm.method">
            <div class="grid h-11 w-11 place-items-center rounded-2xl bg-rose-50 text-rose-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </div>
            <h2 class="mt-4 text-lg font-semibold text-slate-900">Konfirmasi</h2>
            <p class="mt-1 text-sm text-slate-500" x-text="confirm.message"></p>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" class="{{ $btn2 }}" @click="confirm.open = false">Batal</button>
                <button type="submit" class="{{ $btn }}" x-text="confirm.label"></button>
            </div>
        </form>
    </div>

</body>
</html>
