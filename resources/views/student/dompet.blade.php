<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            Dompet Poin
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-xl relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-xl relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Bagian Atas: Info Poin & Tombol Scan -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Info Poin -->
                <div class="bg-gradient-to-br from-indigo-600 to-violet-800 rounded-3xl p-6 shadow-lg shadow-indigo-200 text-white flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-indigo-400/20 rounded-full blur-xl"></div>
                    
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-indigo-100 font-medium text-sm">Total Saldo Poin Anda</p>
                            <h3 class="text-4xl font-black mt-1">{{ number_format($user->points) }} <span class="text-lg font-bold text-indigo-200">Poin</span></h3>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    
                    <div class="relative z-10 mt-6 pt-4 border-t border-indigo-500/30">
                        <p class="text-indigo-100 text-xs font-medium">Gunakan poin Anda untuk berbelanja di kantin atau menukarkan reward!</p>
                    </div>
                </div>

                <!-- Scanner QR -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 flex flex-col justify-center">
                    <div id="scan-standby" class="text-center space-y-4">
                        <div class="w-16 h-16 bg-violet-50 text-violet-600 rounded-2xl flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Bayar di Toko / Kantin</h3>
                            <p class="text-xs text-slate-500 mt-1">Scan QR Code dari penjual untuk melakukan pembayaran.</p>
                        </div>
                        <button onclick="startScanner()" id="btn-start" class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-3.5 rounded-2xl shadow-md shadow-violet-100 transition">
                            Mulai Scan QR
                        </button>
                    </div>

                    <div id="scan-active" class="hidden flex-col items-center">
                        <div class="w-full max-w-xs rounded-2xl overflow-hidden border-4 border-violet-100 aspect-square relative bg-slate-900" id="reader-container">
                            <div id="reader" class="w-full h-full"></div>
                        </div>
                        <div id="scan-result" class="hidden mt-4 w-full p-3 rounded-xl bg-emerald-50 text-emerald-700 font-bold text-sm flex items-center justify-center space-x-2 border border-emerald-100">
                            <span class="animate-spin inline-block w-4 h-4 border-2 border-emerald-500 border-t-transparent rounded-full"></span>
                            <span>QR Terdeteksi! Memproses...</span>
                        </div>
                        <button onclick="stopScanner()" id="btn-stop" class="mt-4 text-xs font-bold text-rose-500 hover:text-rose-700 transition">
                            Batalkan Scan
                        </button>
                    </div>
                </div>

                <!-- Transfer Poin (Generate QR) -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 flex flex-col justify-center">
                    <div class="text-center space-y-4">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Kirim Poin</h3>
                            <p class="text-xs text-slate-500 mt-1">Buat QR Code berisi nominal poin untuk di-scan oleh teman Anda.</p>
                        </div>
                        <button uk-toggle="target: #modal-transfer" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-md shadow-blue-100 transition">
                            Buat QR Transfer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bagian Bawah: Riwayat -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Riwayat Transaksi Toko -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center space-x-2">
                            <span uk-icon="icon: bag; ratio: 0.9"></span>
                            <span>Riwayat Belanja</span>
                        </h3>
                    </div>
                    <div class="divide-y divide-slate-100 max-h-[400px] overflow-y-auto">
                        @forelse($shopTransactions as $tx)
                            <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center shrink-0">
                                        <span class="font-bold text-slate-600 text-sm">{{ substr($tx->shop->name ?? '?', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $tx->item_name }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            {{ $tx->shop->name ?? 'Toko' }} · {{ $tx->created_at->translatedFormat('d M, H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-rose-600">-{{ $tx->points_amount }}</p>
                                    @if($tx->status === 'paid')
                                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Lunas</span>
                                    @else
                                        <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Gagal/Expired</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-sm font-medium">
                                Belum ada riwayat belanja.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Riwayat Perubahan Poin -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center space-x-2">
                            <span uk-icon="icon: history; ratio: 0.9"></span>
                            <span>Aktivitas Poin</span>
                        </h3>
                    </div>
                    <div class="divide-y divide-slate-100 max-h-[400px] overflow-y-auto">
                        @forelse($pointHistory as $history)
                            @php
                                $isPositive = $history->points > 0;
                            @endphp
                            <div class="p-4 flex items-start space-x-3 hover:bg-slate-50 transition">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $isPositive ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                    @if($isPositive)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800">{{ $history->description }}</p>
                                    <p class="text-[10px] text-slate-400 mt-1">{{ $history->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-sm font-black {{ $isPositive ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $isPositive ? '+' : '' }}{{ $history->points }}
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-sm font-medium">
                                Belum ada aktivitas poin.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Transfer -->
    <div id="modal-transfer" uk-modal>
        <div class="uk-modal-dialog rounded-3xl p-6">
            <div id="transfer-form-section">
                <h3 class="text-lg font-bold text-slate-800">Transfer Poin</h3>
                <p class="text-sm text-slate-500 mb-6">Masukkan jumlah poin yang ingin dikirimkan ke teman Anda.</p>
                <input type="number" id="transfer-amount" class="w-full border-slate-200 rounded-xl mb-4" placeholder="Contoh: 500">
                <button id="btn-generate-transfer" onclick="generateTransfer()" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl">Generate QR Code</button>
            </div>
            <div id="transfer-qr-section" class="hidden flex-col items-center">
                <h3 class="text-lg font-bold text-slate-800">Scan QR Transfer</h3>
                <div id="transfer-qr-display" class="my-6"></div>
                <p class="text-sm text-slate-600">Poin: <span id="transfer-display-amount" class="font-bold"></span></p>
                <button onclick="cancelTransfer()" class="mt-6 text-rose-500 font-bold">Batal</button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        let html5QrcodeScanner;
        let transferToken = null;
        let transferStatusInterval = null;

        function startScanner() {
            document.getElementById('scan-standby').classList.add('hidden');
            document.getElementById('scan-active').classList.remove('hidden');
            document.getElementById('scan-active').classList.add('flex');

            const html5QrCode = new Html5Qrcode("reader");
            html5QrcodeScanner = html5QrCode;

            html5QrCode.start(
                { facingMode: "environment" }, 
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    html5QrCode.stop().then(() => {
                        document.getElementById('reader-container').classList.add('hidden');
                        document.getElementById('btn-stop').classList.add('hidden');
                        document.getElementById('scan-result').classList.remove('hidden');
                        
                        if(decodedText.includes('/pay/') || decodedText.includes('/transfer/claim/')) {
                            try {
                                // Extract only the path so it uses the phone's current IP connection
                                // instead of 127.0.0.1 from the laptop's QR code.
                                const urlObj = new URL(decodedText, window.location.origin);
                                window.location.href = urlObj.pathname + urlObj.search;
                            } catch(e) {
                                window.location.href = decodedText;
                            }
                        } else {
                            alert("QR Code tidak valid atau bukan dari Cakrawala!");
                            window.location.reload();
                        }
                    });
                },
                (errorMessage) => { /* ignore parse errors */ }
            ).catch((err) => {
                alert("Gagal mengakses kamera. Pastikan Anda memberikan izin kamera.");
                stopScanner();
            });
        }

        function stopScanner() {
            if(html5QrcodeScanner) {
                html5QrcodeScanner.stop().catch(e => console.error(e));
            }
            document.getElementById('scan-active').classList.add('hidden');
            document.getElementById('scan-active').classList.remove('flex');
            document.getElementById('scan-standby').classList.remove('hidden');
        }

        // --- Transfer QR Generation Logic ---
        function generateTransfer() {
            const amount = document.getElementById('transfer-amount').value;
            if (!amount || amount < 1) {
                alert('Masukkan nominal poin yang valid.');
                return;
            }

            const btn = document.getElementById('btn-generate-transfer');
            btn.disabled = true;
            btn.innerText = 'Memproses...';

            fetch('{{ route('student.transfer.generate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ amount: amount })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    transferToken = data.token;
                    
                    document.getElementById('transfer-form-section').classList.add('hidden');
                    document.getElementById('transfer-qr-section').classList.remove('hidden');
                    document.getElementById('transfer-qr-section').classList.add('flex');
                    document.getElementById('transfer-display-amount').innerText = amount;
                    
                    document.getElementById('transfer-qr-display').innerHTML = '';
                    new QRCode(document.getElementById('transfer-qr-display'), {
                        text: data.qr_url,
                        width: 200,
                        height: 200,
                        colorDark: '#2563eb',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.H
                    });

                    // Start polling
                    if (transferStatusInterval) clearInterval(transferStatusInterval);
                    transferStatusInterval = setInterval(() => {
                        fetch(`/student/transfer/status/${transferToken}`)
                            .then(r => r.json())
                            .then(res => {
                                if (res.status === 'claimed') {
                                    clearInterval(transferStatusInterval);
                                    UIkit.modal(document.getElementById('modal-transfer')).hide();
                                    alert('Transfer poin berhasil diterima oleh teman Anda!');
                                    window.location.reload();
                                } else if (res.status === 'not_found') {
                                    clearInterval(transferStatusInterval);
                                    UIkit.modal(document.getElementById('modal-transfer')).hide();
                                    alert('QR Code telah kadaluarsa atau dibatalkan.');
                                }
                            }).catch(console.error);
                    }, 3000);
                } else {
                    alert(data.message || 'Terjadi kesalahan.');
                    btn.disabled = false;
                    btn.innerText = 'Generate QR Code';
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan jaringan.');
                btn.disabled = false;
                btn.innerText = 'Generate QR Code';
            });
        }

        function cancelTransfer() {
            if (transferToken) {
                fetch(`/student/transfer/cancel/${transferToken}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
            }
            if (transferStatusInterval) clearInterval(transferStatusInterval);
            transferToken = null;
            
            document.getElementById('transfer-qr-section').classList.add('hidden');
            document.getElementById('transfer-qr-section').classList.remove('flex');
            document.getElementById('transfer-form-section').classList.remove('hidden');
            
            const btn = document.getElementById('btn-generate-transfer');
            btn.disabled = false;
            btn.innerText = 'Generate QR Code';
            document.getElementById('transfer-amount').value = '';
            UIkit.modal(document.getElementById('modal-transfer')).hide();
        }
    </script>
</x-app-layout>
