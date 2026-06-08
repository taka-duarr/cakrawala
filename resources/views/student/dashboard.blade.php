<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Dashboard Siswa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center space-x-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Hero Card Siswa -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-8 text-white">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-blue-200 text-sm font-medium mb-1">Selamat datang kembali,</p>
                        <h1 class="text-3xl font-bold mb-1">{{ $user->name }}</h1>
                        <p class="text-blue-100">Kelas {{ $user->class_name ?? '-' }}</p>
                    </div>
                    <div class="mt-6 md:mt-0 text-right">
                        <p class="text-blue-200 text-sm mb-1">Level saat ini</p>
                        <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur rounded-full text-lg font-bold border border-white/30">
                            ⭐ {{ $user->current_level ?? 'Pemula' }}
                        </span>
                    </div>
                </div>
                <!-- Progress Bar Poin -->
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-blue-200 mb-1">
                        <span>Poin Kebaikan: <strong class="text-white">{{ $user->points_kebaikan }}</strong></span>
                        <span>Poin Pelanggaran: <strong class="text-red-300">{{ $user->points_pelanggaran }}</strong></span>
                    </div>
                    <div class="h-3 bg-white/20 rounded-full overflow-hidden">
                        @php
                            $levelMax = match($user->current_level ?? 'Pemula') {
                                'Pemula' => 100,
                                'Berkembang' => 500,
                                'Unggul' => 1500,
                                'Teladan' => 3000,
                                default => 9999,
                            };
                            $pct = min(100, ($user->points_kebaikan / $levelMax) * 100);
                        @endphp
                        <div class="h-full bg-white rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="text-xs text-blue-200 mt-1">{{ $user->points_kebaikan }} / {{ $levelMax }} poin untuk naik level</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Misi Aktif / Yang Sedang Dikerjakan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800">Misi Aktif Saya</h3>
                        <p class="text-sm text-gray-500">Misi yang sedang kamu kerjakan.</p>
                    </div>
                    <div class="p-6 space-y-3">
                        @forelse($activeMissions as $mission)
                        <div class="p-4 border rounded-lg {{ $mission->pivot->status === 'pending_approval' ? 'border-yellow-200 bg-yellow-50' : 'border-blue-200 bg-blue-50' }}">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $mission->title }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $mission->description }}</p>
                                    <span class="inline-block mt-2 text-xs font-bold text-green-700 bg-green-100 px-2 py-1 rounded-full">+{{ $mission->points_reward }} Poin</span>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full ml-2 whitespace-nowrap {{ $mission->pivot->status === 'pending_approval' ? 'bg-yellow-200 text-yellow-800' : 'bg-blue-200 text-blue-800' }}">
                                    {{ $mission->pivot->status === 'pending_approval' ? '⏳ Menunggu' : '🔄 Aktif' }}
                                </span>
                            </div>
                            @if($mission->pivot->status === 'taken')
                            <form method="POST" action="{{ route('student.mission.submit', $mission->id) }}" class="mt-3 flex space-x-2">
                                @csrf
                                <input type="url" name="proof_url" placeholder="Link bukti (URL)" required
                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                                    Kirim
                                </button>
                            </form>
                            @endif
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <p>Kamu belum mengambil misi apapun.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Riwayat Poin -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800">Riwayat Poin</h3>
                        <p class="text-sm text-gray-500">10 aktivitas poin terakhir.</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($pointHistory as $history)
                        <div class="flex items-center justify-between px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center {{ $history->type === 'kebaikan' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    @if($history->type === 'kebaikan')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $history->source }}</p>
                                    <p class="text-xs text-gray-500">{{ $history->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="font-bold {{ $history->type === 'kebaikan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $history->type === 'kebaikan' ? '+' : '-' }}{{ $history->points }} Pts
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400 px-6">
                            <p>Belum ada riwayat poin.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quest Board (Misi Tersedia) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800">⚡ Quest Board</h3>
                    <p class="text-sm text-gray-500">Ambil misi dan raih poin kebaikan!</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($availableMissions as $mission)
                    <div class="border rounded-xl p-5 hover:shadow-md transition {{ in_array($mission->id, $takenMissionIds) ? 'bg-gray-50 border-gray-200 opacity-70' : 'border-blue-100 bg-blue-50/30' }}">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-700 capitalize">{{ $mission->type ?? 'Umum' }}</span>
                            <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-1 rounded-full">+{{ $mission->points_reward }} Pts</span>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1">{{ $mission->title }}</h4>
                        <p class="text-sm text-gray-600 mb-4">{{ $mission->description }}</p>
                        @if(in_array($mission->id, $takenMissionIds))
                            <button disabled class="w-full py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-semibold cursor-not-allowed">
                                ✓ Sudah Diambil
                            </button>
                        @else
                            <form method="POST" action="{{ route('student.mission.take', $mission->id) }}">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">
                                    Ambil Misi
                                </button>
                            </form>
                        @endif
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-12 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <p class="font-medium">Belum ada misi tersedia. Tunggu guru menambahkan misi baru!</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
