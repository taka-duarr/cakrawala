<section>
    <header>
        <h2 class="text-lg font-black text-slate-950 uppercase tracking-tight">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">
            {{ __("Perbarui informasi profil akun dan alamat email Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-black text-slate-950 uppercase tracking-wider text-xs mb-1.5 block" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-xs font-bold text-rose-600 uppercase tracking-wide" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="font-black text-slate-950 uppercase tracking-wider text-xs mb-1.5 block" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-xs font-bold text-rose-600 uppercase tracking-wide" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide mt-2 text-slate-700">
                        {{ __('Alamat email Anda belum terverifikasi.') }}

                        <button form="send-verification" class="underline text-xs font-black text-slate-950 hover:text-slate-800 rounded-md focus:outline-none">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-xs text-emerald-700 uppercase tracking-wider">
                            {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black px-5 py-2.5 rounded-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
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
