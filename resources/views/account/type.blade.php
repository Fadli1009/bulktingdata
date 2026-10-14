<x-auth.layout title="Pilih Tipe Akun — OmniSync Commerce">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Pilih Tipe Akun Anda</h1>
        <p class="text-sm text-slate-500 mt-2">Pilihan ini menentukan pengalaman & fitur yang Anda dapatkan.</p>
    </div>

    <form method="POST" action="{{ route('account.type') ?? '/account/type' }}" x-data="{ selected: null }" class="space-y-4">
        @csrf

        <label @click="selected = 'personal'"
            :class="selected === 'personal' ? 'border-blue-600 ring-2 ring-blue-500/30 bg-blue-50/40' : 'border-slate-200 hover:border-blue-300 hover:shadow-lg'"
            class="group block cursor-pointer rounded-2xl border bg-white p-6 transition-all duration-300">
            <input type="radio" name="account_type" value="personal" class="sr-only" x-model="selected">
            <div class="flex items-center gap-4">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white"
                    :class="selected === 'personal' && 'bg-blue-600 text-white'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0" />
                    </svg>
                </span>
                <div class="flex-1 text-left">
                    <p class="text-base font-bold text-slate-900">Akun Personal</p>
                    <p class="mt-0.5 text-xs leading-relaxed text-slate-500">Untuk individu, admin, atau pengelola tunggal yang ingin mencoba platform.</p>
                </div>
            </div>
            <div x-show="selected === 'personal'" x-transition class="mt-4 flex items-center justify-center gap-2 rounded-lg bg-blue-100 py-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                <span class="text-xs font-semibold text-blue-700">Dipilih</span>
            </div>
        </label>

        <label @click="selected = 'bisnis'"
            :class="selected === 'bisnis' ? 'border-blue-600 ring-2 ring-blue-500/30 bg-blue-50/40' : 'border-slate-200 hover:border-blue-300 hover:shadow-lg'"
            class="group block cursor-pointer rounded-2xl border bg-white p-6 transition-all duration-300">
            <input type="radio" name="account_type" value="bisnis" class="sr-only" x-model="selected">
            <div class="flex items-center gap-4">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-indigo-50 text-indigo-600 transition group-hover:bg-indigo-600 group-hover:text-white"
                    :class="selected === 'bisnis' && 'bg-indigo-600 text-white'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" />
                    </svg>
                </span>
                <div class="flex-1 text-left">
                    <p class="text-base font-bold text-slate-900">Akun Bisnis</p>
                    <p class="mt-0.5 text-xs leading-relaxed text-slate-500">Untuk seller / merchant marketplace dengan operasi multi-channel & tim.</p>
                </div>
            </div>
            <div x-show="selected === 'bisnis'" x-transition class="mt-4 flex items-center justify-center gap-2 rounded-lg bg-indigo-100 py-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                <span class="text-xs font-semibold text-indigo-700">Dipilih</span>
            </div>
        </label>

        <button type="submit" :disabled="!selected"
            class="mt-2 w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
            Lanjutkan
        </button>
    </form>

    @error('account_type')
        <p class="mt-3 text-sm text-rose-600">{{ $message }}</p>
    @enderror
</x-auth.layout>
