<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            {{ __('Verifikasi Presensi QR') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto sm:px-6 lg:px-8">
            
            @if(auth()->user()->role && auth()->user()->role->name !== 'siswa')
            <!-- main card for Guru/Admin (Display QR Code) -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 space-y-6 text-center shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                
                <div class="space-y-3">
                    <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-wider inline-block shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        Kode QR Presensi · Pertemuan Ke-{{ $session->meeting_number }}
                    </span>
                    <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">{{ $session->teachingAssignment->subject->name }}</h3>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">
                        Kelas: <span class="text-slate-950 font-black">{{ $session->teachingAssignment->classroom->name }}</span> · Lokasi: <span class="text-slate-950 font-black">{{ $session->schoolLocation->name ?? 'Semua Lokasi Aktif' }}</span>
                    </p>
                </div>

                <!-- Display QR Code image -->
                <div class="py-4 flex justify-center">
                    <div class="p-4 bg-white border-4 border-slate-950 rounded-3xl inline-block shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(request()->url()) }}" 
                             alt="QR Code Presensi" 
                             class="w-64 h-64 md:w-72 md:h-72 object-contain rounded-xl bg-white">
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="text-xs text-slate-500 font-semibold max-w-sm mx-auto leading-relaxed">
                        Tunjukkan layar ini pada proyektor/layar kelas. Siswa dapat memindai (scan) QR code ini untuk melakukan absensi kehadiran mandiri di perangkat mereka.
                    </p>
                    
                    @if(auth()->user()->role && auth()->user()->role->name === 'guru')
                        <a href="{{ route('guru.assignments.detail', $session->teaching_assignment_id) }}" class="inline-flex items-center space-x-1.5 px-4 py-2.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black rounded-xl border-2 border-slate-950 transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                            <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                            <span>Kembali ke Detail Kelas</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-1.5 px-4 py-2.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black rounded-xl border-2 border-slate-950 transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                            <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                            <span>Kembali ke Dashboard</span>
                        </a>
                    @endif
                </div>

                <div class="pt-4 border-t-2 border-slate-950 flex justify-between items-center text-[10px] text-slate-500 font-black uppercase tracking-wider">
                    <span>Tenggat Sesi:</span>
                    <span class="text-rose-600 font-extrabold">{{ \Carbon\Carbon::parse($session->deadline)->format('H:i') }} WIB</span>
                </div>

            </div>
            @else
            <!-- main card for Student (GPS Check-In) -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 space-y-6 text-center shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                
                <div class="w-16 h-16 bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center mx-auto shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: camera; ratio: 1.5"></span>
                </div>

                <div class="space-y-2">
                    <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-wider inline-block shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        Pertemuan Ke-{{ $session->meeting_number }}
                    </span>
                    <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">{{ $session->teachingAssignment->subject->name }}</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-bold uppercase tracking-wider">
                        Sesi presensi dibuka oleh guru Anda. Silakan verifikasi posisi GPS Anda untuk mencatat kehadiran.
                    </p>
                </div>

                <!-- Geolocation trigger section -->
                <div class="space-y-4">
                    <!-- Geolocation failure message (hidden by default) -->
                    <div id="geo-error" class="hidden bg-[#FFEAEA] border-2 border-slate-950 text-rose-700 p-4 rounded-xl text-xs font-bold uppercase tracking-wider">
                        <span uk-icon="icon: warning; ratio: 0.9" class="mr-1"></span>
                        <span id="geo-error-msg">Izin GPS tidak diberikan atau posisi tidak ditemukan.</span>
                    </div>

                    @if(session('error'))
                        <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-700 p-4 rounded-xl text-xs font-bold uppercase tracking-wider">
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
                        <button type="button" onclick="requestScanCheckIn()" id="btn-scan-submit" class="w-full py-4 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 rounded-2xl text-xs font-black uppercase transition shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] flex items-center justify-center space-x-1.5">
                            <span uk-icon="icon: check; ratio: 0.85"></span>
                            <span>Konfirmasi Kehadiran Saya</span>
                        </button>
                    </form>

                    <!-- Loading Spinner (hidden by default) -->
                    <div id="loading-spinner" class="hidden flex-col items-center justify-center py-4 space-y-2.5">
                        <span class="animate-spin inline-block w-8 h-8 border-4 border-slate-950 border-t-[#E4FF1A] rounded-full"></span>
                        <p class="text-xs text-slate-950 font-black uppercase tracking-wider">Memverifikasi lokasi GPS Anda...</p>
                    </div>
                </div>

                <div class="pt-4 border-t-2 border-slate-950 flex justify-between items-center text-[10px] text-slate-500 font-black uppercase tracking-wider">
                    <span>Tenggat Sesi:</span>
                    <span class="text-rose-600 font-extrabold">{{ \Carbon\Carbon::parse($session->deadline)->format('H:i') }} WIB</span>
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
