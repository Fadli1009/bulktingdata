<x-admin.layout page="logs">
    <x-admin.page-header title="Sistem Logs" subtitle="Riwayat aktivitas dan perubahan pada seluruh modul." />

    <x-admin.card x-data="{ detail: null }">
        <form method="GET" class="grid gap-3 border-b border-slate-100 p-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="relative lg:col-span-2">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <x-admin.input type="search" name="q" :value="request('q')" placeholder="Cari aktivitas atau user..." class="pl-9" />
            </div>
            <x-admin.select name="module">
                <option value="">Semua modul</option>
                @foreach (['Pelanggan', 'Order', 'Paket', 'FAQ', 'Upload Excel', 'Campaign'] as $m)
                    <option @selected(request('module') === $m)>{{ $m }}</option>
                @endforeach
            </x-admin.select>
            <x-admin.input type="date" name="from" :value="request('from')" placeholder="Dari tanggal" />
            <div class="flex gap-2">
                <x-admin.input type="date" name="to" :value="request('to')" placeholder="Sampai tanggal" class="flex-1" />
                <x-admin.button variant="secondary" type="submit">Cari</x-admin.button>
            </div>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70"><tr>
                    @foreach (['Waktu', 'User', 'Aktivitas', 'Modul', 'Status', 'Detail'] as $h)
                        <x-admin.th>{{ $h }}</x-admin.th>
                    @endforeach
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @forelse (($logs ?? []) as $l)
                    <tr class="transition-colors hover:bg-blue-50/30">
                        <x-admin.td class="whitespace-nowrap text-slate-400">{{ optional($l->created_at)->format('d M Y H:i') }}</x-admin.td>
                        <x-admin.td class="font-medium text-slate-700">{{ $l->user->name ?? 'Sistem' }}</x-admin.td>
                        <x-admin.td>{{ $l->activity }}</x-admin.td>
                        <x-admin.td class="text-slate-400">{{ $l->module }}</x-admin.td>
                        <x-admin.td><x-admin.badge :status="$l->status" /></x-admin.td>
                        <x-admin.td>
                            @if ($l->detail)
                                <button type="button" class="rounded-lg px-2 py-1 text-xs font-semibold text-blue-600 transition-colors hover:bg-blue-50" @click="detail = @js(is_string($l->detail) ? $l->detail : json_encode($l->detail, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT))">Lihat</button>
                            @else
                                —
                            @endif
                        </x-admin.td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Tidak ada log" description="Tidak ada aktivitas yang cocok dengan filter." icon="search" /></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if (isset($logs) && method_exists($logs, 'links'))
            <div class="border-t border-slate-100 p-4">{{ $logs->withQueryString()->links() }}</div>
        @endif

        <div x-cloak x-show="detail !== null" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm" @click.self="detail = null">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-pop ring-1 ring-slate-200/70">
                <div class="flex items-center justify-between"><h2 class="text-lg font-semibold text-slate-900">Detail log</h2><button type="button" class="text-slate-400 hover:text-slate-700" @click="detail = null">✕</button></div>
                <pre class="mt-3 max-h-80 overflow-auto whitespace-pre-wrap rounded-xl bg-slate-50 p-3 text-xs text-slate-600" x-text="detail"></pre>
            </div>
        </div>
    </x-admin.card>
</x-admin.layout>
