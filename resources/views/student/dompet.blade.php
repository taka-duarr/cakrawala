<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            Dompet Poin
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] font-bold uppercase text-xs" role="alert">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 px-4 py-3 rounded-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] font-bold uppercase text-xs" role="alert">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Bagian Atas: Info Poin & Tombol Scan -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Info Poin -->
                <div class="bg-[#E4FF1A] border-4 border-slate-950 rounded-3xl p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] text-slate-950 flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-slate-800 font-extrabold uppercase text-[10px] tracking-wider">Total Saldo Poin Anda</p>
                            <h3 class="text-4xl font-black mt-1.5">{{ number_format($user->points) }} <span class="text-lg font-bold">Poin</span></h3>
                        </div>
                        <div class="w-12 h-12 bg-white border-2 border-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                            <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    
                    <div class="relative z-10 mt-8 pt-4 border-t-2 border-slate-950">
                        <p class="text-slate-800 text-xs font-bold uppercase tracking-wide">Gunakan poin untuk berbelanja di kantin atau menukarkan reward!</p>
                    </div>
                </div>

                <!-- Scanner QR -->
                <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] flex flex-col justify-center">
                    <div id="scan-standby" class="text-center space-y-4">
                        <div class="w-16 h-16 bg-[#EAFCEF] border-2 border-slate-950 text-emerald-700 rounded-2xl flex items-center justify-center mx-auto shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black uppercase tracking-tight text-slate-950">Bayar di Toko / Kantin</h3>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-1">Scan QR Code dari penjual untuk melakukan pembayaran.</p>
                        </div>
                        <button onclick="startScanner()" id="btn-start" class="w-full bg-[#E4FF1A] text-slate-950 hover:bg-slate-950 hover:text-white border-2 border-slate-950 font-black py-3.5 rounded-2xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] transition active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase text-xs tracking-wider">
                            Mulai Scan QR
                        </button>
                    </div>

                    <div id="scan-active" class="hidden flex-col items-center">
                        <div class="w-full max-w-xs rounded-2xl overflow-hidden border-4 border-slate-950 aspect-square relative bg-slate-900 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]" id="reader-container">
                            <div id="reader" class="w-full h-full"></div>
                        </div>
                        <div id="scan-result" style="display: none;" class="mt-4 w-full p-3.5 rounded-xl bg-[#EAFCEF] text-emerald-700 font-black text-sm flex items-center justify-center space-x-2 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                            <span class="animate-spin inline-block w-4 h-4 border-2 border-emerald-500 border-t-transparent rounded-full"></span>
                            <span>QR Terdeteksi! Memproses...</span>
                        </div>
                        <button onclick="stopScanner()" id="btn-stop" class="mt-4 text-xs font-black uppercase tracking-wider text-rose-600 hover:text-rose-800 hover:underline transition">
                            Batalkan Scan
                        </button>
                    </div>
                </div>

                <!-- Transfer Poin (Generate QR) -->
                <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] flex flex-col justify-center">
                    <div class="text-center space-y-4">
                        <div class="w-16 h-16 bg-[#FFEAEA] border-2 border-slate-950 text-rose-700 rounded-2xl flex items-center justify-center mx-auto shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black uppercase tracking-tight text-slate-950">Kirim Poin</h3>
                            <p class="text-xs text-slate-500 mt-1 font-bold uppercase tracking-wider">Buat QR Code berisi nominal poin untuk di-scan oleh teman Anda.</p>
                        </div>
                        <button uk-toggle="target: #modal-transfer" class="w-full bg-[#E4FF1A] text-slate-950 hover:bg-slate-950 hover:text-white border-2 border-slate-950 font-black py-3.5 rounded-2xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] transition active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase text-xs tracking-wider">
                            Buat QR Transfer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bagian Bawah: Riwayat -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Riwayat Transaksi Toko -->
                <div class="bg-white rounded-3xl border-4 border-slate-950 shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] overflow-hidden">
                    <div class="p-5 border-b-2 border-slate-950 bg-slate-50/50">
                        <h3 class="font-black text-slate-950 flex items-center space-x-2 uppercase tracking-tight">
                            <span uk-icon="icon: bag; ratio: 0.9"></span>
                            <span>Riwayat Belanja</span>
                        </h3>
                    </div>
                    <div class="divide-y-2 divide-slate-950 max-h-[400px] overflow-y-auto">
                        @forelse($shopTransactions as $tx)
                            <div class="p-4 flex items-center justify-between hover:bg-slate-100 transition">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-[#E4FF1A] border-2 border-slate-950 rounded-xl flex items-center justify-center shrink-0 shadow-[1.5px_1.5px_0px_0px_rgba(15,23,42,1)]">
                                        <span class="font-black text-slate-950 text-sm">{{ substr($tx->shop->name ?? '?', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-950 uppercase tracking-tight">{{ $tx->item_name }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-1">
                                            {{ $tx->shop->name ?? 'Toko' }} · {{ $tx->created_at->translatedFormat('d M, H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-rose-700">-{{ $tx->points_amount }}</p>
                                    @if($tx->status === 'paid')
                                        <span class="text-[9px] font-black uppercase text-emerald-700 bg-[#EAFCEF] border border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">Lunas</span>
                                    @else
                                        <span class="text-[9px] font-black uppercase text-rose-700 bg-[#FFEAEA] border border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">Gagal/Expired</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-xs font-bold uppercase tracking-wider">
                                Belum ada riwayat belanja.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Riwayat Perubahan Poin -->
                <div class="bg-white rounded-3xl border-4 border-slate-950 shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] overflow-hidden">
                    <div class="p-5 border-b-2 border-slate-950 bg-slate-50/50">
                        <h3 class="font-black text-slate-950 flex items-center space-x-2 uppercase tracking-tight">
                            <span uk-icon="icon: history; ratio: 0.9"></span>
                            <span>Aktivitas Poin</span>
                        </h3>
                    </div>
                    <div class="divide-y-2 divide-slate-950 max-h-[400px] overflow-y-auto">
                        @forelse($pointHistory as $history)
                            @php
                                $isPositive = $history->points > 0;
                            @endphp
                            <div class="p-4 flex items-start space-x-3 hover:bg-slate-100 transition">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] {{ $isPositive ? 'bg-[#EAFCEF] text-emerald-700' : 'bg-[#FFEAEA] text-rose-700' }}">
                                    @if($isPositive)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-950">{{ $history->description }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">{{ $history->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-xs font-black {{ $isPositive ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $isPositive ? '+' : '' }}{{ $history->points }}
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-xs font-bold uppercase tracking-wider">
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
        <div class="uk-modal-dialog border-4 border-slate-950 rounded-3xl p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
            <div id="transfer-form-section">
                <h3 class="text-lg font-black uppercase text-slate-950 tracking-tight">Transfer Poin</h3>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-6">Masukkan jumlah poin yang ingin dikirimkan ke teman Anda.</p>
                <input type="number" id="transfer-amount" class="w-full border-2 border-slate-950 rounded-xl mb-4 p-3 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] focus:ring-slate-950 focus:border-slate-950" placeholder="Contoh: 500">
                <button id="btn-generate-transfer" onclick="generateTransfer()" class="w-full bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 font-black py-3 rounded-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition text-xs uppercase tracking-wider">Generate QR Code</button>
            </div>
            <div id="transfer-qr-section" class="hidden flex-col items-center">
                <h3 class="text-lg font-black uppercase text-slate-950 tracking-tight">Scan QR Transfer</h3>
                <div id="transfer-qr-display" class="my-6 p-4 border-2 border-slate-950 rounded-2xl bg-white shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]"></div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-800">Poin: <span id="transfer-display-amount" class="font-black text-slate-950"></span></p>
                <button onclick="cancelTransfer()" class="mt-6 text-rose-600 font-black uppercase tracking-wider text-xs hover:underline">Batal</button>
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
                        colorDark: '#0f172a',
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
