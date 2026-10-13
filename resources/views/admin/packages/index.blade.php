<x-admin.layout page="packages">
    <x-admin.page-header title="Paket Pembelian" subtitle="Kelola harga, deskripsi, dan status paket untuk pelanggan.">
        <x-slot:actions>
            <x-admin.button x-on:click="newPkg()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                Tambah paket
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card x-data="{ f: { open: false, id: null, name: '', description: '', price: '', duration: '', status: 'active' },
                       newPkg() { this.f = { open: true, id: null, name: '', description: '', price: '', duration: '', status: 'active' } } }">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70"><tr>
                    @foreach (['Nama paket', 'Deskripsi', 'Harga', 'Durasi / batas', 'Status', 'Aksi'] as $h)
                        <x-admin.th>{{ $h }}</x-admin.th>
                    @endforeach
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @forelse (($packages ?? []) as $p)
                    <tr class="transition-colors hover:bg-blue-50/30">
                        <x-admin.td class="font-semibold text-slate-900">{{ $p->name }}</x-admin.td>
                        <x-admin.td class="max-w-xs truncate text-slate-400">{{ $p->description ?: '—' }}</x-admin.td>
                        <x-admin.td class="whitespace-nowrap font-semibold"><x-admin.currency :value="$p->price" /></x-admin.td>
                        <x-admin.td class="text-slate-400">{{ $p->duration ?: '—' }}</x-admin.td>
                        <x-admin.td><x-admin.badge :status="$p->status" /></x-admin.td>
                        <x-admin.td>
                            <div class="flex flex-wrap gap-1">
                                <x-admin.button variant="ghost" size="sm" type="button" x-on:click="f = { open: true, id: {{ $p->id }}, name: @js($p->name), description: @js($p->description), price: @js($p->price), duration: @js($p->duration), status: @js($p->status) }">Edit</x-admin.button>
                                <x-admin.button variant="ghost" size="sm" type="button" x-on:click="ask(@js(url('/admin/packages/'.$p->id.'/toggle')), 'PATCH', @js(($p->status === 'active' ? 'Nonaktifkan' : 'Aktifkan').' paket '.$p->name.'?'))">{{ $p->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}</x-admin.button>
                                <x-admin.button variant="ghost" size="sm" :href="url('/admin/orders?package_id='.$p->id)">Lihat order{{ isset($p->orders_count) ? ' ('.$p->orders_count.')' : '' }}</x-admin.button>
                            </div>
                        </x-admin.td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Belum ada paket" description="Tambahkan paket agar pelanggan bisa memilih opsi pembelian." icon="box">
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
            <form method="POST" :action="f.id ? '{{ url('/admin/packages') }}/' + f.id : '{{ url('/admin/packages') }}'" class="w-full max-w-lg space-y-4 rounded-2xl bg-white p-6 shadow-pop ring-1 ring-slate-200/70" @submit="busy = true">
                @csrf
                <template x-if="f.id"><input type="hidden" name="_method" value="PUT"></template>
                <div class="flex items-center justify-between"><h2 class="text-lg font-semibold text-slate-900" x-text="f.id ? 'Edit paket' : 'Tambah paket'"></h2><button type="button" class="text-slate-400 hover:text-slate-700" @click="f.open = false">✕</button></div>
                <div><x-admin.input label="Nama paket" name="name" x-model="f.name" required /></div>
                <div><x-admin.textarea label="Deskripsi" name="description" x-model="f.description" :rows="3" /></div>
                <div class="grid gap-4 sm:grid-cols-2"><div><x-admin.input label="Harga (Rp)" name="price" type="number" min="0" step="1" x-model="f.price" required /></div><div><x-admin.input label="Durasi / batas" hint="opsional" name="duration" x-model="f.duration" placeholder="mis. 30 hari" /></div></div>
                <div><x-admin.select label="Status" name="status" x-model="f.status"><option value="active">Aktif</option><option value="inactive">Nonaktif</option></x-admin.select></div>
                <div class="flex justify-end gap-2"><x-admin.button variant="secondary" type="button" @click="f.open = false">Batal</x-admin.button><x-admin.button type="submit" x-bind:disabled="busy">Simpan paket</x-admin.button></div>
            </form>
        </div>
    </x-admin.card>
</x-admin.layout>
