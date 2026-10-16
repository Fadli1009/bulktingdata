<x-app.layout page="history" title="Pembayaran">
    @php
        $banks = config('payments.bank_accounts', []);
        $canSubmit = $order->isPending() || $order->isProcessing();
        $steps = [
            ['Pesanan dibuat', $order->ordered_at],
            ['Bukti transfer diterima', $order->paid_at],
            ['Pembayaran dikonfirmasi', $order->confirmed_at],
        ];
        $currentStep = $order->isDone() ? 2 : ($order->isProcessing() || $order->paid_at ? 1 : 0);
    @endphp

    <x-app.page-header title="Pesanan {{ $order->order_number }}" subtitle="Selesaikan pembayaran untuk mengaktifkan paket Anda.">
        <x-slot:actions>
            <x-app.button variant="ghost" :href="route('dashboard.orders')">Riwayat</x-app.button>
            <x-app.badge :status="$order->status->value" :label="$order->status->label()" />
        </x-slot:actions>
    </x-app.page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-app.card>
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="font-semibold text-slate-900">Instruksi pembayaran</h2>
                    <p class="text-xs text-slate-400">Transfer sesuai total tagihan ke salah satu rekening berikut.</p>
                </div>
                <div class="space-y-3 px-5 py-5">
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 ring-1 ring-inset ring-slate-200/60">
                        <span class="text-sm text-slate-500">Total tagihan</span>
                        <span class="text-lg font-bold text-slate-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    @foreach ($banks as $bank)
                        <div class="flex items-center justify-between rounded-xl border border-slate-200/70 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $bank['bank'] ?? 'Bank' }}</p>
                                <p class="mt-0.5 font-mono text-sm text-slate-700">{{ $bank['number'] ?? '—' }}</p>
                                <p class="text-xs text-slate-400">a.n. {{ $bank['holder'] ?? '—' }}</p>
                            </div>
                            <button type="button"
                                x-data
                                @click="navigator.clipboard.writeText('{{ $bank['number'] ?? '' }}'); $el.dataset.copied = '1'; setTimeout(() => delete $el.dataset.copied, 1600)"
                                class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">
                                <span x-show="!$el.dataset.copied">Salin</span>
                                <span x-show="$el.dataset.copied" x-cloak>Tersalin</span>
                            </button>
                        </div>
                    @endforeach
                    @if ($order->paid_at)
                        <p class="text-xs text-slate-400">Bukti diunggah pada {{ $order->paid_at->translatedFormat('d M Y, H:i') }}.</p>
                    @endif
                </div>
            </x-app.card>

            <x-app.card>
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="font-semibold text-slate-900">Bukti transfer</h2>
                    <p class="text-xs text-slate-400">Unggah gambar atau PDF bukti transfer (maks. {{ intdiv(config('payments.proof_max_kilobytes', 5120), 1024) }}MB).</p>
                </div>
                <div class="px-5 py-5">
                    @if ($order->payment_proof_path)
                        <div class="mb-4 flex items-center gap-3 rounded-xl bg-emerald-50/70 px-4 py-3 text-sm text-emerald-800 ring-1 ring-inset ring-emerald-600/15">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="flex-1">Bukti transfer sudah diunggah.</span>
                            <a href="{{ route('orders.proof.download', $order) }}" class="font-semibold text-emerald-700 hover:text-emerald-800">Lihat</a>
                        </div>
                    @endif

                    @if ($canSubmit)
                        <form method="POST" action="{{ route('orders.proof', $order) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold text-slate-600" for="proof">File bukti transfer</label>
                                <label for="proof" x-data="{ name: '' }"
                                    class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border border-dashed border-slate-300 bg-slate-50/60 px-4 py-3 transition hover:border-indigo-300 hover:bg-indigo-50/40">
                                    <span class="flex min-w-0 items-center gap-2 text-sm text-slate-600">
                                        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5M4 20h16"/></svg>
                                        <span class="truncate" x-text="name || 'Pilih file bukti (JPG, PNG, PDF)'">Pilih file bukti (JPG, PNG, PDF)</span>
                                    </span>
                                    <span class="shrink-0 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white">Pilih</span>
                                    <input type="file" name="proof" id="proof" accept=".jpg,.jpeg,.png,.pdf" class="sr-only" @change="name = $event.target.files[0]?.name || ''">
                                </label>
                                @error('proof')
                                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold text-slate-600" for="payment_reference">Nama pengirim / referensi (opsional)</label>
                                <input type="text" name="payment_reference" id="payment_reference" value="{{ old('payment_reference', $order->payment_reference) }}"
                                    class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10">
                                @error('payment_reference')
                                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <x-app.button type="submit">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5M4 20h16"/></svg>
                                Kirim Bukti
                            </x-app.button>
                        </form>
                    @elseif ($order->isDone())
                        <p class="text-sm text-slate-500">Pembayaran telah dikonfirmasi. Paket Anda sudah aktif.</p>
                    @else
                        <p class="text-sm text-slate-500">Pesanan ini sudah {{ $order->status->label() }} dan tidak menerima bukti baru.</p>
                    @endif
                </div>
            </x-app.card>
        </div>

        <div class="space-y-6">
            <x-app.card>
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="font-semibold text-slate-900">Status pesanan</h2>
                </div>
                <ol class="px-5 py-5">
                    @foreach ($steps as $i => [$label, $at])
                        <li class="relative flex gap-3 pb-5 last:pb-0">
                            @if (! $loop->last)
                                <span class="absolute left-[11px] top-6 h-full w-px {{ $i < $currentStep ? 'bg-indigo-500' : 'bg-slate-200' }}"></span>
                            @endif
                            <span class="relative z-10 grid h-6 w-6 shrink-0 place-items-center rounded-full {{ $i <= $currentStep ? 'bg-gradient-to-br from-blue-600 to-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-medium {{ $i <= $currentStep ? 'text-slate-800' : 'text-slate-400' }}">{{ $label }}</p>
                                @if ($at)
                                    <p class="text-xs text-slate-400">{{ $at->translatedFormat('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </x-app.card>

            <x-app.card>
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="font-semibold text-slate-900">Ringkasan</h2>
                </div>
                <dl class="space-y-3 px-5 py-5 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Paket</dt>
                        <dd class="font-medium text-slate-800">{{ $order->package?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Periode</dt>
                        <dd class="font-medium text-slate-800">{{ $order->package?->billing_period->label() ?? '—' }}</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                        <dt class="font-semibold text-slate-900">Total</dt>
                        <dd class="font-bold text-slate-900">Rp {{ number_format($order->total, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </x-app.card>

            @if ($order->isDone() && $order->subscription)
                <x-app.card>
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="font-semibold text-slate-900">Langganan aktif</h2>
                    </div>
                    <div class="space-y-2 px-5 py-5 text-sm">
                        <p class="font-semibold text-slate-800">{{ $order->subscription->package?->name ?? '—' }}</p>
                        <p class="text-slate-500">
                            Berlaku hingga
                            <span class="font-medium text-slate-700">{{ $order->subscription->ends_at?->translatedFormat('d M Y') ?? 'Tanpa batas' }}</span>
                        </p>
                        <x-app.button variant="secondary" size="sm" :href="route('dashboard')" class="mt-2">Buka Dashboard</x-app.button>
                    </div>
                </x-app.card>
            @endif
        </div>
    </div>
</x-app.layout>
