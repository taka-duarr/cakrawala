<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black text-slate-950 uppercase tracking-tight">Masuk ke Akun</h2>
        <p class="text-xs text-slate-500 font-semibold mt-1">Masukkan email & password untuk mengakses dashboard Anda.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Masuk...'; }">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="block text-xs font-black text-slate-950 uppercase tracking-wider">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:bg-[#EAF5FF] placeholder-slate-400 font-semibold shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] focus:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] transition-all duration-150"
                placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <div class="flex justify-between items-center">
                <label for="password" class="block text-xs font-black text-slate-950 uppercase tracking-wider">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-black text-indigo-650 hover:text-indigo-800 transition uppercase tracking-wider" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:bg-[#EAF5FF] placeholder-slate-400 font-semibold shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] focus:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] transition-all duration-150"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" 
                    class="rounded-md border-2 border-slate-950 text-slate-950 shadow-sm focus:ring-slate-950 w-4 h-4 cursor-pointer focus:ring-offset-0">
                <span class="ms-2.5 text-xs font-bold text-slate-700">Ingat Saya</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3 bg-slate-950 hover:bg-[#E4FF1A] hover:text-slate-950 text-white rounded-xl text-xs font-black uppercase tracking-wider border-2 border-slate-950 transition-all duration-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,0.15)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5">
            Masuk ke Dashboard
        </button>
    </form>
</x-guest-layout>
