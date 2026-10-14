<x-auth.layout title="Lengkapi Data Bisnis — OmniSync Commerce">
    <div class="mb-6">
        <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" /></svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Lengkapi Data Bisnis</h1>
        <p class="mt-1 text-sm text-slate-500">Siapkan profil seller Anda untuk mulai mengelola marketplace.</p>
    </div>

    <form method="POST" action="{{ route('account.complete') }}" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="mb-1.5 block text-xs font-semibold text-slate-700">Nama Toko / Bisnis</label>
            <input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required placeholder="Contoh: Toko Maju Jaya" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="business_category" class="mb-1.5 block text-xs font-semibold text-slate-700">Kategori Bisnis</label>
            <select id="business_category" name="business_category" class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Pilih kategori</option>
                <option value="fashion">Fashion</option>
                <option value="elektronik">Elektronik</option>
                <option value="kecantikan">Kecantikan</option>
                <option value="rumah_tangga">Rumah Tangga</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
        <div>
            <label for="phone" class="mb-1.5 block text-xs font-semibold text-slate-700">Nomor Telepon Bisnis</label>
            <input id="phone" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="tax_id" class="mb-1.5 block text-xs font-semibold text-slate-700">NPWP</label>
            <input id="tax_id" name="tax_id" value="{{ old('tax_id') }}" placeholder="Opsional" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="address" class="mb-1.5 block text-xs font-semibold text-slate-700">Alamat Kantor / Gudang</label>
            <textarea id="address" name="address" rows="3" placeholder="Alamat lengkap bisnis Anda" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="pic_name" class="mb-1.5 block text-xs font-semibold text-slate-700">Nama PIC</label>
                <input id="pic_name" name="pic_name" placeholder="Nama penanggung jawab" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="website" class="mb-1.5 block text-xs font-semibold text-slate-700">Website</label>
                <input id="website" name="website" placeholder="https://..." class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30">Simpan & Masuk Dashboard</button>
    </form>
</x-auth.layout>
