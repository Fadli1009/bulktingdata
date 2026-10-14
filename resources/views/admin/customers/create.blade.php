<x-admin.layout page="customer-create">
    <x-admin.page-header title="Tambah Pelanggan" subtitle="Informasi yang diisi akan dipakai untuk mengelola project dan integrasi marketplace.">
        <x-slot:actions>
            <x-admin.button variant="secondary" size="sm" :href="url('/admin/customers')">Kembali</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <form method="POST" action="{{ url('/admin/customers') }}" autocomplete="off"
          x-data="{ marketplace: @js(old('marketplace', '')), copied: '' }"
          @submit="busy = true">
        @csrf

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                <p class="font-semibold">Periksa kembali isian form</p>
                <ul class="mt-1.5 list-disc space-y-0.5 pl-5 text-sm leading-5">
                    @foreach ($errors->all() as $m)<li>{{ $m }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if (!empty($newCustomer))
            <section class="mb-5 rounded-2xl border border-emerald-200/70 bg-emerald-50/80 p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-emerald-600 text-white shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <div class="flex-1">
                        <h2 class="text-sm font-semibold text-emerald-900">Pelanggan <span class="font-bold">{{ $newCustomer->name }}</span> berhasil dibuat</h2>
                        <p class="mt-1 text-xs leading-5 text-emerald-700">Bagikan URL unik di bawah ini ke pelanggan untuk akses project mereka.</p>
                    </div>
                </div>
                @if (!empty($newCustomer->unique_url))
                    <div class="mt-4">
                        <p class="mb-1.5 text-xs font-semibold uppercase tracking-wide text-emerald-700/80">URL unik pelanggan</p>
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            <div class="flex-1 rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm font-mono text-slate-700 shadow-sm select-all" onclick="this.select?.()">{{ $newCustomer->unique_url }}</div>
                            <input type="text" class="sr-only" value="{{ $newCustomer->unique_url }}" readonly>
                            <x-admin.button type="button" size="sm" class="shrink-0" title="Salin URL" @click="navigator.clipboard.writeText(@js($newCustomer->unique_url)); copied='url'; setTimeout(() => copied='', 1500)">
                                <svg x-show="copied!=='url'" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <svg x-cloak x-show="copied==='url'" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span x-text="copied==='url' ? 'Tersalin' : 'Salin URL'"></span>
                            </x-admin.button>
                        </div>
                    </div>
                @endif
            </section>
        @endif

        <x-admin.card>
            <div class="divide-y divide-slate-100">
                <div class="space-y-6 p-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="grid h-6 w-6 place-items-center rounded-full bg-blue-600 text-[10px] font-bold text-white">1</span>
                            <h3 class="text-sm font-semibold text-slate-900">Informasi utama</h3>
                        </div>
                        <p class="mt-1 pl-8 text-xs text-slate-400">Data identitas yang ditampilkan di daftar pelanggan.</p>
                    </div>
                    <div class="space-y-4">
                        
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-admin.input label="Email" name="email" id="email" type="email" :value="old('email')" required placeholder="nama@contoh.com" autocomplete="email" />
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="space-y-4 p-6">
                    
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="grid h-6 w-6 place-items-center rounded-full bg-violet-600 text-[10px] font-bold text-white">2</span>
                            <h3 class="text-sm font-semibold text-slate-900">Status & marketplace</h3>
                        </div>
                        <p class="mt-1 pl-8 text-xs text-slate-400">Atur status akses dan marketplace yang terhubung.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-admin.input label="No Pesanan" name="order_number" id="order_number" :value="old('order_number')" required placeholder="Masukkan nomor pesanan" />
                            </div>
                            
                        </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <x-admin.select label="Status" name="status" id="status" required>
                                <option value="active" @selected(old('status', 'active') === 'active')>Aktif</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Nonaktif</option>
                            </x-admin.select>
                        </div>
                        <div>
                            <x-admin.select label="Marketplace" hint="jika ada" name="marketplace" id="marketplace" x-model="marketplace">
                                <option value="">Bukan pelanggan marketplace</option>
                                <option value="shopee">Shopee</option>
                                <option value="tokopedia">Tokopedia</option>
                                <option value="lazada">Lazada</option>
                                <option value="tiktok">TikTok Shop</option>
                            </x-admin.select>
                        </div>
                    </div>

                    {{-- <div
                        x-cloak
                        x-show="marketplace"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-700">Detail toko marketplace</p>
                            <span class="rounded-full bg-white px-2 py-0.5 text-xs font-medium text-slate-500 ring-1 ring-slate-200" x-text="marketplace ? marketplace : ''"></span>
                        </div>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-admin.input label="Nama toko di marketplace" name="store_name" id="store_name" :value="old('store_name')" placeholder="Nama toko marketplace" />
                            </div>
                            <div>
                                <x-admin.input label="ID toko" hint="opsional" name="store_id" id="store_id" :value="old('store_id')" placeholder="ID toko" />
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-slate-400">Kosongkan jika toko belum tersedia — bisa diisi nanti di halaman Pelanggan.</p>
                    </div> --}}
                </div>
            </div>

            <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50/40 px-6 py-4 sm:flex-row sm:justify-end sm:gap-3">
                <x-admin.button variant="secondary" :href="url('/admin/customers')">Batal</x-admin.button>
                <x-admin.button type="submit" x-bind:disabled="busy">
                    <svg x-cloak x-show="busy" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                    <span x-show="!busy">Simpan pelanggan</span>
                    <span x-cloak x-show="busy">Menyimpan…</span>
                </x-admin.button>
            </div>
        </x-admin.card>
    </form>

    <p class="mt-4 text-center text-xs text-slate-400">Semua data tersimpan aman dan URL unik digenerate otomatis.</p>
</x-admin.layout>
