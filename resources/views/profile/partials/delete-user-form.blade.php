<section class="space-y-6">
    <header>
        <h2 class="text-lg font-black text-slate-950 uppercase tracking-tight">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-rose-500 hover:bg-slate-950 text-white border-2 border-slate-950 text-xs font-black px-5 py-2.5 rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider"
    >{{ __('Hapus Akun') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-black text-slate-950 uppercase tracking-tight">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1 leading-normal">
                {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Harap masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Kata Sandi') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Kata Sandi') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-xs font-bold text-rose-600 uppercase tracking-wide" />
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="border-2 border-slate-950 text-slate-950 hover:bg-slate-100 text-xs font-black px-4 py-2 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-none uppercase tracking-wider">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button class="bg-rose-500 hover:bg-slate-950 text-white border-2 border-slate-950 text-xs font-black px-5 py-2 rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                    {{ __('Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
