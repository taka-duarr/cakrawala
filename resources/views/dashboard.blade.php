<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] rounded-3xl p-8 text-center max-w-2xl mx-auto space-y-5">
                <div class="mx-auto w-16 h-16 bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 rounded-2xl flex items-center justify-center shadow-[2.5px_2.5px_0px_0px_rgba(15,23,42,1)] mb-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h3>
                <p class="text-xs text-slate-700 font-semibold leading-relaxed">
                    Anda berhasil masuk ke platform **CAKRAWALA**. Namun, akun Anda saat ini belum dikonfigurasi dengan peran khusus (Siswa, Guru, Wali Kelas, atau Orang Tua).
                </p>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">
                    Silakan hubungi administrator sekolah untuk melakukan penugasan peran dan kelas agar Anda dapat mulai mengakses seluruh fitur platform.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
