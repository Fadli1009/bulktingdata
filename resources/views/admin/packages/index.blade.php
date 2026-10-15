<x-admin.layout page="packages">
    <x-admin.page-header title="Paket Pembelian" subtitle="Kelola harga, limit, dan status paket untuk pelanggan.">
        <x-slot:actions>
            <x-admin.button x-on:click="newPkg()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                Tambah paket
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card x-data="{
        f: {
            open: false, id: null, name: '', description: '', type: 'personal',
            price: '', billing_period: 'monthly', upload_limit: '', seat_limit: '',
            status: 'active', sort_order: 0, badge: '', is_featured: false, features: ''
        },
        newPkg() {
            this.f = {
                open: true, id: null, name: '', description: '', type: 'personal',
                price: '', billing_period: 'monthly', upload_limit: '', seat_limit: '',
                status: 'active', sort_order: 0, badge: '', is_featured: false, features: ''
            }
        },
        edit(pkg) {
            this.f = Object.assign({}, pkg, {
                open: true,
                features: (pkg.features || []).join('\n')
            })
        }
    }">
        <form method="GET" class="flex flex-wrap items-center gap-3 border-b border-slate-100 p-4">
            <x-admin.select name="type" class="!w-auto" onchange="this.form.submit()">
                <option value="">Semua tipe</option>
                <option value="personal" @selected(($type ?? '') === 'personal')>Personal</option>
                <option value="bisnis" @selected(($type ?? '') === 'bisnis')>Bisnis</option>
            </x-admin.select>
            <x-admin.button variant="secondary" type="submit">Terapkan</x-admin.button>
            @if (($type ?? '') !== '')
                <x-admin.button variant="ghost" :href="url('/admin/packages')">Reset</x-admin.button>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70"><tr>
                    @foreach (['Nama paket', 'Tipe', 'Deskripsi', 'Harga', 'Limit', 'Status', 'Aksi'] as $h)
                        <x-admin.th>{{ $h }}</x-admin.th>
                    @endforeach
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @forelse (($packages ?? []) as $p)
                    <tr class="transition-colors hover:bg-blue-50/30">
                        <x-admin.td class="font-semibold text-slate-900">
                            <div class="flex items-center gap-2">
                                <span>{{ $p->name }}</span>
                                @if ($p->is_featured)
                                    <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700">Utama</span>
                                @endif
                                @if ($p->badge)
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">{{ $p->badge }}</span>
                                @endif
                            </div>
                        </x-admin.td>
                        <x-admin.td>
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $p->type->value === 'bisnis' ? 'bg-indigo-50 text-indigo-700 ring-indigo-600/20' : 'bg-blue-50 text-blue-700 ring-blue-600/20' }}">
                                {{ $p->type->label() }}
                            </span>
                        </x-admin.td>
                        <x-admin.td class="max-w-xs truncate text-slate-400">{{ $p->description ?: '—' }}</x-admin.td>
                        <x-admin.td class="whitespace-nowrap font-semibold">
                            <x-admin.currency :value="$p->price" />
                            <span class="text-xs font-normal text-slate-400">/{{ $p->billing_period->value === 'yearly' ? 'tahun' : 'bulan' }}</span>
                        </x-admin.td>
                        <x-admin.td class="whitespace-nowrap text-slate-500">
                            @if ($p->type->value === 'bisnis')
                                <span class="block">Upload: <b>Unlimited</b></span>
                                <span class="block">User: <b>{{ $p->seat_limit ? $p->seat_limit : 'Unlimited' }}</b></span>
                            @else
                                <span class="block">Upload: <b>{{ $p->upload_limit ? $p->upload_limit.'x' : 'Unlimited' }}</b></span>
                            @endif
                        </x-admin.td>
                        <x-admin.td><x-admin.badge :status="$p->status" /></x-admin.td>
                        <x-admin.td>
                            <div class="flex flex-wrap gap-1">
                                <x-admin.button variant="ghost" size="sm" type="button" x-on:click="edit(@js(['id' => $p->id, 'name' => $p->name, 'description' => $p->description, 'type' => $p->type->value, 'price' => $p->price, 'billing_period' => $p->billing_period->value, 'upload_limit' => $p->upload_limit, 'seat_limit' => $p->seat_limit, 'status' => $p->status, 'sort_order' => $p->sort_order, 'badge' => $p->badge, 'is_featured' => (bool) $p->is_featured, 'features' => $p->features ?? []]))">Edit</x-admin.button>
                                <x-admin.button variant="ghost" size="sm" type="button" x-on:click="ask(@js(url('/admin/packages/'.$p->id.'/toggle')), 'PATCH', @js(($p->status === 'active' ? 'Nonaktifkan' : 'Aktifkan').' paket '.$p->name.'?'))">{{ $p->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}</x-admin.button>
                                <x-admin.button variant="ghost" size="sm" type="button" x-on:click="ask(@js(url('/admin/packages/'.$p->id)), 'DELETE', @js('Hapus paket '.$p->name.'? Tindakan tidak bisa dibatalkan.'), 'Hapus')">Hapus</x-admin.button>
                            </div>
                        </x-admin.td>
                    </tr>
                @empty
                    <tr><td colspan="7"><x-admin.empty-state title="Belum ada paket" description="Tambahkan paket agar pelanggan bisa memilih opsi pembelian." icon="box">
                        <x-slot:action><x-admin.button x-on:click="newPkg()">Tambah paket</x-admin.button></x-slot:action>
                    </x-admin.empty-state></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if (isset($packages) && method_exists($packages, 'links'))
            <div class="border-t border-slate-100 p-4">{{ $packages->withQueryString()->links() }}</div>
        @endif

        <div x-cloak x-show="f.open" class="fixed inset-0 z-50 flex items-end justify-center bg-slate-900/40 p-4 backdrop-blur-sm sm:items-center" @click.self="f.open = false">
            <form method="POST" :action="f.id ? '{{ url('/admin/packages') }}/' + f.id : '{{ url('/admin/packages') }}'" class="max-h-[90vh] w-full max-w-lg space-y-4 overflow-y-auto rounded-2xl bg-white p-6 shadow-pop ring-1 ring-slate-200/70" @submit="busy = true">
                @csrf
                <template x-if="f.id"><input type="hidden" name="_method" value="PUT"></template>
                <div class="flex items-center justify-between"><h2 class="text-lg font-semibold text-slate-900" x-text="f.id ? 'Edit paket' : 'Tambah paket'"></h2><button type="button" class="text-slate-400 hover:text-slate-700" @click="f.open = false">✕</button></div>
                <div><x-admin.input label="Nama paket" name="name" x-model="f.name" required /></div>
                <div><x-admin.textarea label="Deskripsi" name="description" x-model="f.description" :rows="3" /></div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <x-admin.select label="Tipe paket" name="type" x-model="f.type" required>
                            <option value="personal">Personal (limit upload)</option>
                            <option value="bisnis">Bisnis (unlimited + tim)</option>
                        </x-admin.select>
                    </div>
                    <div>
                        <x-admin.select label="Periode tagihan" name="billing_period" x-model="f.billing_period" required>
                            <option value="monthly">Bulanan</option>
                            <option value="yearly">Tahunan</option>
                        </x-admin.select>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><x-admin.input label="Harga (Rp)" name="price" type="number" min="0" step="1" x-model="f.price" required /></div>
                    <div><x-admin.input label="Urutan tampil" hint="opsional" name="sort_order" type="number" min="0" step="1" x-model="f.sort_order" /></div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div x-show="f.type === 'personal'">
                        <x-admin.input label="Limit upload (total)" hint="kosong = unlimited" name="upload_limit" type="number" min="0" step="1" x-model="f.upload_limit" />
                    </div>
                    <div x-show="f.type === 'bisnis'" x-cloak>
                        <x-admin.input label="Jumlah user (seat)" hint="kosong = unlimited" name="seat_limit" type="number" min="0" step="1" x-model="f.seat_limit" />
                    </div>
                    <div>
                        <x-admin.select label="Status" name="status" x-model="f.status">
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </x-admin.select>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><x-admin.input label="Badge" hint="mis. Populer" name="badge" x-model="f.badge" /></div>
                    <div class="flex items-end pb-2">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="is_featured" value="1" x-model="f.is_featured" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            Jadikan paket utama
                        </label>
                    </div>
                </div>
                <div>
                    <x-admin.textarea label="Fitur landing" hint="satu fitur per baris, opsional" name="features" x-model="f.features" :rows="4" placeholder="Upload tanpa batas&#10;Hingga 5 pengguna tim" />
                    <p class="mt-1 text-xs text-slate-400">Awali dengan tanda “-” untuk menandai fitur tidak tersedia.</p>
                </div>
                <div class="flex justify-end gap-2"><x-admin.button variant="secondary" type="button" @click="f.open = false">Batal</x-admin.button><x-admin.button type="submit" x-bind:disabled="busy">Simpan paket</x-admin.button></div>
            </form>
        </div>
    </x-admin.card>
</x-admin.layout>
