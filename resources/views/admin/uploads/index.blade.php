<x-admin.layout page="uploads">
    <x-admin.page-header title="Upload Template Excel"
        subtitle="Sediakan template Excel yang dapat diunduh pelanggan untuk mengisi data produk.">
        <x-slot:actions>
            <x-admin.button variant="secondary" size="sm" :href="url('/admin')">Kembali</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <form method="POST" action="{{ url('/admin/uploads') }}" enctype="multipart/form-data" autocomplete="off"
        x-data="{
            files: { template: null, marketplace: null, result: null },
            pick(key, e) {
                const f = e.target.files[0];
                this.files[key] = f ? {
                    name: f.name,
                    size: f.size,
                    ok: /\.(xlsx|xls|csv)$/i.test(f.name) && f.size <= 10485760
                } : null;
            },
            fmt(b) { return b > 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(b / 1024)) + ' KB' },
            get allValid() { return Object.values(this.files).every(f => f && f.ok) }
        }" @submit="busy = true">
        @csrf

        <x-admin.card>
            <div class="divide-y divide-slate-100">


                <div class="space-y-6 p-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span
                                class="grid h-6 w-6 place-items-center rounded-full bg-violet-600 text-[10px] font-bold text-white">1</span>
                            <h3 class="text-sm font-semibold text-slate-900">Upload template</h3>
                        </div>
                        <p class="mt-1 pl-8 text-xs text-slate-400">Upload ketiga file template. Format: .xlsx, .xls,
                            atau .csv. Maksimal 10 MB per file.</p>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-3">
                        @php
                            $templates = [
                                'template' => [
                                    'label' => 'Template',
                                    'hint' => 'Template kosong untuk pelanggan isi data produk',
                                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z',
                                    'accent' => 'blue',
                                ],
                                'marketplace' => [
                                    'label' => 'Template Marketplace',
                                    'hint' => 'Template khusus format marketplace',
                                    'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m4-9l2 9',
                                    'accent' => 'emerald',
                                ],
                                'result' => [
                                    'label' => 'Template Wadah Hasil',
                                    'hint' => 'Template untuk menampilkan hasil proses',
                                    'icon' =>
                                        'M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6M3 21h18M5 21V7a2 2 0 012-2h10a2 2 0 012 2v14',
                                    'accent' => 'amber',
                                ],
                            ];
                            $accentColors = [
                                'blue' => 'from-blue-500 to-indigo-500 shadow-blue-500/30',
                                'emerald' => 'from-emerald-500 to-teal-500 shadow-emerald-500/30',
                                'amber' => 'from-amber-500 to-orange-500 shadow-amber-500/30',
                            ];
                        @endphp

                        @foreach ($templates as $key => $meta)
                            <div>
                                <label
                                    class="group relative block cursor-pointer overflow-hidden rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50/50 p-4 transition-all duration-200 hover:border-blue-400 hover:bg-blue-50/30">
                                    <input type="file" name="files[{{ $key }}]" accept=".xlsx,.xls,.csv"
                                        required class="sr-only" @change="pick('{{ $key }}', $event)">

                                    <div class="flex flex-col items-center text-center">
                                        <div
                                            class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br text-white shadow-lg {{ $accentColors[$meta['accent']] }}">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7"
                                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <path d="{{ $meta['icon'] }}" />
                                            </svg>
                                        </div>
                                        <h4 class="mt-3 text-sm font-semibold text-slate-900">{{ $meta['label'] }}</h4>
                                        <p class="mt-1 text-xs leading-5 text-slate-500">{{ $meta['hint'] }}</p>

                                        <div
                                            class="mt-3 flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 transition-colors group-hover:bg-blue-50 group-hover:text-blue-700 group-hover:ring-blue-200">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <span x-show="!files.{{ $key }}">Pilih file</span>
                                            <span x-cloak x-show="files.{{ $key }}">Ganti file</span>
                                        </div>
                                    </div>

                                    <template x-if="files.{{ $key }}">
                                        <div x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            class="mt-4 rounded-xl border p-3"
                                            :class="files.{{ $key }}.ok ? 'border-emerald-200 bg-emerald-50' :
                                                'border-rose-200 bg-rose-50'">
                                            <div class="flex items-start gap-2">
                                                <svg x-show="files.{{ $key }}.ok"
                                                    class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" fill="none"
                                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <svg x-cloak x-show="!files.{{ $key }}.ok"
                                                    class="mt-0.5 h-4 w-4 shrink-0 text-rose-600" fill="none"
                                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-xs font-medium"
                                                        :class="files.{{ $key }}.ok ? 'text-emerald-900' :
                                                            'text-rose-900'"
                                                        x-text="files.{{ $key }}.name"></p>
                                                    <p class="text-xs"
                                                        :class="files.{{ $key }}.ok ? 'text-emerald-600' :
                                                            'text-rose-600'"
                                                        x-text="fmt(files.{{ $key }}.size)"></p>
                                                    <p x-cloak x-show="!files.{{ $key }}.ok"
                                                        class="mt-0.5 text-xs text-rose-600">Format tidak valid atau
                                                        ukuran > 10 MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    @error('files.' . $key)
                                        <p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                    @enderror
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-4 p-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span
                                class="grid h-6 w-6 place-items-center rounded-full bg-slate-400 text-[10px] font-bold text-white">2</span>
                            <h3 class="text-sm font-semibold text-slate-900">Informasi tambahan</h3>
                            <span
                                class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">Opsional</span>
                        </div>
                        <p class="mt-1 pl-8 text-xs text-slate-400">Tambahkan catatan atau informasi versi template.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <x-admin.input label="Versi template" hint="opsional" name="version" id="version"
                                :value="old('version')" placeholder="v1.0 atau 2024-10" />
                        </div>
                        <div>
                            <x-admin.input label="Tanggal kadaluarsa" hint="opsional" name="expires_at" id="expires_at"
                                type="date" :value="old('expires_at')" />
                        </div>
                    </div>
                    <div>
                        <x-admin.textarea label="Catatan" hint="opsional" name="notes" id="notes"
                            :rows="3"
                            placeholder="Catatan tentang template ini...">{{ old('notes') }}</x-admin.textarea>
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50/40 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-400">
                    <span x-show="!allValid">Pilih ketiga file yang valid untuk melanjutkan.</span>
                    <span x-cloak x-show="allValid">Ketiga template siap untuk disimpan dan tersedia bagi
                        pelanggan.</span>
                </p>
                <div class="flex gap-2">
                    <x-admin.button variant="secondary" :href="url('/admin')">Batal</x-admin.button>
                    <x-admin.button type="submit" x-bind:disabled="!allValid || busy">
                        <svg x-cloak x-show="busy" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>
                        <span x-show="!busy">Simpan template</span>
                        <span x-cloak x-show="busy">Mengunggah…</span>
                    </x-admin.button>
                </div>
            </div>
        </x-admin.card>
    </form>

    <section class="mt-8 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-slate-900">Template yang tersedia</h2>
                <p class="text-xs text-slate-400">Semua template yang pernah diupload untuk pelanggan.</p>
            </div>
        </div>

        @forelse (($templateSets ?? []) as $set)
            <x-admin.card>
                <div class="p-6">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-semibold text-slate-900">
                                    {{ $set->customer->name ?? 'Project #' . $set->id }}</h3>
                                <x-admin.badge :status="$set->status ?? 'active'" />
                            </div>
                            <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-400">
                                <span>{{ optional($set->created_at)->format('d M Y H:i') }}</span>
                                @if ($set->marketplace)
                                    <span>·</span>
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="1.7"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m4-9l2 9" />
                                        </svg>
                                        {{ ucfirst($set->marketplace) }}
                                    </span>
                                @endif
                                @if ($set->version)
                                    <span>·</span>
                                    <span>{{ $set->version }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($set->notes)
                        <p class="mt-3 text-sm text-slate-600">{{ $set->notes }}</p>
                    @endif

                    <div class="mt-4 grid gap-3 sm:grid-cols-3">
                        @foreach (['template' => 'Template', 'marketplace' => 'Marketplace', 'result' => 'Wadah Hasil'] as $key => $label)
                            @if ($set->{$key . '_path'})
                                <div
                                    class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-semibold text-slate-700">{{ $label }}
                                        </p>
                                        <p class="text-xs text-slate-400">{{ $set->{$key . '_size'} ?? '—' }}</p>
                                    </div>
                                    <div class="flex shrink-0 gap-1">
                                        <x-admin.button variant="ghost" size="sm" :href="url('/admin/uploads/' . $set->id . '/download/' . $key)"
                                            title="Download" class="!p-1.5">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </x-admin.button>
                                        <x-admin.button variant="ghost" size="sm" type="button" title="Ganti"
                                            class="!p-1.5">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </x-admin.button>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="flex items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50/30 px-3 py-2.5">
                                    <p class="text-xs text-slate-400">{{ $label }} belum tersedia</p>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
                        <x-admin.button variant="secondary" size="sm" type="button"
                            x-on:click="ask(@js(url('/admin/uploads/' . $set->id . '/toggle')), 'PATCH', 'Ubah status template set ini?')">
                            {{ ($set->status ?? 'active') === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                        </x-admin.button>
                        <x-admin.button variant="danger" size="sm" type="button"
                            x-on:click="ask(@js(url('/admin/uploads/' . $set->id)), 'DELETE', 'Hapus template set ini? Tindakan tidak bisa dibatalkan.', 'Hapus')">
                            Hapus
                        </x-admin.button>
                        @if ($set->expires_at)
                            <span class="ml-auto text-xs text-slate-400">Kadaluarsa:
                                {{ optional($set->expires_at)->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>
            </x-admin.card>
        @empty
            <x-admin.card>
                <x-admin.empty-state title="Belum ada template"
                    description="Upload template pertama untuk menyediakan file Excel bagi pelanggan." icon="file">
                    <x-slot:action>
                        <x-admin.button variant="secondary"
                            onclick="window.scrollTo({top:0,behavior:'smooth'})">Upload Template</x-admin.button>
                    </x-slot:action>
                </x-admin.empty-state>
            </x-admin.card>
        @endforelse
    </section>
</x-admin.layout>
