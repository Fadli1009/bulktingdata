<x-admin.layout page="dashboard">
    <x-admin.page-header title="Beranda" subtitle="Ringkasan aktivitas panel dan berkas terbaru." />

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat-card label="Total pelanggan" :value="$stats['customers'] ?? null" icon="users" accent="blue" />
        <x-admin.stat-card label="Total order" :value="$stats['orders'] ?? null" icon="receipt" accent="emerald" />
        <x-admin.stat-card label="Paket pembelian aktif" :value="$stats['packages'] ?? null" icon="box" accent="violet" />
        <x-admin.stat-card label="File Excel di-upload" :value="$stats['uploads'] ?? null" icon="file" accent="amber" />
    </section>

    <x-admin.card>
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-slate-900">Aktivitas terbaru</h2>
                <p class="text-xs text-slate-400">Log terakhir dari seluruh modul.</p>
            </div>
            <x-admin.button variant="ghost" size="sm" :href="url('/admin/logs')">Lihat semua</x-admin.button>
        </div>
        @forelse (($recentLogs ?? []) as $log)
            <div class="flex items-start gap-4 border-b border-slate-50 px-5 py-3.5 last:border-0 hover:bg-slate-50/60">
                <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500"></span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-slate-800">{{ $log->activity }}</p>
                    <p class="text-xs text-slate-400">{{ $log->user->name ?? 'Sistem' }} · {{ $log->module }}</p>
                </div>
                <time class="shrink-0 text-xs text-slate-400">{{ optional($log->created_at)->diffForHumans() }}</time>
            </div>
        @empty
            <x-admin.empty-state title="Belum ada aktivitas" description="Aktivitas akan muncul di sini setelah ada perubahan data." icon="file" />
        @endforelse
    </x-admin.card>
</x-admin.layout>
