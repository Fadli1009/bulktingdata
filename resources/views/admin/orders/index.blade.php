<x-admin.layout page="orders">
    <x-admin.page-header title="Order" subtitle="Pantau dan kelola status order dari pelanggan." />

    <x-admin.card>
        <form method="GET" class="grid gap-3 border-b border-slate-100 p-4 sm:grid-cols-2 lg:grid-cols-6">
            <x-admin.select name="customer_id" class="lg:col-span-2">
                <option value="">Semua pelanggan</option>
                @foreach (($customerOptions ?? []) as $o)
                    <option value="{{ $o->id }}" @selected(request('customer_id') == $o->id)>{{ $o->name }}</option>
                @endforeach
            </x-admin.select>
            <x-admin.select name="status">
                <option value="">Semua status</option>
                @foreach (['pending', 'processing', 'done', 'cancelled'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ $statusLabel[$s] ?? $s }}</option>
                @endforeach
            </x-admin.select>
            <x-admin.input type="date" name="from" :value="request('from')" placeholder="Dari tanggal" />
            <x-admin.input type="date" name="to" :value="request('to')" placeholder="Sampai tanggal" />
            <div class="flex gap-2">
                <x-admin.button variant="secondary" type="submit" class="flex-1">Terapkan</x-admin.button>
                @if (request()->hasAny(['customer_id', 'status', 'from', 'to', 'package_id']))
                    <x-admin.button variant="ghost" :href="url('/admin/orders')">Reset</x-admin.button>
                @endif
            </div>
            @if (request('package_id'))
                <input type="hidden" name="package_id" value="{{ request('package_id') }}">
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70"><tr>
                    @foreach (['No. order', 'Pelanggan', 'Paket', 'Orders by', 'Tanggal', 'Total', 'Status', 'Aksi'] as $h)
                        <x-admin.th>{{ $h }}</x-admin.th>
                    @endforeach
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @forelse (($orders ?? []) as $o)
                    <tr class="transition-colors hover:bg-blue-50/30">
                        <x-admin.td class="font-mono font-semibold text-slate-900">{{ $o->order_number }}</x-admin.td>
                        <x-admin.td>{{ $o->customer->name ?? '—' }}</x-admin.td>
                        <x-admin.td>{{ $o->package->name ?? $o->package_name ?? '—' }}</x-admin.td>
                        <x-admin.td class="whitespace-nowrap text-slate-400">{{ optional($o->ordered_by)->name ?: '—' }}</x-admin.td>
                        <x-admin.td class="whitespace-nowrap text-slate-400">{{ optional($o->ordered_at ?? $o->created_at)->format('d M Y') }}</x-admin.td>
                        <x-admin.td class="whitespace-nowrap font-semibold"><x-admin.currency :value="$o->total ?? null" /></x-admin.td>
                        <x-admin.td><x-admin.badge :status="$o->status->value" /></x-admin.td>
                        <x-admin.td>
                            <div class="flex items-center gap-1.5">
                                @if ($o->payment_proof_path)
                                    <x-admin.button variant="secondary" size="sm" :href="route('admin.orders.proof', $o)">Bukti</x-admin.button>
                                @endif
                                @if ($o->isPending() || $o->isProcessing())
                                    <form method="POST" action="{{ route('admin.orders.confirm', $o) }}">
                                        @csrf
                                        <x-admin.button variant="primary" size="sm" type="submit">Konfirmasi</x-admin.button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.orders.cancel', $o) }}">
                                        @csrf
                                        <x-admin.button variant="danger" size="sm" type="submit">Batal</x-admin.button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </div>
                        </x-admin.td>
                    </tr>
                @empty
                    <tr><td colspan="8"><x-admin.empty-state title="Belum ada order" description="Order akan muncul setelah pelanggan melakukan pembelian." icon="inbox" /></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if (isset($orders) && method_exists($orders, 'links'))
            <div class="border-t border-slate-100 p-4">{{ $orders->withQueryString()->links() }}</div>
        @endif
    </x-admin.card>
</x-admin.layout>
