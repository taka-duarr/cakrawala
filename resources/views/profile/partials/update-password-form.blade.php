<section>
    <header>
        <h2 class="text-lg font-black text-slate-950 uppercase tracking-tight">
            {{ __('Perbarui Kata Sandi') }}
        </h2>

        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">
            {{ __('Pastikan akun Anda menggunakan kata sandi acak yang panjang untuk menjaga keamanan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" class="font-black text-slate-950 uppercase tracking-wider text-xs mb-1.5 block" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs font-bold text-rose-600 uppercase tracking-wide" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" class="font-black text-slate-950 uppercase tracking-wider text-xs mb-1.5 block" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs font-bold text-rose-600 uppercase tracking-wide" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" class="font-black text-slate-950 uppercase tracking-wider text-xs mb-1.5 block" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs font-bold text-rose-600 uppercase tracking-wide" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black px-5 py-2.5 rounded-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-black text-emerald-800 bg-[#EAFCEF] border-2 border-slate-950 px-2.5 py-1 rounded-lg shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
