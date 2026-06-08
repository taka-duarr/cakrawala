<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Banner -->
            <div class="bg-blue-600 rounded-xl shadow-lg p-8 text-white flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang, Admin!</h1>
                    <p class="text-blue-100">Berikut adalah ringkasan statistik keseluruhan dari platform CAKRAWALA.</p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-24 h-24 text-blue-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Siswa -->
                <div class="uk-card uk-card-default rounded-xl border border-gray-100 shadow-sm flex items-center p-6">
                    <div class="p-4 bg-blue-100 text-blue-600 rounded-lg mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Siswa Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                    </div>
                </div>

                <!-- Total Kebaikan -->
                <div class="uk-card uk-card-default rounded-xl border border-gray-100 shadow-sm flex items-center p-6">
                    <div class="p-4 bg-green-100 text-green-600 rounded-lg mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Poin Kebaikan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalKebaikan }}</p>
                    </div>
                </div>

                <!-- Total Pelanggaran -->
                <div class="uk-card uk-card-default rounded-xl border border-gray-100 shadow-sm flex items-center p-6">
                    <div class="p-4 bg-red-100 text-red-600 rounded-lg mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Poin Pelanggaran</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalPelanggaran }}</p>
                    </div>
                </div>
            </div>

            <!-- Kelas Ranking -->
            <div class="uk-card uk-card-default rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-xl font-bold text-gray-800">Peringkat Kelas</h3>
                    <p class="text-sm text-gray-500">Berdasarkan total poin kebaikan yang diraih siswa di setiap kelas.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="uk-table uk-table-divider uk-table-hover uk-table-middle mb-0">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-16 text-center">Rank</th>
                                <th>Nama Kelas</th>
                                <th class="text-right">Total Kebaikan</th>
                                <th class="text-right">Total Pelanggaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelasRanking as $index => $kelas)
                            <tr>
                                <td class="text-center">
                                    @if($index == 0)
                                        <span class="uk-badge bg-yellow-500 text-white w-8 h-8 flex items-center justify-center rounded-full text-lg shadow-sm">1</span>
                                    @elseif($index == 1)
                                        <span class="uk-badge bg-gray-400 text-white w-8 h-8 flex items-center justify-center rounded-full text-lg shadow-sm">2</span>
                                    @elseif($index == 2)
                                        <span class="uk-badge bg-amber-700 text-white w-8 h-8 flex items-center justify-center rounded-full text-lg shadow-sm">3</span>
                                    @else
                                        <span class="text-gray-500 font-medium">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="font-semibold text-gray-800">{{ $kelas->class_name ?? 'Belum ada kelas' }}</td>
                                <td class="text-right font-bold text-green-600">{{ $kelas->total_kebaikan }} Pts</td>
                                <td class="text-right font-bold text-red-500">{{ $kelas->total_pelanggaran }} Pts</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-500">Belum ada data siswa/kelas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
