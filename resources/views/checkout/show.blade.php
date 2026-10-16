<x-app.layout page="checkout" title="Checkout">
    <x-app.page-header title="Konfirmasi paket" subtitle="Tinjau detail paket sebelum membuat pesanan.">
        <x-slot:actions>
            <x-app.button variant="ghost" :href="url('/').'#paket'">Ganti paket</x-app.button>
        </x-slot:actions>
    </x-app.page-header>

    <div class="grid gap-6 lg:grid-cols-5">
        <x-app.card class="lg:col-span-3">
            <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="font-semibold text-slate-900">{{ $package->name }}</h2>
                    <p class="mt-0.5 text-xs text-slate-400">{{ $package->type->label() }} · {{ $package->billing_period->label() }}</p>
                </div>
                @if ($package->is_featured)
                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-blue-700">Populer</span>
                @endif
            </div>
            <div class="space-y-4 px-5 py-5">
                @if ($package->description)
                    <p class="text-sm leading-relaxed text-slate-500">{{ $package->description }}</p>
                @endif
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3 text-slate-700">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $package->upload_limit ? $package->upload_limit.' kali upload' : 'Upload tanpa batas' }}</span>
                    </li>
                    <li class="flex items-start gap-3 text-slate-700">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $package->seat_limit ? 'Hingga '.$package->seat_limit.' pengguna tim' : 'Pengguna tanpa batas' }}</span>
                    </li>
                    <li class="flex items-start gap-3 text-slate-700">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        <span>Pencocokan SKU standar</span>
                    </li>
                </ul>
            </div>
        </x-app.card>

        <x-app.card class="lg:col-span-2">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Ringkasan</h2>
            </div>
            <div class="space-y-3 px-5 py-5 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">{{ $package->name }}</span>
                    <span class="font-medium text-slate-800">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Periode</span>
                    <span class="font-medium text-slate-800">{{ $package->billing_period->label() }}</span>
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                    <span class="font-semibold text-slate-900">Total</span>
                    <span class="text-lg font-bold text-slate-900">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                </div>

                <p class="rounded-xl bg-slate-50 px-3.5 py-3 text-xs leading-relaxed text-slate-500 ring-1 ring-inset ring-slate-200/60">
                    Pembayaran dilakukan via transfer bank. Setelah pesanan dibuat, Anda akan diarahkan ke halaman pembayaran untuk mengunggah bukti transfer.
                </p>

                <form method="POST" action="{{ route('checkout.store', $package) }}">
                    @csrf
                    <x-app.button type="submit" class="w-full">
                        Buat Pesanan
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </x-app.button>
                </form>
            </div>
        </x-app.card>
    </div>
</x-app.layout>
