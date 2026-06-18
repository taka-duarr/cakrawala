<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Kasir — {{ auth()->user()->name }}</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="bg-white border-2 border-slate-950 rounded-2xl px-4 py-2.5 text-center shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-[9px] text-slate-400 font-black block uppercase tracking-wider">Saldo Poin</span>
                    <strong class="text-xl text-slate-950 font-black block mt-0.5">{{ number_format(auth()->user()->points) }} Pts</strong>
                </div>
                <div class="bg-[#E4FF1A]/20 border-2 border-slate-950 rounded-2xl px-4 py-2.5 text-center hidden sm:block shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-[9px] text-slate-400 font-black block uppercase tracking-wider">Transaksi Hari Ini</span>
                    <strong class="text-xl text-slate-950 font-black block mt-0.5">{{ number_format($todayPoints) }} Pts</strong>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100/40 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div id="flash-success" class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 mb-6 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 px-4 py-3 rounded-xl text-xs font-black mb-6 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

                <!-- ═══ KIRI: KATALOG BARANG ═══ -->
                <div class="xl:col-span-3 space-y-4 order-2 xl:order-1">
                    <!-- Search + Title -->
                    <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Menu Barang</h3>
                            <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 text-[10px] font-black px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">{{ $items->count() }} Item Aktif</span>
                        </div>
                        <input type="text" id="item-search" placeholder="Cari barang..." oninput="filterItems(this.value)"
                            class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:ring-0 focus:border-slate-950 placeholder-slate-400">
                    </div>

                    <!-- Item Grid -->
                    @if($items->isEmpty())
                        <div class="bg-white rounded-3xl border-4 border-slate-950 p-12 text-center shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] space-y-4">
                            <div class="w-16 h-16 bg-[#FFEAEA] border-2 border-slate-950 rounded-2xl flex items-center justify-center mx-auto shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: cart; ratio: 1.5"></span>
                            </div>
                            <div>
                                <p class="text-slate-950 font-black text-sm uppercase tracking-tight">Katalog masih kosong</p>
                                <p class="text-slate-400 text-xs mt-1 font-bold uppercase tracking-wider">Silakan tambahkan barang di menu Kelola Katalog.</p>
                            </div>
                        </div>
                    @else
                        <div id="item-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($items as $item)
                                <button
                                    onclick="addToCart({{ $item->id }}, {{ json_encode($item->name) }}, {{ $item->points_price }})"
                                    data-item-name="{{ strtolower($item->name) }}"
                                    class="item-card bg-white rounded-2xl border-2 border-slate-950 p-4 text-left shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-1 hover:translate-x-0.5 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all duration-200 cursor-pointer group">
                                    <div class="w-10 h-10 bg-[#E4FF1A] border-2 border-slate-950 rounded-xl flex items-center justify-center mb-3 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                        <span uk-icon="icon: bag; ratio: 0.85"></span>
                                    </div>
                                    <div class="text-xs font-black text-slate-950 leading-tight mb-2 uppercase tracking-tight">{{ $item->name }}</div>
                                    <div class="text-slate-950 font-black text-xs uppercase tracking-wider">
                                        <span class="bg-[#E4FF1A] border-2 border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">{{ $item->points_price }} Pts</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Histori Hari Ini -->
                    <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] mt-4">
                        <div class="p-4 border-b-4 border-slate-950 bg-[#E4FF1A]/10 flex items-center justify-between">
                            <h3 class="text-sm font-black text-slate-950 uppercase tracking-tight">Transaksi Hari Ini</h3>
                            <span class="bg-white border-2 border-slate-950 text-slate-950 text-[10px] font-black px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">{{ $todayTransactions->where('status','paid')->count() }}</span>
                        </div>
                        <div class="divide-y divide-slate-950 max-h-48 overflow-y-auto bg-white">
                            @forelse($todayTransactions->where('status','paid') as $tx)
                                <div class="px-4 py-3.5 flex justify-between items-center hover:bg-slate-50 transition">
                                    <div>
                                        <p class="text-xs font-black text-slate-950 uppercase tracking-tight">{{ $tx->item_name }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $tx->student->name ?? '—' }} · {{ $tx->paid_at->format('H:i') }} WIB</p>
                                    </div>
                                    <span class="font-black text-xs text-emerald-700 bg-[#EAFCEF] border-2 border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">+{{ $tx->points_amount }} Pts</span>
                                </div>
                            @empty
                                <div class="py-12 text-center text-xs text-slate-400 font-bold uppercase tracking-wider italic">Belum ada transaksi hari ini</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- ═══ KANAN: KERANJANG ═══ -->
                <div class="xl:col-span-2 order-1 xl:order-2">
                    <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden sticky top-20 z-10 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                        <!-- Cart Header -->
                        <div class="bg-[#E4FF1A] p-5 text-slate-950 border-b-4 border-slate-950">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span uk-icon="icon: cart; ratio: 0.95"></span>
                                    <h3 class="font-black text-base uppercase tracking-tight">Keranjang</h3>
                                </div>
                                <button onclick="clearCart()" class="text-slate-950 hover:text-slate-800 text-xs font-black uppercase tracking-wider hover:underline">Kosongkan</button>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div id="cart-items" class="divide-y divide-slate-950 min-h-[200px] max-h-[380px] overflow-y-auto bg-white">
                            <div id="cart-empty" class="flex flex-col items-center justify-center py-12 text-slate-400 space-y-2">
                                <span uk-icon="icon: cart; ratio: 1.25"></span>
                                <p class="text-xs font-bold uppercase tracking-wider">Keranjang kosong</p>
                                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400/80">Pilih barang dari menu</p>
                            </div>
                        </div>

                        <!-- Total & Generate QR -->
                        <div class="border-t-4 border-slate-950 p-5 space-y-4 bg-white">
                            <div class="flex justify-between items-center border-b-2 border-slate-950 pb-3">
                                <span class="text-xs font-black text-slate-950 uppercase tracking-wider">Total Bayar</span>
                                <div class="text-right">
                                    <span id="cart-total-display" class="text-3xl font-black text-slate-950">0</span>
                                    <span class="text-xs font-black text-slate-950 uppercase tracking-wider"> Pts</span>
                                </div>
                            </div>

                            <!-- QR Display Section -->
                            <div id="qr-section" class="hidden">
                                <div class="bg-[#EAFCEF] border-2 border-slate-950 rounded-2xl p-4 flex flex-col items-center space-y-3 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                    <div class="inline-flex items-center space-x-2 bg-white border-2 border-slate-950 text-emerald-800 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping inline-block"></span>
                                        <span>QR Aktif — Menunggu Presensi</span>
                                    </div>
                                    <div id="qr-code-display" class="border-2 border-slate-950 p-1.5 bg-white rounded-xl"></div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider text-center">Tunjukkan QR ini ke siswa untuk di-scan</p>
                                    <button onclick="cancelQr()" class="text-xs font-black text-rose-600 hover:text-slate-950 transition uppercase tracking-wider">Batalkan QR</button>
                                </div>
                            </div>

                            <!-- Generate QR Button -->
                            <button id="btn-generate-qr" onclick="generateQr()" disabled
                                class="w-full bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white disabled:bg-slate-200 disabled:text-slate-400 disabled:border-transparent disabled:shadow-none border-2 border-slate-950 font-black py-4 rounded-2xl transition shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] text-sm flex items-center justify-center space-x-2 uppercase tracking-wider">
                                <span uk-icon="icon: qrcode; ratio: 0.9"></span>
                                <span>Generate QR Bayar</span>
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- QRCode.js -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        // ─── Cart State ───────────────────────────────────────────────
        let cart = {}; // { id: { id, name, qty, points_price } }
        let qrInstance = null;
        let qrActive = false;

        // ─── Add to Cart ──────────────────────────────────────────────
        function addToCart(id, name, price) {
            if (cart[id]) {
                cart[id].qty++;
            } else {
                cart[id] = { id, name, qty: 1, points_price: price };
            }
            renderCart();
        }

        // ─── Render Cart ──────────────────────────────────────────────
        function renderCart() {
            const container = document.getElementById('cart-items');
            const emptyMsg = document.getElementById('cart-empty');
            const total = Object.values(cart).reduce((s, i) => s + i.qty * i.points_price, 0);

            document.getElementById('cart-total-display').textContent = total.toLocaleString('id-ID');

            const hasItems = Object.keys(cart).length > 0;
            document.getElementById('btn-generate-qr').disabled = !hasItems || qrActive;

            if (!hasItems) {
                container.innerHTML = `<div id="cart-empty" class="flex flex-col items-center justify-center py-12 text-slate-400 space-y-2">
                    <span uk-icon="icon: cart; ratio: 1.25"></span>
                    <p class="text-xs font-bold uppercase tracking-wider">Keranjang kosong</p>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400/80">Pilih barang dari menu</p>
                </div>`;
                return;
            }

            // Remove empty msg
            const empty = document.getElementById('cart-empty');
            if (empty) empty.remove();

            // Clear and re-render
            container.innerHTML = '';
            Object.values(cart).forEach(item => {
                const subtotal = item.qty * item.points_price;
                const div = document.createElement('div');
                div.className = 'flex items-center gap-3 px-4 py-3.5 border-b border-slate-950 bg-white hover:bg-slate-55/30 transition';
                div.innerHTML = `
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-slate-950 uppercase tracking-tight truncate">${item.name}</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">${item.points_price} Pts × ${item.qty}</p>
                    </div>
                    <div class="flex items-center space-x-1.5 shrink-0">
                        <button onclick="changeQty(${item.id}, -1)" class="w-6 h-6 rounded bg-white border-2 border-slate-950 text-slate-950 font-black text-sm flex items-center justify-center transition shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-none">−</button>
                        <span class="text-xs font-black text-slate-950 w-5 text-center">${item.qty}</span>
                        <button onclick="changeQty(${item.id}, 1)" class="w-6 h-6 rounded bg-white border-2 border-slate-950 text-slate-950 font-black text-sm flex items-center justify-center transition shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-none">+</button>
                    </div>
                    <div class="text-xs font-black text-slate-950 w-16 text-right shrink-0 uppercase tracking-wider">${subtotal} Pts</div>
                `;
                container.appendChild(div);
            });
        }

        function changeQty(id, delta) {
            if (!cart[id]) return;
            cart[id].qty += delta;
            if (cart[id].qty <= 0) delete cart[id];
            renderCart();
        }

        // Clear Cart
        function clearCart() {
            cart = {};
            renderCart();
            hideQr();
        }

        // Filter Items
        function filterItems(q) {
            document.querySelectorAll('.item-card').forEach(card => {
                const match = card.dataset.itemName.includes(q.toLowerCase());
                card.style.display = match ? '' : 'none';
            });
        }

        // Generate QR
        function generateQr() {
            if (Object.keys(cart).length === 0) return;

            const cartArray = Object.values(cart).map(i => ({ id: i.id, qty: i.qty }));
            const total = Object.values(cart).reduce((s, i) => s + i.qty * i.points_price, 0);

            document.getElementById('btn-generate-qr').disabled = true;
            document.getElementById('btn-generate-qr').innerHTML = `<span>Memproses...</span>`;

            fetch('{{ route('toko.qr.generate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ cart: cartArray, total_points: total })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showQr(data.pay_url, data.token);
                } else {
                    alert('Gagal generate QR. Coba lagi.');
                    resetGenerateBtn();
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan koneksi.');
                resetGenerateBtn();
            });
        }

        let checkStatusInterval = null;

        function showQr(url, token) {
            qrActive = true;
            const section = document.getElementById('qr-section');
            section.classList.remove('hidden');

            const display = document.getElementById('qr-code-display');
            display.innerHTML = '';

            qrInstance = new QRCode(display, {
                text: url,
                width: 180,
                height: 180,
                colorDark: '#0f172a',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });

            document.getElementById('btn-generate-qr').innerHTML = `<span>QR Aktif</span>`;
            document.getElementById('btn-generate-qr').disabled = true;

            // Start polling status
            if (checkStatusInterval) clearInterval(checkStatusInterval);
            checkStatusInterval = setInterval(() => {
                fetch(`/toko/qr/${token}/status`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'paid') {
                            clearInterval(checkStatusInterval);
                            hideQr();
                            clearCart();
                            Swal.fire({
                                title: 'Pembayaran Berhasil!',
                                text: 'Poin telah berhasil ditambahkan ke saldo Anda.',
                                icon: 'success',
                                confirmButtonText: 'Tutup',
                                timer: 3000
                            }).then(() => {
                                window.location.reload();
                            });
                        } else if (data.status === 'expired' || data.status === 'not_found') {
                            clearInterval(checkStatusInterval);
                            hideQr();
                            Swal.fire('Kedaluwarsa', 'QR Code telah kedaluwarsa atau dibatalkan.', 'warning');
                        }
                    }).catch(console.error);
            }, 3000);
        }

        function hideQr() {
            qrActive = false;
            if (checkStatusInterval) clearInterval(checkStatusInterval);
            document.getElementById('qr-section').classList.add('hidden');
            document.getElementById('qr-code-display').innerHTML = '';
            qrInstance = null;
            resetGenerateBtn();
        }

        function resetGenerateBtn() {
            const btn = document.getElementById('btn-generate-qr');
            btn.disabled = Object.keys(cart).length === 0;
            btn.innerHTML = `<span uk-icon="icon: qrcode; ratio: 0.9"></span><span>Generate QR Bayar</span>`;
        }

        function cancelQr() {
            fetch('{{ route('toko.qr.cancel') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => hideQr());
        }

        // Auto-dismiss flash
        const flash = document.getElementById('flash-success');
        if (flash) setTimeout(() => flash.style.display = 'none', 3000);
    </script>
</x-app-layout>
