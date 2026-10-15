<x-admin.layout page="customers">
    <x-admin.page-header title="Data Pelanggan" subtitle="Pantau pelanggan terdaftar beserta status onboarding mereka." />

    <x-admin.card>
        <form method="GET" class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-center">
            <div class="relative flex-1 sm:max-w-xs">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <x-admin.input type="search" name="q" :value="request('q')" placeholder="Cari nama atau email..." class="pl-9" />
            </div>
            <x-admin.select name="status" class="sm:w-40">
                <option value="">Semua status</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="success" @selected(request('status') === 'success')>Sukses</option>
            </x-admin.select>
            <x-admin.button variant="secondary" type="submit">Terapkan</x-admin.button>
            @if (request()->hasAny(['q', 'status']))
                <x-admin.button variant="ghost" :href="url('/admin/customers')">Reset</x-admin.button>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70"><tr>
                    @foreach (['Nama', 'Email', 'Telepon', 'Tipe Akun', 'Status', 'Terdaftar'] as $h)
                        <x-admin.th>{{ $h }}</x-admin.th>
                    @endforeach
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @forelse ($customers as $c)
                    <tr class="transition-colors hover:bg-blue-50/30">
                        <x-admin.td class="font-semibold text-slate-900">{{ $c->name }}</x-admin.td>
                        <x-admin.td>{{ $c->email }}</x-admin.td>
                        <x-admin.td>{{ $c->phone ?: '—' }}</x-admin.td>
                        <x-admin.td class="capitalize">{{ $c->account_type ?: '—' }}</x-admin.td>
                        <x-admin.td><x-admin.badge :status="$c->status" /></x-admin.td>
                        <x-admin.td class="whitespace-nowrap text-slate-400">{{ optional($c->created_at)->format('d M Y') }}</x-admin.td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Belum ada pelanggan" description="Pelanggan yang mendaftar lewat email OTP atau Google akan muncul di sini." icon="users" /></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($customers->hasPages())
            <div class="border-t border-slate-100 p-4">{{ $customers->links() }}</div>
        @endif
    </x-admin.card>
</x-admin.layout>
