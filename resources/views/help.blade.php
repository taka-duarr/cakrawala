<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">❔ Pusat Bantuan & FAQ</h2>
    </x-slot>

    <div class="py-6 bg-slate-100/30 min-h-screen">
        <div class="max-w-3xl mx-auto space-y-6">
            
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">Frequently Asked Questions</h3>
                        <p class="text-xs text-slate-400 mt-1 font-bold uppercase tracking-wider">Pertanyaan yang sering diajukan mengenai sistem penilaian karakter, misi kebaikan, dan penukaran poin hadiah di CAKRAWALA.</p>
                    </div>
                    <!-- Search Input -->
                    <div class="relative w-full md:w-72">
                        <span uk-icon="icon: search; ratio: 0.85" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-950"></span>
                        <input type="text" id="search-faq" onkeyup="filterFAQ()" placeholder="Cari pertanyaan..." 
                            class="w-full border-2 border-slate-950 bg-white rounded-xl pl-9 pr-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] focus-within:shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] transition-all placeholder-slate-400">
                    </div>
                </div>

                <!-- UIkit Accordion for FAQ -->
                <ul uk-accordion="collapsible: true" class="divide-y-2 divide-slate-950 -mx-2">
                    <li class="uk-open py-4 px-2 faq-item">
                        <a class="uk-accordion-title text-sm font-black text-slate-950 hover:text-slate-700 transition flex items-center justify-between uppercase tracking-tight" href>
                            <span>Bagaimana cara mengumpulkan Poin Kebaikan?</span>
                        </a>
                        <div class="uk-accordion-content mt-3 text-xs text-slate-700 font-semibold leading-relaxed">
                            <p>Anda dapat mengumpulkan Poin Kebaikan dengan mengambil dan menyelesaikan misi di **Quest Board** pada dashboard Anda. Setelah mengambil misi, lakukan instruksinya (seperti hadir tepat waktu atau piket kelas), lalu kirimkan link bukti foto/video di form yang tersedia untuk disetujui oleh Guru/Wali Kelas.</p>
                        </div>
                    </li>

                    <li class="py-4 px-2 faq-item">
                        <a class="uk-accordion-title text-sm font-black text-slate-950 hover:text-slate-700 transition flex items-center justify-between uppercase tracking-tight" href>
                            <span>Bagaimana cara menukarkan hadiah di Toko Hadiah?</span>
                        </a>
                        <div class="uk-accordion-content mt-3 text-xs text-slate-700 font-semibold leading-relaxed">
                            <p>Siswa dapat masuk ke menu **Toko Hadiah** di sidebar. Jika Poin Kebaikan Anda mencukupi biaya penukaran hadiah, Anda dapat mengklik tombol "Tukarkan Hadiah". Pengajuan penukaran poin tersebut akan masuk ke dashboard Wali Kelas / Admin untuk mendapatkan persetujuan dan serah terima fisik hadiah.</p>
                        </div>
                    </li>
                    <li class="py-4 px-2 faq-item">
                        <a class="uk-accordion-title text-sm font-black text-slate-950 hover:text-slate-700 transition flex items-center justify-between uppercase tracking-tight" href>
                            <span>Bagaimana AI Student Insight memantau perkembangan saya?</span>
                        </a>
                        <div class="uk-accordion-content mt-3 text-xs text-slate-700 font-semibold leading-relaxed">
                            <p>AI Student Insight menganalisis tren aktivitas mingguan dan penyelesaian misi Anda. Sistem cerdas ini memberikan rekomendasi belajar yang personal untuk memicu motivasi belajar dan memberikan peringatan dini kepada Wali Kelas jika terdeteksi penurunan aktivitas belajar.</p>
                        </div>
                    </li>
                    <li class="py-4 px-2 faq-item">
                        <a class="uk-accordion-title text-sm font-black text-slate-950 hover:text-slate-700 transition flex items-center justify-between uppercase tracking-tight" href>
                            <span>Siapa yang bisa saya hubungi jika akun saya belum dikonfigurasi?</span>
                        </a>
                        <div class="uk-accordion-content mt-3 text-xs text-slate-700 font-semibold leading-relaxed">
                            <p>Jika peran Anda belum disesuaikan atau data anak Anda belum ditautkan (untuk orang tua), silakan hubungi Administrator Sekolah di bagian Tata Usaha sekolah untuk melakukan pendaftaran dan pemetaan peran yang benar.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="bg-[#E4FF1A] text-slate-950 border-4 border-slate-950 rounded-3xl p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h4 class="font-black text-base uppercase tracking-tight">Butuh bantuan lebih lanjut?</h4>
                    <p class="text-xs text-slate-800 mt-1 font-semibold">Hubungi tim administrator sekolah di email support@cakrawala.com atau kunjungi kantor Tata Usaha.</p>
                </div>
                <a href="mailto:support@cakrawala.com" class="px-5 py-2.5 bg-slate-950 hover:bg-white hover:text-slate-950 text-white border-2 border-slate-950 rounded-xl transition shadow-[3px_3px_0px_0px_rgba(255,255,255,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] font-black text-xs uppercase tracking-wider">
                    Hubungi Kami
                </a>
            </div>

        </div>
    </div>

    <script>
        function filterFAQ() {
            const searchVal = document.getElementById('search-faq').value.toLowerCase();
            const items = document.querySelectorAll('.faq-item');

            items.forEach(item => {
                const question = item.querySelector('.uk-accordion-title').textContent.toLowerCase();
                const answer = item.querySelector('.uk-accordion-content').textContent.toLowerCase();

                if (question.includes(searchVal) || answer.includes(searchVal)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
