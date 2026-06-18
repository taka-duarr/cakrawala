<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran — Cakrawala</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 bg-slate-100/40">
    <div class="w-full max-w-sm">

        @if(isset($expired) && $expired)
            <!-- QR Expired State -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 text-center space-y-5 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="w-16 h-16 bg-[#FFEAEA] border-2 border-slate-950 rounded-2xl flex items-center justify-center mx-auto shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <svg class="w-8 h-8 text-rose-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-950 uppercase tracking-tight">QR Kadaluarsa</h1>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1.5 leading-relaxed">QR Code ini sudah tidak berlaku. Minta penjual untuk membuat ulang QR Code baru.</p>
                </div>
                <a href="{{ route('student.dashboard') }}"
                    class="block text-center w-full bg-white hover:bg-slate-50 text-slate-950 border-2 border-slate-950 font-black py-3 rounded-2xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-xs uppercase tracking-wider">
                    Kembali ke Dashboard
                </a>
            </div>

        @elseif(!auth()->check())
            <!-- Not logged in -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 text-center space-y-5 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="w-16 h-16 bg-[#E4FF1A]/20 border-2 border-slate-950 rounded-2xl flex items-center justify-center mx-auto shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <svg class="w-8 h-8 text-slate-950" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-950 uppercase tracking-tight">Login Diperlukan</h1>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1.5 leading-relaxed">Silakan login dengan akun siswa Anda untuk melanjutkan pembayaran.</p>
                </div>
                <a href="{{ route('login') }}?redirect={{ url()->current() }}"
                    class="block text-center w-full bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 font-black py-3 rounded-2xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-xs uppercase tracking-wider">
                    Login Sekarang
                </a>
            </div>

        @else
            <!-- Confirmation State -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <!-- Header -->
                <div class="bg-[#E4FF1A] p-6 text-center text-slate-950 border-b-4 border-slate-950">
                    <div class="w-12 h-12 bg-white border-2 border-slate-950 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-wider">Pembayaran ke</p>
                    <h1 class="text-xl font-black mt-0.5 uppercase tracking-tight">{{ $tx->shop->name }}</h1>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-5">
                    <!-- Item Info -->
                    <div class="bg-slate-50 border-2 border-slate-950 rounded-2xl p-4 space-y-2.5 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        @if($tx->cart_items && count($tx->cart_items) > 0)
                            @foreach($tx->cart_items as $cartItem)
                                <div class="flex justify-between items-center text-xs font-black text-slate-950 uppercase tracking-tight">
                                    <span>{{ $cartItem['name'] }} <span class="text-slate-400 font-bold uppercase tracking-wider">×{{ $cartItem['qty'] }}</span></span>
                                    <span>{{ $cartItem['subtotal'] }} Pts</span>
                                </div>
                            @endforeach
                            <div class="border-t-2 border-slate-950 pt-3 mt-3 flex justify-between items-center">
                                <span class="text-xs text-slate-400 font-black uppercase tracking-wider">Total Bayar</span>
                                <span class="text-2xl font-black text-slate-950">{{ number_format($tx->points_amount) }}<span class="text-xs font-black bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] ml-1 uppercase tracking-wider">Pts</span></span>
                            </div>
                        @else
                            <div class="flex justify-between items-center text-xs font-black text-slate-950 uppercase tracking-tight">
                                <span class="text-slate-400 font-bold uppercase tracking-wider">Nama Barang</span>
                                <span>{{ $tx->item_name }}</span>
                            </div>
                            <div class="flex justify-between items-center border-t-2 border-slate-950 pt-3 mt-3">
                                <span class="text-xs text-slate-400 font-black uppercase tracking-wider">Total Bayar</span>
                                <span class="text-2xl font-black text-slate-950">{{ number_format($tx->points_amount) }}<span class="text-xs font-black bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] ml-1 uppercase tracking-wider">Pts</span></span>
                            </div>
                        @endif
                    </div>

                    <!-- Student Balance Info -->
                    <div class="bg-[#E4FF1A]/20 border-2 border-slate-950 rounded-2xl p-4 flex justify-between items-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <div>
                            <p class="text-[9px] text-slate-500 font-black uppercase tracking-wider">Saldo Poin Anda</p>
                            <p class="text-xl font-black text-slate-950 mt-0.5">{{ number_format($student->points) }} Pts</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] text-slate-500 font-black uppercase tracking-wider">Sisa Setelah Bayar</p>
                            @php $remaining = $student->points - $tx->points_amount; @endphp
                            <p class="text-xl font-black mt-0.5 {{ $remaining < 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                                {{ number_format($remaining) }} Pts
                            </p>
                        </div>
                    </div>

                    @if($student->points < $tx->points_amount)
                        <div class="bg-[#FFEAEA] border-2 border-slate-950 rounded-xl p-3 text-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                            <p class="text-xs font-black text-rose-800 uppercase tracking-wider">⚠️ Saldo poin Anda tidak mencukupi!</p>
                        </div>
                    @endif

                    <!-- Session Info -->
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Login sebagai: <strong class="text-slate-950">{{ $student->name }}</strong></p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4 pt-2">
                        @if($student->points >= $tx->points_amount)
                            <form action="{{ route('student.pay.process', $tx->qr_token) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 font-black py-4 rounded-2xl transition shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                                    <span>Bayar {{ number_format($tx->points_amount) }} Pts Sekarang</span>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('student.dashboard') }}"
                            class="block text-center w-full bg-white hover:bg-slate-50 text-slate-950 border-2 border-slate-950 font-black py-3 rounded-2xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-xs uppercase tracking-wider">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</body>
</html>
