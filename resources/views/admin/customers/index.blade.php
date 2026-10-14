<x-admin.layout page="customers">
    <x-admin.page-header title="Data Pelanggan" subtitle="Kelola pelanggan, status, dan informasi toko mereka.">
        <x-slot:actions>
            <x-admin.button :href="url('/admin/customers/create')">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                Tambah pelanggan
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card x-data="{ edit: { open: false, id: null, name: '', email: '', phone: '', marketplace: '', status: 'active', url: '', created: '' } }">
        <form method="GET" class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-center">
            <div class="relative flex-1 sm:max-w-xs">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <x-admin.input type="search" name="q" :value="request('q')" placeholder="Cari pelanggan..." class="pl-9" />
            </div>
            <x-admin.select name="status" class="sm:w-40">
                <option value="">Semua status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </x-admin.select>
            <x-admin.button variant="secondary" type="submit">Terapkan</x-admin.button>
            @if (request()->hasAny(['q', 'status']))
                <x-admin.button variant="ghost" :href="url('/admin/customers')">Reset</x-admin.button>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70"><tr>
                    @foreach (['Nama', 'Email', 'Telepon', 'Status', 'Dibuat Oleh', 'Aksi'] as $h)
                        <x-admin.th>{{ $h }}</x-admin.th>
                    @endforeach
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @forelse (($customers ?? []) as $c)
                    <tr class="transition-colors hover:bg-blue-50/30">
                        <x-admin.td class="font-semibold text-slate-900">{{ $c->name }}</x-admin.td>
                        <x-admin.td>{{ $c->email }}</x-admin.td>
                        <x-admin.td>{{ $c->phone ?: '—' }}</x-admin.td>
                        <x-admin.td><x-admin.badge :status="$c->status" /></x-admin.td>
                        <x-admin.td class="whitespace-nowrap text-slate-400">{{ optional($c->created_at)->format('d M Y') }}</x-admin.td>
                        <x-admin.td>
                            <div class="flex flex-wrap gap-1">
                                <x-admin.button variant="ghost" size="sm" type="button"
                                    x-on:click="edit = { open: true, id: {{ $c->id }}, name: @js($c->name), email: @js($c->email), phone: @js($c->phone), marketplace: @js($c->marketplace), status: @js($c->status), url: @js($c->unique_url ?? ''), created: @js(optional($c->created_at)->format('d M Y H:i')) }">Detail</x-admin.button>
                                @if (!empty($c->unique_url))
                                    <x-admin.button variant="ghost" size="sm" type="button" x-on:click="copy(@js($c->unique_url))">
                                        <span x-text="copied === @js($c->unique_url) ? 'Tersalin' : 'Salin URL'"></span>
                                    </x-admin.button>
                                @endif
                                <x-admin.button variant="ghost" size="sm" type="button"
                                    x-on:click="ask(@js(url('/admin/customers/'.$c->id.'/toggle')), 'PATCH', @js($c->status === 'active' ? 'Nonaktifkan pelanggan '.$c->name.'?' : 'Aktifkan pelanggan '.$c->name.'?'))">
                                    {{ $c->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </x-admin.button>
                            </div>
                        </x-admin.td>
                    </tr>
                @empty
                    <tr><td colspan="7"><x-admin.empty-state title="Belum ada pelanggan" description="Tambahkan pelanggan pertama untuk mulai mengelola project." icon="users">
                        <x-slot:action><x-admin.button :href="url('/admin/customers/create')">Tambah pelanggan</x-admin.button></x-slot:action>
                    </x-admin.empty-state></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if (isset($customers) && method_exists($customers, 'links'))
            <div class="border-t border-slate-100 p-4">{{ $customers->withQueryString()->links() }}</div>
        @endif

        <div x-cloak x-show="edit.open" class="fixed inset-0 z-50 flex items-end justify-center bg-slate-900/40 p-4 backdrop-blur-sm sm:items-center" @click.self="edit.open = false">
            <form method="POST" :action="'{{ url('/admin/customers') }}/' + edit.id" class="w-full max-w-lg space-y-4 rounded-2xl bg-white p-6 shadow-pop ring-1 ring-slate-200/70" @submit="busy = true">
                @csrf @method('PUT')
                <div class="flex items-center justify-between"><h2 class="text-lg font-semibold text-slate-900">Detail pelanggan</h2><button type="button" class="text-slate-400 hover:text-slate-700" @click="edit.open = false">✕</button></div>
                <div><x-admin.input label="Nama" name="name" x-model="edit.name" required /></div>
                <div class="grid gap-4 sm:grid-cols-2"><div><x-admin.input label="Email" name="email" type="email" x-model="edit.email" required /></div><div><x-admin.input label="Telepon" name="phone" x-model="edit.phone" /></div></div>
                <div class="grid gap-4 sm:grid-cols-2"><div><x-admin.select label="Marketplace" name="marketplace" x-model="edit.marketplace"><option value="">Tidak ada</option><option value="shopee">Shopee</option><option value="tokopedia">Tokopedia</option><option value="lazada">Lazada</option><option value="tiktok">TikTok Shop</option></x-admin.select></div><div><x-admin.select label="Status" name="status" x-model="edit.status"><option value="active">Aktif</option><option value="inactive">Nonaktif</option></x-admin.select></div></div>
                <dl class="rounded-xl bg-slate-50 p-3 text-xs text-slate-500"><div class="flex justify-between"><dt>Dibuat</dt><dd x-text="edit.created"></dd></div><div class="mt-1 flex justify-between gap-4"><dt>URL pelanggan</dt><dd class="truncate font-mono" x-text="edit.url || 'Belum tersedia'"></dd></div></dl>
                <div class="flex justify-end gap-2"><x-admin.button variant="secondary" type="button" @click="edit.open = false">Batal</x-admin.button><x-admin.button type="submit" x-bind:disabled="busy">Simpan perubahan</x-admin.button></div>
            </form>
        </div>
    </x-admin.card>
</x-admin.layout>
