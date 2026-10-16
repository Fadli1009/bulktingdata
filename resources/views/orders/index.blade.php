<x-app.layout page="history" title="Riwayat">
    <x-app.page-header title="Riwayat pembelian" subtitle="Semua pesanan paket dan status pembayarannya.">
        <x-slot:actions>
            <x-app.button :href="url('/').'#paket'">Beli Paket</x-app.button>
        </x-slot:actions>
    </x-app.page-header>

    <x-app.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="bg-slate-50/70 text-left">
                        @foreach (['No. order', 'Paket', 'Tanggal', 'Total', 'Status', ''] as $h)
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($orders as $order)
                        <tr class="transition-colors hover:bg-blue-50/30">
                            <td class="whitespace-nowrap px-5 py-3.5 font-mono text-sm font-semibold text-slate-900">{{ $order->order_number }}</td>
                            <td class="px-5 py-3.5 text-sm text-slate-700">{{ $order->package?->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-sm text-slate-400">{{ $order->ordered_at?->translatedFormat('d M Y') }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-sm font-semibold text-slate-800">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5"><x-app.badge :status="$order->status->value" :label="$order->status->label()" /></td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                <a href="{{ route('orders.show', $order) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-app.empty-state icon="box" title="Belum ada pesanan"
                                    description="Pesanan paket Anda akan tercatat di sini setelah melakukan pembelian.">
                                    <x-slot:action>
                                        <x-app.button :href="url('/').'#paket'">Lihat Paket</x-app.button>
                                    </x-slot:action>
                                </x-app.empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (method_exists($orders, 'links'))
            <div class="border-t border-slate-100 p-4">{{ $orders->withQueryString()->links() }}</div>
        @endif
    </x-app.card>
</x-app.layout>
