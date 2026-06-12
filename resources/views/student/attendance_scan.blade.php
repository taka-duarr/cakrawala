<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Verifikasi Presensi QR') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto sm:px-6 lg:px-8">
            
            @if(auth()->user()->role && auth()->user()->role->name !== 'siswa')
            <!-- main card for Guru/Admin (Display QR Code) -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-lg p-8 space-y-6 text-center soft-glow-indigo">
                
                <div class="space-y-2">
                    <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 text-[9px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                        Kode QR Presensi · Pertemuan Ke-{{ $session->meeting_number }}
                    </span>
                    <h3 class="text-xl font-black text-slate-800">{{ $session->teachingAssignment->subject->name }}</h3>
                    <p class="text-xs text-slate-400 font-semibold leading-relaxed">
                        Kelas: <span class="font-bold text-slate-700">{{ $session->teachingAssignment->classroom->name }}</span> · Lokasi: <span class="font-bold text-slate-700">{{ $session->schoolLocation->name ?? 'Semua Lokasi Aktif' }}</span>
                    </p>
                </div>

                <!-- Display QR Code image -->
                <div class="py-4 flex justify-center">
                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl inline-block shadow-inner">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(request()->url()) }}" 
                             alt="QR Code Presensi" 
                             class="w-64 h-64 md:w-72 md:h-72 object-contain rounded-xl bg-white shadow-sm border border-slate-100">
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-xs text-slate-500 font-semibold max-w-sm mx-auto leading-relaxed">
                        Tunjukkan layar ini pada proyektor/layar kelas. Siswa dapat memindai (scan) QR code ini untuk melakukan absensi kehadiran mandiri di perangkat mereka.
                    </p>
                    
                    @if(auth()->user()->role && auth()->user()->role->name === 'guru')
                        <a href="{{ route('guru.assignments.detail', $session->teaching_assignment_id) }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                            <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                            <span>Kembali ke Detail Kelas</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                            <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                            <span>Kembali ke Dashboard</span>
                        </a>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-[9px] text-slate-400 font-bold uppercase tracking-wider">
                    <span>Tenggat Sesi:</span>
                    <span class="text-rose-700 font-extrabold">{{ \Carbon\Carbon::parse($session->deadline)->format('H:i') }} WIB</span>
                </div>

            </div>
            @else
            <!-- main card for Student (GPS Check-In) -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-lg p-8 space-y-6 text-center soft-glow-indigo">
                
                <div class="w-16 h-16 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                    <span uk-icon="icon: camera; ratio: 1.5"></span>
                </div>

                <div class="space-y-2">
                    <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 text-[9px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                        Pertemuan Ke-{{ $session->meeting_number }}
                    </span>
                    <h3 class="text-lg font-black text-slate-800">{{ $session->teachingAssignment->subject->name }}</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-semibold">
                        Sesi presensi dibuka oleh guru Anda. Silakan verifikasi posisi GPS Anda untuk mencatat kehadiran.
                    </p>
                </div>

                <!-- Geolocation trigger section -->
                <div class="space-y-4">
                    <!-- Geolocation failure message (hidden by default) -->
                    <div id="geo-error" class="hidden bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-xs font-semibold leading-relaxed">
                        <span uk-icon="icon: warning; ratio: 0.9" class="mr-1"></span>
                        <span id="geo-error-msg">Izin GPS tidak diberikan atau posisi tidak ditemukan.</span>
                    </div>

                    @if(session('error'))
                        <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-xs font-semibold leading-relaxed">
                            <span uk-icon="icon: warning; ratio: 0.9" class="mr-1"></span>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Action form -->
                    <form id="scan-checkin-form" action="{{ route('student.sessions.scan-check-in', $session->qr_token) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="latitude" id="hidden-lat">
                        <input type="hidden" name="longitude" id="hidden-lng">

                        <!-- Main check in button -->
                        <button type="button" onclick="requestScanCheckIn()" id="btn-scan-submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-100 flex items-center justify-center space-x-1.5">
                            <span uk-icon="icon: check; ratio: 0.85"></span>
                            <span>Konfirmasi Kehadiran Saya</span>
                        </button>
                    </form>

                    <!-- Loading Spinner (hidden by default) -->
                    <div id="loading-spinner" class="hidden flex-col items-center justify-center py-4 space-y-2.5">
                        <span class="animate-spin inline-block w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full"></span>
                        <p class="text-xs text-indigo-700 font-bold">Memverifikasi lokasi GPS Anda...</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-[9px] text-slate-400 font-bold uppercase tracking-wider">
                    <span>Tenggat Sesi:</span>
                    <span class="text-rose-700 font-extrabold">{{ \Carbon\Carbon::parse($session->deadline)->format('H:i') }} WIB</span>
                </div>

            </div>
            @endif

        </div>
    </div>

    <!-- SCRIPT FOR SCAN GEOLOCATION -->
    <script>
        function requestScanCheckIn() {
            const btn = document.getElementById('btn-scan-submit');
            const spinner = document.getElementById('loading-spinner');
            const errorBlock = document.getElementById('geo-error');
            const errorMsg = document.getElementById('geo-error-msg');
            const form = document.getElementById('scan-checkin-form');

            errorBlock.classList.add('hidden');

            if (!navigator.geolocation) {
                errorMsg.innerText = 'Browser Anda tidak mendukung layanan penentuan lokasi / GPS.';
                errorBlock.classList.remove('hidden');
                return;
            }

            btn.classList.add('hidden');
            spinner.classList.remove('hidden');

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    document.getElementById('hidden-lat').value = lat;
                    document.getElementById('hidden-lng').value = lng;

                    // Automatically submit the form
                    form.submit();
                },
                function(error) {
                    spinner.classList.add('hidden');
                    btn.classList.remove('hidden');

                    let msg = 'Gagal mengakses koordinat GPS Anda.';
                    if (error.code === error.PERMISSION_DENIED) {
                        msg = 'Akses lokasi ditolak. Harap izinkan akses lokasi/GPS pada pengaturan browser Anda untuk melanjutkan.';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        msg = 'Informasi lokasi tidak tersedia. Coba cari area terbuka agar GPS mendeteksi perangkat Anda.';
                    } else if (error.code === error.TIMEOUT) {
                        msg = 'Waktu pengambilan lokasi habis. Silakan coba lagi.';
                    }

                    errorMsg.innerText = msg;
                    errorBlock.classList.remove('hidden');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 12000,
                    maximumAge: 0
                }
            );
        }
    </script>
</x-app-layout>
