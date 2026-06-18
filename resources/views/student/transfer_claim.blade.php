<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            Konfirmasi Terima Poin
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 text-center relative overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                
                <div class="relative z-10 space-y-6">
                    <div class="w-16 h-16 bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center mx-auto shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>

                    <div>
                        <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">Terima Poin dari Teman</h3>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-1">Anda akan menerima poin dari <strong class="text-slate-950 font-black">{{ $sender->name }}</strong>.</p>
                    </div>
                    
                    <div class="bg-slate-50 rounded-2xl p-6 border-2 border-slate-950 inline-block w-full max-w-xs shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                        <p class="text-[10px] text-slate-500 font-black uppercase tracking-wider mb-1">Jumlah Poin</p>
                        <h2 class="text-4xl font-black text-slate-950">{{ $transfer['amount'] }} <span class="text-sm text-slate-500 font-black uppercase tracking-wider">Poin</span></h2>
                    </div>

                    <form action="{{ route('student.transfer.claim.process', $token) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 font-black uppercase py-4 rounded-2xl shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] transition-all duration-200 text-sm mb-4">
                            Konfirmasi & Terima Poin
                        </button>
                    </form>

                    <a href="{{ route('student.dompet') }}" class="inline-block w-full bg-white hover:bg-slate-100 text-slate-950 border-2 border-slate-950 font-black uppercase py-3.5 rounded-2xl shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] transition-all duration-200 text-xs">
                        Batal
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
