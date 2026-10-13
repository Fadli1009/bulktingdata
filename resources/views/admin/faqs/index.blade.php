<x-admin.layout page="faqs">
    <x-admin.page-header title="FAQ" subtitle="Kelola pertanyaan umum dan jawaban untuk pelanggan.">
        <x-slot:actions>
            <x-admin.button x-on:click="newFaq()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                Tambah FAQ
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card x-data="{ f: { open: false, id: null, question: '', answer: '', sort_order: 0, status: 'published' },
                       newFaq() { this.f = { open: true, id: null, question: '', answer: '', sort_order: {{ (int) (collect($faqs ?? [])->max('sort_order') ?? 0) + 1 }}, status: 'published' } } }">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70"><tr>
                    @foreach (['Urutan', 'Pertanyaan', 'Jawaban', 'Status', 'Aksi'] as $h)
                        <x-admin.th>{{ $h }}</x-admin.th>
                    @endforeach
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @forelse (($faqs ?? []) as $q)
                    <tr class="transition-colors hover:bg-blue-50/30">
                        <x-admin.td class="w-16 font-semibold text-slate-900">{{ $q->sort_order }}</x-admin.td>
                        <x-admin.td class="font-semibold text-slate-900">{{ $q->question }}</x-admin.td>
                        <x-admin.td class="max-w-sm truncate text-slate-400">{{ $q->answer }}</x-admin.td>
                        <x-admin.td><x-admin.badge :status="$q->status" /></x-admin.td>
                        <x-admin.td>
                            <div class="flex flex-wrap gap-1">
                                <x-admin.button variant="ghost" size="sm" type="button" x-on:click="f = { open: true, id: {{ $q->id }}, question: @js($q->question), answer: @js($q->answer), sort_order: {{ (int) $q->sort_order }}, status: @js($q->status) }">Edit</x-admin.button>
                                <x-admin.button variant="ghost" size="sm" type="button" x-on:click="ask(@js(url('/admin/faqs/'.$q->id.'/toggle')), 'PATCH', 'Ubah status tayang FAQ ini?')">{{ $q->status === 'published' ? 'Sembunyikan' : 'Tayangkan' }}</x-admin.button>
                                <x-admin.button variant="danger" size="sm" type="button" x-on:click="ask(@js(url('/admin/faqs/'.$q->id)), 'DELETE', 'Hapus FAQ ini? Tindakan ini tidak bisa dibatalkan.', 'Hapus FAQ')">Hapus</x-admin.button>
                            </div>
                        </x-admin.td>
                    </tr>
                @empty
                    <tr><td colspan="5"><x-admin.empty-state title="Belum ada FAQ" description="Tambahkan pertanyaan umum untuk membantu pelanggan." icon="file">
                        <x-slot:action><x-admin.button x-on:click="newFaq()">Tambah FAQ</x-admin.button></x-slot:action>
                    </x-admin.empty-state></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if (isset($faqs) && method_exists($faqs, 'links'))
            <div class="border-t border-slate-100 p-4">{{ $faqs->withQueryString()->links() }}</div>
        @endif

        <div x-cloak x-show="f.open" class="fixed inset-0 z-50 flex items-end justify-center bg-slate-900/40 p-4 backdrop-blur-sm sm:items-center" @click.self="f.open = false">
            <form method="POST" :action="f.id ? '{{ url('/admin/faqs') }}/' + f.id : '{{ url('/admin/faqs') }}'" class="w-full max-w-lg space-y-4 rounded-2xl bg-white p-6 shadow-pop ring-1 ring-slate-200/70" @submit="busy = true">
                @csrf
                <template x-if="f.id"><input type="hidden" name="_method" value="PUT"></template>
                <div class="flex items-center justify-between"><h2 class="text-lg font-semibold text-slate-900" x-text="f.id ? 'Edit FAQ' : 'Tambah FAQ'"></h2><button type="button" class="text-slate-400 hover:text-slate-700" @click="f.open = false">✕</button></div>
                <div><x-admin.input label="Pertanyaan" name="question" x-model="f.question" required /></div>
                <div><x-admin.textarea label="Jawaban" name="answer" x-model="f.answer" :rows="5" required /></div>
                <div class="grid gap-4 sm:grid-cols-2"><div><x-admin.input label="Urutan tampil" name="sort_order" type="number" min="0" x-model="f.sort_order" /></div><div><x-admin.select label="Status" name="status" x-model="f.status"><option value="published">Tayang</option><option value="draft">Draft</option></x-admin.select></div></div>
                <div class="flex justify-end gap-2"><x-admin.button variant="secondary" type="button" @click="f.open = false">Batal</x-admin.button><x-admin.button type="submit" x-bind:disabled="busy">Simpan FAQ</x-admin.button></div>
            </form>
        </div>
    </x-admin.card>
</x-admin.layout>
