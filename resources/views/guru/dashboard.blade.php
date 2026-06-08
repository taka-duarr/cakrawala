<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Dashboard Guru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Banner -->
            <div class="bg-green-600 rounded-xl shadow-lg p-8 text-white flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h1>
                    <p class="text-green-100">Kelola misi dan pantau perkembangan siswa dari sini.</p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-24 h-24 text-green-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm flex items-center p-6">
                    <div class="p-4 bg-yellow-100 text-yellow-600 rounded-lg mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Menunggu Persetujuan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $pendingMissions->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm flex items-center p-6">
                    <div class="p-4 bg-blue-100 text-blue-600 rounded-lg mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Siswa</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $siswas->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Persetujuan Misi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Misi Menunggu Persetujuan</h3>
                        <p class="text-sm text-gray-500">Siswa yang sudah mengirim bukti penyelesaian misi.</p>
                    </div>
                    @if($pendingMissions->count() > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ $pendingMissions->count() }} menunggu
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    @forelse($pendingMissions as $mission)
                    <div class="flex items-center justify-between p-4 border border-yellow-200 bg-yellow-50 rounded-lg mb-3">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-yellow-200 rounded-full flex items-center justify-center font-bold text-yellow-800">
                                {{ substr($mission->student->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $mission->student->name }}</p>
                                <p class="text-sm text-gray-500">Misi: <span class="font-medium text-gray-700">{{ $mission->title }}</span> · +{{ $mission->points_reward }} Poin</p>
                                @if($mission->pivot->proof_url)
                                    <a href="{{ $mission->pivot->proof_url }}" target="_blank" class="text-xs text-blue-600 underline">Lihat Bukti →</a>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('guru.mission.approve', [$mission->student->id, $mission->id]) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Setujui
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="font-medium">Tidak ada misi yang menunggu persetujuan.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Peringkat Siswa -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800">Peringkat Siswa</h3>
                    <p class="text-sm text-gray-500">Daftar semua siswa berdasarkan poin kebaikan.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="uk-table uk-table-divider uk-table-hover mb-0">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 text-center">Rank</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Level</th>
                                <th class="text-right">Poin Kebaikan</th>
                                <th class="text-right">Poin Pelanggaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $siswa)
                            <tr>
                                <td class="text-center font-bold text-gray-500">{{ $index + 1 }}</td>
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-700 text-sm">
                                            {{ substr($siswa->name, 0, 1) }}
                                        </div>
                                        <span class="font-semibold">{{ $siswa->name }}</span>
                                    </div>
                                </td>
                                <td class="text-gray-600">{{ $siswa->class_name ?? '-' }}</td>
                                <td>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        {{ $siswa->current_level ?? 'Pemula' }}
                                    </span>
                                </td>
                                <td class="text-right font-bold text-green-600">{{ $siswa->points_kebaikan }}</td>
                                <td class="text-right font-bold text-red-500">{{ $siswa->points_pelanggaran }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">Belum ada data siswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
