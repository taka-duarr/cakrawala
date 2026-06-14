<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran — Cakrawala</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 50%, #7c3aed 100%); min-height: 100vh; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-sm">

        @if(isset($expired) && $expired)
            <!-- QR Expired State -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 text-center space-y-4">
                <div class="w-16 h-16 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-black text-slate-800">QR Kadaluarsa</h1>
                <p class="text-sm text-slate-500 font-medium">QR Code ini sudah tidak berlaku. Minta penjual untuk generate ulang QR Code baru.</p>
                <a href="{{ route('student.dashboard') }}"
                    class="block w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-3 rounded-xl transition text-sm">
                    Kembali ke Dashboard
                </a>
            </div>

        @elseif(!auth()->check())
            <!-- Not logged in -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 text-center space-y-4">
                <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-black text-slate-800">Login Diperlukan</h1>
                <p class="text-sm text-slate-500 font-medium">Silakan login dengan akun siswa Anda untuk melanjutkan pembayaran.</p>
                <a href="{{ route('login') }}?redirect={{ url()->current() }}"
                    class="block w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-3 rounded-xl transition text-sm">
                    Login Sekarang
                </a>
            </div>

        @else
            <!-- Confirmation State -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-violet-700 to-purple-800 p-6 text-center text-white">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-[10px] text-violet-200 uppercase font-bold tracking-wider">Pembayaran ke</p>
                    <h1 class="text-xl font-black mt-0.5">{{ $tx->shop->name }}</h1>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-5">
                    <!-- Item Info -->
                    <div class="bg-slate-50 rounded-2xl p-4 space-y-2">
                        @if($tx->cart_items && count($tx->cart_items) > 0)
                            @foreach($tx->cart_items as $cartItem)
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-700 font-semibold">{{ $cartItem['name'] }} <span class="text-slate-400 font-medium">×{{ $cartItem['qty'] }}</span></span>
                                    <span class="font-bold text-slate-700">{{ $cartItem['subtotal'] }} Poin</span>
                                </div>
                            @endforeach
                            <div class="border-t border-slate-200 pt-2 mt-2 flex justify-between items-center">
                                <span class="text-xs text-slate-400 font-semibold">Total Bayar</span>
                                <span class="text-2xl font-black text-violet-700">{{ $tx->points_amount }} <span class="text-sm font-bold text-violet-400">Poin</span></span>
                            </div>
                        @else
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-400 font-semibold">Nama Barang</span>
                                <span class="text-sm font-bold text-slate-800">{{ $tx->item_name }}</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-slate-200 pt-2">
                                <span class="text-xs text-slate-400 font-semibold">Total Bayar</span>
                                <span class="text-2xl font-black text-violet-700">{{ $tx->points_amount }} <span class="text-sm font-bold text-violet-400">Poin</span></span>
                            </div>
                        @endif
                    </div>

                    <!-- Student Balance Info -->
                    <div class="bg-violet-50 border border-violet-100 rounded-2xl p-4 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] text-violet-400 font-bold uppercase tracking-wider">Saldo Poin Anda</p>
                            <p class="text-xl font-black text-violet-800 mt-0.5">{{ $student->points }} Poin</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-violet-400 font-bold uppercase tracking-wider">Sisa Setelah Bayar</p>
                            @php $remaining = $student->points - $tx->points_amount; @endphp
                            <p class="text-xl font-black mt-0.5 {{ $remaining < 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $remaining }} Poin
                            </p>
                        </div>
                    </div>

                    @if($student->points < $tx->points_amount)
                        <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 text-center">
                            <p class="text-xs font-bold text-rose-600">⚠️ Saldo poin Anda tidak mencukupi!</p>
                        </div>
                    @endif

                    <!-- Session Info -->
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 font-medium">Login sebagai: <strong class="text-slate-600">{{ $student->name }}</strong></p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        @if($student->points >= $tx->points_amount)
                            <form action="{{ route('student.pay.process', $tx->qr_token) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-violet-600 hover:bg-violet-700 active:scale-95 text-white font-black py-4 rounded-2xl transition shadow-lg shadow-violet-200 text-sm">
                                    ✅ Bayar {{ $tx->points_amount }} Poin Sekarang
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('student.dashboard') }}"
                            class="block text-center w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 rounded-2xl transition text-sm">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</body>
</html>
