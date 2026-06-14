<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Kasir — {{ auth()->user()->name }}</h2>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-2 text-center">
                    <span class="text-[10px] text-emerald-500 font-bold block uppercase">Saldo Poin</span>
                    <strong class="text-xl text-emerald-700 font-black">{{ auth()->user()->points }}</strong>
                </div>
                <div class="bg-violet-50 border border-violet-100 rounded-xl px-4 py-2 text-center hidden sm:block">
                    <span class="text-[10px] text-violet-400 font-bold block uppercase">Transaksi Hari Ini</span>
                    <strong class="text-xl text-violet-700 font-black">{{ $todayPoints }}</strong>
                    <span class="text-[10px] text-violet-400 font-medium"> Poin</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div id="flash-success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2 mb-4">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-semibold mb-4">
                    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

                <!-- ═══ KIRI: KATALOG BARANG ═══ -->
                <div class="xl:col-span-3 space-y-4 order-2 xl:order-1">
                    <!-- Search + Title -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-base font-bold text-slate-800">Menu Barang</h3>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $items->count() }} item aktif</span>
                        </div>
                        <input type="text" id="item-search" placeholder="Cari barang..." oninput="filterItems(this.value)"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    </div>

                    <!-- Item Grid -->
                    @if($items->isEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
                            <div class="w-16 h-16 bg-violet-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <p class="text-slate-500 font-semibold text-sm">Katalog masih kosong</p>
                            <p class="text-slate-400 text-xs mt-1">Klik "Kelola Katalog" di atas untuk menambahkan barang.</p>
                        </div>
                    @else
                        <div id="item-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($items as $item)
                                <button
                                    onclick="addToCart({{ $item->id }}, {{ json_encode($item->name) }}, {{ $item->points_price }})"
                                    data-item-name="{{ strtolower($item->name) }}"
                                    class="item-card bg-white rounded-2xl shadow-sm border border-slate-100 p-4 text-left hover:border-violet-300 hover:shadow-md hover:scale-[1.02] transition-all duration-200 active:scale-95 cursor-pointer group">
                                    <div class="w-10 h-10 bg-violet-50 group-hover:bg-violet-100 rounded-xl flex items-center justify-center mb-3 transition">
                                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 11H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <div class="text-sm font-bold text-slate-800 leading-tight mb-1">{{ $item->name }}</div>
                                    <div class="text-violet-600 font-black text-sm">{{ $item->points_price }} <span class="text-xs font-bold text-violet-400">Poin</span></div>
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Histori Hari Ini -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mt-4">
                        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-700">Transaksi Hari Ini</h3>
                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold">{{ $todayTransactions->where('status','paid')->count() }}</span>
                        </div>
                        <div class="divide-y divide-slate-100 max-h-48 overflow-y-auto">
                            @forelse($todayTransactions->where('status','paid') as $tx)
                                <div class="px-4 py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-xs font-semibold text-slate-800">{{ $tx->item_name }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $tx->student->name ?? '—' }} · {{ $tx->paid_at->format('H:i') }}</p>
                                    </div>
                                    <span class="text-sm font-extrabold text-emerald-600">+{{ $tx->points_amount }}</span>
                                </div>
                            @empty
                                <div class="py-8 text-center text-xs text-slate-400 font-medium">Belum ada transaksi hari ini</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- ═══ KANAN: KERANJANG ═══ -->
                <div class="xl:col-span-2 order-1 xl:order-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-20 z-10">
                        <!-- Cart Header -->
                        <div class="bg-gradient-to-r from-violet-700 to-purple-800 p-5 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <h3 class="font-bold text-base">Keranjang</h3>
                                </div>
                                <button onclick="clearCart()" class="text-violet-200 hover:text-white text-xs font-semibold transition">Kosongkan</button>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div id="cart-items" class="divide-y divide-slate-100 min-h-[200px] max-h-[380px] overflow-y-auto">
                            <div id="cart-empty" class="flex flex-col items-center justify-center py-12 text-slate-400">
                                <svg class="w-10 h-10 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-xs font-medium">Keranjang kosong</p>
                                <p class="text-[10px] mt-0.5">Pilih barang dari menu</p>
                            </div>
                        </div>

                        <!-- Total & Generate QR -->
                        <div class="border-t border-slate-100 p-5 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-600">Total Bayar</span>
                                <div class="text-right">
                                    <span id="cart-total-display" class="text-3xl font-black text-violet-700">0</span>
                                    <span class="text-sm font-bold text-violet-400"> Poin</span>
                                </div>
                            </div>

                            <!-- QR Display Section -->
                            <div id="qr-section" class="hidden">
                                <div class="bg-violet-50 border-2 border-dashed border-violet-200 rounded-2xl p-4 flex flex-col items-center space-y-3">
                                    <div class="inline-flex items-center space-x-2 bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                                        <span>QR Aktif — Menunggu Pembayaran</span>
                                    </div>
                                    <div id="qr-code-display"></div>
                                    <p class="text-[10px] text-slate-400 font-medium text-center">Tunjukkan QR ini ke siswa untuk di-scan</p>
                                    <button onclick="cancelQr()" class="text-xs font-bold text-rose-500 hover:text-rose-700 transition">Batalkan QR</button>
                                </div>
                            </div>

                            <!-- Generate QR Button -->
                            <button id="btn-generate-qr" onclick="generateQr()" disabled
                                class="w-full bg-violet-600 hover:bg-violet-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white font-black py-4 rounded-2xl transition shadow-lg shadow-violet-100 text-sm flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
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
                if (!document.getElementById('cart-empty')) {
                    container.innerHTML = `<div id="cart-empty" class="flex flex-col items-center justify-center py-12 text-slate-400">
                        <p class="text-xs font-medium">Keranjang kosong</p>
                    </div>`;
                }
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
                div.className = 'flex items-center gap-3 px-4 py-3';
                div.innerHTML = `
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-800 truncate">${item.name}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">${item.points_price} Poin × ${item.qty}</p>
                    </div>
                    <div class="flex items-center space-x-1.5 shrink-0">
                        <button onclick="changeQty(${item.id}, -1)" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm flex items-center justify-center transition">−</button>
                        <span class="text-sm font-bold text-slate-800 w-5 text-center">${item.qty}</span>
                        <button onclick="changeQty(${item.id}, 1)" class="w-6 h-6 rounded-lg bg-violet-100 hover:bg-violet-200 text-violet-700 font-bold text-sm flex items-center justify-center transition">+</button>
                    </div>
                    <div class="text-xs font-extrabold text-violet-700 w-16 text-right shrink-0">${subtotal} Poin</div>
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

        function clearCart() {
            cart = {};
            renderCart();
            hideQr();
        }

        // ─── Filter Items ─────────────────────────────────────────────
        function filterItems(q) {
            document.querySelectorAll('.item-card').forEach(card => {
                const match = card.dataset.itemName.includes(q.toLowerCase());
                card.style.display = match ? '' : 'none';
            });
        }

        // ─── Generate QR ──────────────────────────────────────────────
        function generateQr() {
            if (Object.keys(cart).length === 0) return;

            const cartArray = Object.values(cart).map(i => ({ id: i.id, qty: i.qty }));
            const total = Object.values(cart).reduce((s, i) => s + i.qty * i.points_price, 0);

            document.getElementById('btn-generate-qr').disabled = true;
            document.getElementById('btn-generate-qr').innerHTML = `<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span><span>Memproses...</span>`;

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
                colorDark: '#5b21b6',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });

            document.getElementById('btn-generate-qr').innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <span>QR Aktif</span>`;
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
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg><span>Generate QR Bayar</span>`;
        }

        function cancelQr() {
            fetch('{{ route('toko.qr.cancel') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => hideQr());
        }

        // ─── Auto-dismiss flash ───────────────────────────────────────
        const flash = document.getElementById('flash-success');
        if (flash) setTimeout(() => flash.style.display = 'none', 3000);
    </script>
</x-app-layout>
