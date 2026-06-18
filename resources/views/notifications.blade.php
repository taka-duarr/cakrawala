<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight flex items-center gap-2.5 uppercase tracking-tight">
            <svg class="w-7 h-7 text-slate-950 inline-block" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path>
            </svg>
            <span>Notifikasi Saya</span>
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-100/30 min-h-screen">
        <div class="max-w-4xl mx-auto space-y-6">
            
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">Pusat Notifikasi</h3>
                        <p class="text-xs text-slate-400 mt-1 font-bold uppercase tracking-wider">Informasi dan pembaruan real-time mengenai aktivitas pembelajaran, poin kebaikan, dan lencana karakter Anda.</p>
                    </div>
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-slate-950 hover:bg-[#E4FF1A] hover:text-slate-950 text-white border-2 border-slate-950 rounded-xl text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all">
                            Tandai Semua Dibaca
                        </button>
                    </form>
                </div>

                <div class="space-y-5">
                    @forelse($notifications as $notification)
                    <div class="p-5 rounded-2xl border-2 border-slate-950 {{ $notification->is_unread ? 'bg-[#E4FF1A]/10 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]' : 'bg-white shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]' }} flex items-start space-x-4 relative transition hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5">
                        @if($notification->is_unread)
                        <div class="absolute top-4 right-4 w-2.5 h-2.5 bg-rose-500 rounded-full border border-slate-950 animate-pulse"></div>
                        @endif

                        <div class="w-10 h-10 rounded-xl {{ $notification->is_unread ? 'bg-[#E4FF1A]' : 'bg-slate-100' }} border-2 border-slate-950 flex items-center justify-center shadow-[1.5px_1.5px_0px_0px_rgba(15,23,42,1)] flex-shrink-0">
                            @switch($notification->icon)
                                @case('sparkles')
                                    <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.187.904zM18 7.5L17.25 11l-.75-3.5L13 7l3.5-.75L17.25 3l.75 3.5L21 7l-3 1.5z"></path></svg>
                                    @break
                                @case('star')
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.195-.39.736-.39.93 0l2.399 4.86 5.342.776c.433.063.606.592.293.898l-3.866 3.769 1.127 5.318c.092.433-.362.762-.75.558L12 17.15l-4.782 2.516c-.388.204-.842-.125-.75-.558l1.127-5.318-3.866-3.769c-.313-.306-.14-.835.293-.898l5.342-.776 2.399-4.86z"></path></svg>
                                    @break
                                @case('trophy')
                                    <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19.5 8.25c0-1.518-1.232-2.75-2.75-2.75h-.75V3H8v2.5h-.75C5.732 5.5 4.5 6.732 4.5 8.25v.75c0 1.518 1.232 2.75 2.75 2.75h.75M19.5 8.25v.75c0 1.518-1.232 2.75-2.75 2.75h-.75M9 21h6M12 15v6"></path></svg>
                                    @break
                                @case('gift')
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path></svg>
                                    @break
                                @case('warning')
                                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    @break
                                @case('bell')
                                    <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path></svg>
                                    @break
                                @default
                                    <svg class="w-5 h-5 text-slate-650" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"></path></svg>
                            @endswitch
                        </div>

                        <div class="space-y-1.5 flex-1 pr-4">
                            <div class="flex items-center space-x-2 text-[9px] font-bold uppercase tracking-wider text-slate-550">
                                <span class="bg-white text-slate-950 border border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] font-black uppercase text-[8px]">{{ $notification->category }}</span>
                                <span>·</span>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="font-black text-slate-950 text-xs uppercase tracking-tight leading-snug">{{ $notification->title }}</h4>
                            <p class="text-xs text-slate-700 font-bold uppercase tracking-wider leading-relaxed">{{ $notification->body }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider">Tidak ada notifikasi yang tersedia saat ini.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
