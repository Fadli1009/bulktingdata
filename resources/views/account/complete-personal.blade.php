<x-auth.layout title="Lengkapi Data Personal — OmniSync Commerce">
    <div class="mb-6">
        <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0" /></svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Lengkapi Data Personal</h1>
        <p class="mt-1 text-sm text-slate-500">Isi data berikut untuk menyelesaikan pengaturan akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('account.complete') }}" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="mb-1.5 block text-xs font-semibold text-slate-700">Nama Lengkap</label>
            <input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="mb-1.5 block text-xs font-semibold text-slate-700">Email</label>
            <input id="email" value="{{ auth()->user()->email }}" disabled class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500">
        </div>
        <div>
            <label for="phone" class="mb-1.5 block text-xs font-semibold text-slate-700">Nomor Telepon</label>
            <input id="phone" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="birth_date" class="mb-1.5 block text-xs font-semibold text-slate-700">Tanggal Lahir</label>
            <input id="birth_date" name="birth_date" type="date" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="address" class="mb-1.5 block text-xs font-semibold text-slate-700">Alamat</label>
            <textarea id="address" name="address" rows="3" placeholder="Alamat lengkap Anda" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30">Simpan & Lanjutkan</button>
    </form>
</x-auth.layout>
