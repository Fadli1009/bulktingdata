<x-app.layout page="home" title="Beranda">
    @php
        $hour = now()->hour;
        $greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));
        $firstName = collect(explode(' ', trim($user->name)))->first() ?: 'Pengguna';
        $quotaLimit = $package?->upload_limit;
        $quotaRatio = ($quotaLimit === null || $quotaLimit === 0) ? null : min(100, (int) round(($quotaUsed / $quotaLimit) * 100));
        $daysLeft = ($subscription?->ends_at && $subscription->ends_at->isFuture()) ? (int) now()->diffInDays($subscription->ends_at) : null;
        $planHref = url('/').'#paket';
    @endphp

    <x-app.page-header :title="$greeting.', '.$firstName" subtitle="{{ now()->translatedFormat('l, d F Y') }}">
        <x-slot:actions>
            <x-app.button variant="secondary" :href="$planHref">Lihat Paket</x-app.button>
            <x-app.button type="button" title="Segera hadir" disabled>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5M4 20h16"/></svg>
                Upload Excel
            </x-app.button>
        </x-slot:actions>
    </x-app.page-header>

    <section class="grid gap-6 lg:grid-cols-3">
        <x-app.card class="lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="font-semibold text-slate-900">Langganan aktif</h2>
                    <p class="text-xs text-slate-400">Status paket dan pemakaian kuota Anda.</p>
                </div>
                @if ($subscription)
                    <x-app.badge :status="$subscription->status->value" :label="$subscription->status->label()" />
                @endif
            </div>

            @if ($subscription && $package)
                <div class="grid gap-6 px-5 py-5 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">Paket</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ $package->name }}</p>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $package->type->label() }} · {{ $package->billing_period->label() }}
                        </p>
                        <p class="mt-3 text-sm text-slate-500">
                            Berlaku hingga
                            <span class="font-semibold text-slate-800">{{ $subscription->ends_at?->translatedFormat('d M Y') ?? 'Tanpa batas' }}</span>
                            @if ($daysLeft !== null)
                                <span class="text-slate-400">· {{ $daysLeft }} hari lagi</span>
                            @endif
                        </p>
                        <div class="mt-4 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400">
                            @if ($user->isBusiness())
                                <span>{{ $isTeamOwner ? $teamMembersCount.' anggota tim' : 'Anggota tim' }}</span>
                            @endif
                            <span>{{ $package->seat_limit ? 'Kuota '.$package->seat_limit.' pengguna' : 'Pengguna tanpa batas' }}</span>
                        </div>
                    </div>

                    <div class="sm:border-l sm:border-slate-100 sm:pl-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">Kuota upload</p>
                        @if ($quotaLimit === null)
                            <p class="mt-1 text-2xl font-bold text-slate-900">Tanpa batas</p>
                            <p class="mt-1 text-sm text-slate-500">{{ number_format($quotaUsed, 0, ',', '.') }} file telah diproses.</p>
                        @else
                            <p class="mt-1 text-2xl font-bold text-slate-900">
                                {{ number_format($quotaUsed, 0, ',', '.') }}
                                <span class="text-base font-medium text-slate-400">/ {{ number_format($quotaLimit, 0, ',', '.') }}</span>
                            </p>
                            <p class="mt-1 text-sm text-slate-500">file terpakai pada siklus ini.</p>
                            <x-app.progress class="mt-3" :value="$quotaUsed" :max="$quotaLimit" :tone="$quotaRatio !== null && $quotaRatio >= 80 ? 'amber' : 'indigo'" />
                        @endif
                    </div>
                </div>
            @else
                <x-app.empty-state icon="box" title="Belum ada langganan aktif"
                    description="Pilih paket untuk mulai mengunggah dan mencocokkan katalog marketplace Anda.">
                    <x-slot:action>
                        <x-app.button :href="$planHref">Pilih Paket</x-app.button>
                    </x-slot:action>
                </x-app.empty-state>
            @endif
        </x-app.card>

        <x-app.card>
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Aksi cepat</h2>
                <p class="text-xs text-slate-400">Mulai pekerjaan Anda berikutnya.</p>
            </div>
            <div class="space-y-1 p-3">
                @php
                    $actions = [
                        ['Beli / Upgrade Paket', 'Pilih atau perpanjang langganan Anda.', 'M21 8l-9-5-9 5v8l9 5 9-5zM3 8l9 5 9-5M12 13v8', $planHref],
                        ['Riwayat Order', 'Pantau status pembayaran paket.', 'M6 6h15l-1.5 9h-12zM6 6L5 3H2M9 20h.01M18 20h.01', route('dashboard.orders')],
                        ['Mulai Upload Excel', 'Unggah template campaign & SKU master.', 'M12 16V4M7 9l5-5 5 5M4 20h16', null],
                    ];
                @endphp
                @foreach ($actions as [$label, $desc, $icon, $href])
                    @if ($href)
                        <a href="{{ $href }}" class="group flex items-start gap-3 rounded-xl px-3 py-3 transition-colors hover:bg-slate-50">
                    @else
                        <span class="group flex cursor-not-allowed items-start gap-3 rounded-xl px-3 py-3">
                    @endif
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-600 ring-1 ring-inset ring-blue-600/10">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="flex items-center gap-2">
                                    <span class="text-sm font-semibold {{ $href ? 'text-slate-800 group-hover:text-indigo-700' : 'text-slate-500' }}">{{ $label }}</span>
                                    @unless ($href)
                                        <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-400">Segera</span>
                                    @endunless
                                </span>
                                <span class="mt-0.5 block text-xs text-slate-400">{{ $desc }}</span>
                            </span>
                    @if ($href)
                        </a>
                    @else
                        </span>
                    @endif
                @endforeach
            </div>
        </x-app.card>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-app.stat-card label="Total upload" :value="$uploadStats->total ?? 0" icon="upload" accent="indigo" />
        <x-app.stat-card label="Berhasil diproses" :value="$uploadStats->done ?? 0" icon="check" accent="emerald" />
        <x-app.stat-card label="Gagal" :value="$uploadStats->failed ?? 0" icon="alert" accent="rose" />
        <x-app.stat-card label="Baris diproses" :value="$uploadStats->rows ?? 0" icon="rows" accent="amber" />
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <x-app.card class="lg:col-span-2">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="font-semibold text-slate-900">Upload terbaru</h2>
                    <p class="text-xs text-slate-400">Lima berkas terakhir yang Anda unggah.</p>
                </div>
                @if (Route::has('dashboard.history'))
                    <x-app.button variant="ghost" size="sm" :href="route('dashboard.history')">Lihat semua</x-app.button>
                @endif
            </div>

            @forelse ($recentUploads as $upload)
                <div class="flex items-center gap-4 border-b border-slate-50 px-5 py-3.5 last:border-0 hover:bg-slate-50/60">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-slate-100 text-slate-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/></svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-slate-800">{{ $upload->original_name }}</p>
                        <p class="text-xs text-slate-400">
                            {{ number_format($upload->rows_processed ?? 0, 0, ',', '.') }} baris · {{ $upload->created_at?->diffForHumans() }}
                        </p>
                    </div>
                    <x-app.badge :status="$upload->status->value" :label="$upload->status->label()" />
                </div>
            @empty
                <x-app.empty-state icon="upload" title="Belum ada upload"
                    description="Berkas yang Anda unggah akan tampil di sini beserta status pencocokannya." />
            @endforelse
        </x-app.card>

        <x-app.card>
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Order & langganan</h2>
                <p class="text-xs text-slate-400">Riwayat pembelian paket.</p>
            </div>

            @forelse ($recentOrders as $order)
                <a href="{{ route('orders.show', $order) }}" class="flex items-start gap-3 border-b border-slate-50 px-5 py-3.5 transition-colors last:border-0 hover:bg-slate-50/60">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-slate-800">{{ $order->package?->name ?? 'Paket' }}</p>
                        <p class="text-xs text-slate-400">{{ $order->order_number }} · {{ $order->ordered_at?->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-sm font-semibold text-slate-800">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        <p class="text-[11px] font-medium text-slate-400">{{ $order->status->label() }}</p>
                    </div>
                </a>
            @empty
                <x-app.empty-state icon="box" title="Belum ada order" description="Order paket Anda akan tercatat di sini." />
            @endforelse
        </x-app.card>
    </section>
</x-app.layout>
