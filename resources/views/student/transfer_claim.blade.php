<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            Konfirmasi Terima Poin
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 text-center relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-50 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-50 rounded-full blur-2xl"></div>
                
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-blue-200">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>

                    <h3 class="text-xl font-bold text-slate-800 mb-2">Terima Poin dari Teman</h3>
                    <p class="text-slate-500 mb-8">Anda akan menerima poin dari <strong class="text-slate-800">{{ $sender->name }}</strong>.</p>
                    
                    <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100 inline-block w-full max-w-xs shadow-inner">
                        <p class="text-sm text-slate-500 font-bold mb-1">Jumlah Poin</p>
                        <h2 class="text-4xl font-black text-blue-600">{{ $transfer['amount'] }} <span class="text-lg text-slate-400">Poin</span></h2>
                    </div>

                    <form action="{{ route('student.transfer.claim.process', $token) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-md shadow-blue-200 transition text-lg mb-4">
                            Konfirmasi & Terima Poin
                        </button>
                    </form>

                    <a href="{{ route('student.dompet') }}" class="inline-block w-full bg-white hover:bg-slate-50 text-slate-600 font-bold py-3.5 rounded-2xl border-2 border-slate-200 transition">
                        Batal
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
