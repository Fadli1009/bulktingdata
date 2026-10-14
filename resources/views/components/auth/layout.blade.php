<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'OmniSync Commerce — Autentikasi' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>
<body class="bg-white font-sans text-slate-700 antialiased">
    <div class="pointer-events-none fixed inset-x-0 top-0 -z-10 h-96 bg-gradient-to-b from-indigo-50/70 to-white"></div>

    <div class="min-h-screen flex flex-col lg:flex-row">
        <div class="hidden lg:flex lg:w-1/2 flex-col items-center justify-center bg-gradient-to-b from-indigo-50 to-white px-8">
            <div class="max-w-sm text-center">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-blue-600 text-white mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h11l-3-3M17 17H6l3 3" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">OmniSync Commerce</h2>
                <p class="text-sm leading-relaxed text-slate-600 mb-8">
                    Otomasi katalog marketplace & eksekusi campaign Excel dalam hitungan detik. Sinkronisasi Tokopedia & Shopee dengan presisi tinggi.
                </p>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-xs text-slate-600">Pemrosesan presisi 99.6% tanpa jeda</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-xs text-slate-600">Enkripsi AES-256 untuk data Anda</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-xs text-slate-600">Dukungan 24/7 untuk seller enterprise</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center px-5 py-12 lg:py-0">
            <div class="w-full max-w-sm">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
