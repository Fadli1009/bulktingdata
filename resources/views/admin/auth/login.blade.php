<x-auth.layout title="Login Admin — OmniSync Commerce">
    <div x-data="{ step: 'email', email: '', code: '', loading: false, error: '', message: '', resendCooldown: 0 }">

        @if ($message = session('error'))
            <div class="mb-4 p-3 rounded-lg bg-rose-50 text-sm text-rose-700 border border-rose-200">
                {{ $message }}
            </div>
        @endif

        <div class="mb-6">
            <span
                class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700 mb-3">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z" />
                </svg>
                Portal Admin
            </span>
            <h1 class="text-2xl font-bold text-slate-900">Masuk Portal Admin</h1>
            <p class="text-sm text-slate-500 mt-1">Autentikasi khusus pengelola sistem OmniSync.</p>
        </div>

        <div x-show="step === 'email'" x-transition class="space-y-4">
            <form
                @submit.prevent="async () => {
                loading = true; error = ''; message = '';
                try {
                    const res = await fetch('{{ route('admin.auth.otp.send') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('[name=csrf-token]').content },
                        body: JSON.stringify({ email })
                    });
                    if (!res.ok) throw await res.json();
                    step = 'otp';
                    message = 'Kode OTP telah dikirim ke ' + email;
                    resendCooldown = 60;
                    const timer = setInterval(() => { resendCooldown > 0 ? resendCooldown-- : clearInterval(timer) }, 1000);
                } catch (err) {
                    error = err.errors?.email?.[0] || err.message || 'Terjadi kesalahan';
                } finally {
                    loading = false;
                }
            }"
                class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Admin</label>
                    <input type="email" x-model="email" placeholder="nama@example.com" required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <button type="submit" :disabled="loading || !email"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading">Kirim Kode OTP</span>
                    <span x-show="loading">Mengirim...</span>
                </button>
            </form>

            <div class="relative flex items-center gap-3 my-4">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-xs font-medium text-slate-500">atau</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            <a href="{{ route('admin.auth.google') }}"
                class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 transition hover:bg-slate-50 focus:outline-none focus-visible:ring-4 focus-visible:ring-slate-400/30">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032 c0-3.331,2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.461,2.268,15.366,1,12.545,1 C6.777,1,2,5.777,2,11.545c0,5.768,4.777,10.545,10.545,10.545c6.032,0,10.545-4.513,10.545-10.545 C23.091,11.52,23.027,10.88,22.959,10.239H12.545z" />
                </svg>
                Masuk dengan Google
            </a>
        </div>

        <div x-show="step === 'otp'" x-transition class="space-y-4">
            <div class="text-center mb-4">
                <p class="text-sm text-slate-600">Kode terkirim ke</p>
                <p class="text-sm font-semibold text-slate-900"><span x-text="email"></span></p>
            </div>

            <form
                @submit.prevent="async () => {
                loading = true; error = '';
                try {
                    const res = await fetch('{{ route('admin.auth.otp.verify') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('[name=csrf-token]').content },
                        body: JSON.stringify({ email, code })
                    });
                    if (!res.ok) {
                        const err = await res.json();
                        throw err;
                    }
                    window.location.href = '/admin';
                } catch (err) {
                    error = err.errors?.code?.[0] || err.message || 'Terjadi kesalahan';
                } finally {
                    loading = false;
                }
            }"
                class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Masukkan 6 Digit Kode OTP</label>
                    <input type="text" x-model="code" inputmode="numeric" maxlength="6" placeholder="000000"
                        required
                        class="w-full text-center text-2xl font-bold rounded-lg border border-slate-300 px-3.5 py-3 placeholder:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <button type="submit" :disabled="loading || code.length !== 6"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading">Verifikasi</span>
                    <span x-show="loading">Memverifikasi...</span>
                </button>
            </form>

            <button @click="step = 'email'; code = ''; message = ''; error = ''"
                class="w-full text-sm font-medium text-blue-600 hover:text-blue-700">
                ← Gunakan Email Lain
            </button>
        </div>

        <div x-show="error" class="mt-4 p-3 rounded-lg bg-rose-50 text-sm text-rose-700 border border-rose-200"
            x-text="error"></div>
        <div x-show="message"
            class="mt-4 p-3 rounded-lg bg-emerald-50 text-sm text-emerald-700 border border-emerald-200"
            x-text="message"></div>

        <p class="mt-6 text-center text-xs text-slate-500">
            Bukan admin? <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Masuk
                sebagai pengguna</a>
        </p>
    </div>
</x-auth.layout>
