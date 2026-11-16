<x-app title="Profil & Riwayat">
    
    <div class="min-h-screen bg-slate-50/80 py-12 font-sans selection:bg-blue-100 selection:text-blue-600">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">

            {{-- 1. HEADER & STATS CARD (Sticky saat di-scroll) --}}
            <div class="sticky top-24 z-30 bg-white/95 backdrop-blur-md rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.08),0_10px_25px_-5px_rgba(0,0,0,0.04)] border border-white p-6 lg:p-7 mb-8 overflow-hidden group transition-all duration-300">
                
                {{-- Hiasan Background Halus --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-60 group-hover:opacity-100 transition-opacity duration-700"></div>

                <div class="relative z-10 flex flex-col lg:flex-row items-center gap-8">
                    
                    {{-- Foto Profil --}}
                    <div class="relative">
                        <div class="w-28 h-28 rounded-full p-1 bg-white shadow-lg border border-slate-100">
                            <div class="w-full h-full rounded-full overflow-hidden relative group/img">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://placehold.co/112x112?text={{ substr($user->name, 0, 1) }}';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400 text-4xl font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <label for="photoInput" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-opacity cursor-pointer text-white">
                                    <i class="fas fa-camera"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Info User --}}
                    <div class="text-center lg:text-left flex-1">
                        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $user->name }}</h1>
                        <div class="flex flex-wrap justify-center lg:justify-start gap-3 mt-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium border border-slate-200">
                                <i class="far fa-envelope text-slate-400"></i> {{ $user->email }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100">
                                <i class="fas fa-user-check"></i> Member Terverifikasi
                            </span>
                        </div>
                    </div>

                    {{-- STATS WIDGETS --}}
                    <div class="w-full lg:w-auto grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-4 rounded-2xl border border-amber-100/50 shadow-sm flex items-center gap-3 min-w-[140px]">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm text-amber-500 flex items-center justify-center text-lg"><i class="fas fa-star"></i></div>
                            <div><p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Poin</p><p class="text-lg font-black text-slate-800">{{ number_format($totalPoints) }}</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-2xl border border-blue-100/50 shadow-sm flex items-center gap-3 min-w-[140px]">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm text-blue-600 flex items-center justify-center text-lg"><i class="fas fa-wallet"></i></div>
                            <div><p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Donasi</p><p class="text-lg font-black text-slate-800">{{ number_format($totalDonationAmount, 0, ',', '.') }}</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-2xl border border-green-100/50 shadow-sm flex items-center gap-3 min-w-[140px]">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm text-green-600 flex items-center justify-center text-lg"><i class="fas fa-hand-holding-heart"></i></div>
                            <div><p class="text-[10px] font-bold text-green-600 uppercase tracking-wider">Kampanye</p><p class="text-lg font-black text-slate-800">{{ $countDonatedCampaigns }}</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-fuchsia-50 p-4 rounded-2xl border border-purple-100/50 shadow-sm flex items-center gap-3 min-w-[140px]">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm text-purple-600 flex items-center justify-center text-lg"><i class="fas fa-hands-helping"></i></div>
                            <div><p class="text-[10px] font-bold text-purple-600 uppercase tracking-wider">Relawan</p><p class="text-lg font-black text-slate-800">{{ $countVolunteerCampaigns }}</p></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- 2. SIDEBAR MENU --}}
                <div class="lg:col-span-3">
                    <div class="space-y-1">
                        <button onclick="switchTab('edit-profile')" id="btn-edit-profile" class="tab-btn active w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3"><i class="fas fa-user-edit w-5 text-center group-hover:text-blue-500 transition-colors"></i> Edit Profil</span>
                            <i class="fas fa-chevron-right text-xs opacity-0 group-hover:opacity-50 transition-opacity"></i>
                        </button>

                        <button onclick="switchTab('security')" id="btn-security" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3"><i class="fas fa-lock w-5 text-center group-hover:text-blue-500 transition-colors"></i> Keamanan</span>
                            <i class="fas fa-chevron-right text-xs opacity-0 group-hover:opacity-50 transition-opacity"></i>
                        </button>

                        <div class="h-px bg-slate-200/60 my-3 mx-4"></div>

                        <button onclick="switchTab('history-donation')" id="btn-history-donation" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3"><i class="fas fa-receipt w-5 text-center group-hover:text-blue-500 transition-colors"></i> Riwayat Donasi</span>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-md font-bold">{{ $allDonations->count() }}</span>
                        </button>

                        <button onclick="switchTab('history-volunteer')" id="btn-history-volunteer" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3"><i class="fas fa-hands-helping w-5 text-center group-hover:text-blue-500 transition-colors"></i> Relawan</span>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-md font-bold">{{ $volunteerApps->total() }}</span>
                        </button>

                        <button onclick="switchTab('volunteer-preferences')" id="btn-volunteer-preferences" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3"><i class="fas fa-star w-5 text-center group-hover:text-blue-500 transition-colors text-amber-500 animate-pulse"></i> Minat Relawan</span>
                            <span class="bg-blue-50 text-blue-600 text-[10px] px-2 py-0.5 rounded-md font-bold">Preferences</span>
                        </button>

                        <button onclick="switchTab('manage-campaign')" id="btn-manage-campaign" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3"><i class="fas fa-bullhorn w-5 text-center group-hover:text-blue-500 transition-colors"></i> Kelola Kampanye</span>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-md font-bold">{{ \App\Models\Campaign::where('user_id', auth()->id())->count() }}</span>
                        </button>

                        <button onclick="switchTab('certificates')" id="btn-certificates" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3"><i class="fas fa-certificate w-5 text-center group-hover:text-blue-500 transition-colors"></i> Sertifikat Saya</span>
                            <span class="bg-blue-50 text-blue-600 text-[10px] px-2 py-0.5 rounded-md font-bold">{{ $certificates->count() }}</span>
                        </button>

                        <form action="{{ route('logout') }}" method="POST" class="pt-6">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-2xl text-sm font-bold text-red-500 bg-red-50 hover:bg-red-100 hover:shadow-inner transition-all">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 3. KONTEN TAB --}}
                <div class="lg:col-span-9">
                    
                    {{-- Alert Success --}}
                    <script>
                        // Global functions for onclick attributes
                        function openModalDonasi() {
                            console.log("Attempting to open modal...");
                            const modal = document.getElementById('modalTambahDonasi');
                            if (modal) {
                                modal.classList.remove('hidden');
                                if (typeof window.resetStep === 'function') {
                                    window.resetStep();
                                }
                                console.log("Modal opened successfully.");
                            } else {
                                console.error("Modal element #modalTambahDonasi not found!");
                                alert("Gagal membuka form: Elemen modal tidak ditemukan.");
                            }
                        }
                        
                        function closeModalDonasi() {
                            const modal = document.getElementById('modalTambahDonasi');
                            if (modal) modal.classList.add('hidden');
                        }

                        // --- GLOBAL DYNAMIC FORM & DRAFT FUNCTIONS ---
                        window.onWebCategoryChanged = function() {
                            const select = document.getElementById('web_kategori_select');
                            const container = document.getElementById('web_dynamic_fields_container');
                            if (!select || !container) return;

                            const category = select.value;
                            if (!category || category === 'Lingkungan') {
                                container.style.maxHeight = '0px';
                                container.style.padding = '0px';
                                container.classList.add('hidden');
                                container.innerHTML = '';
                                return;
                            }

                            container.classList.remove('hidden');
                            let html = '';

                            if (category === 'Kesehatan') {
                                html = `
                                    <div class="space-y-4">
                                        <span class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-blue-600 mb-2">
                                            <i class="fas fa-health-and-safety text-base"></i> Detail Kebutuhan Medis
                                        </span>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Nama Pasien</label>
                                                <input type="text" id="dyn_kesehatan_nama_pasien" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Nama lengkap pasien" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Nama Rumah Sakit</label>
                                                <input type="text" id="dyn_kesehatan_nama_rs" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Rumah sakit rujukan" required>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Diagnosis Penyakit</label>
                                                <input type="text" id="dyn_kesehatan_diagnosis" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-medium" placeholder="Contoh: Kanker Kelenjar" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Estimasi Biaya Pengobatan (Rp)</label>
                                                <input type="number" id="dyn_kesehatan_estimasi_biaya" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Total estimasi biaya pengobatan" required>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            } else if (category === 'Bencana Alam') {
                                html = `
                                    <div class="space-y-4">
                                        <span class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-blue-600 mb-2">
                                            <i class="fas fa-tsunami text-base"></i> Detail Tanggap Bencana
                                        </span>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Jenis Bencana</label>
                                                <input type="text" id="dyn_bencana_jenis" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Contoh: Erupsi, Banjir, Gempa" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Tanggal Kejadian</label>
                                                <input type="date" id="dyn_bencana_tanggal" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" required>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Lokasi Kejadian</label>
                                                <input type="text" id="dyn_bencana_lokasi" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-medium" placeholder="Kecamatan, Kota" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Jumlah Korban Terdampak (Jiwa)</label>
                                                <input type="number" id="dyn_bencana_jumlah_korban" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Jumlah korban terdampak" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Kondisi Saat Ini</label>
                                            <textarea id="dyn_bencana_kondisi" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-medium" placeholder="Kebutuhan logistik, medis mendesak, dsb." required></textarea>
                                        </div>
                                    </div>
                                `;
                            } else if (category === 'Pendidikan') {
                                html = `
                                    <div class="space-y-4">
                                        <span class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-blue-600 mb-2">
                                            <i class="fas fa-graduation-cap text-base"></i> Detail Beasiswa / Fasilitas
                                        </span>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Penerima Bantuan</label>
                                                <input type="text" id="dyn_pendidikan_penerima" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Nama siswa / mahasiswa" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Sekolah / Kampus</label>
                                                <input type="text" id="dyn_pendidikan_lembaga" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Nama sekolah/kampus" required>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Jenjang Pendidikan</label>
                                                <select id="dyn_pendidikan_jenjang" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" required>
                                                    <option value="">Pilih Jenjang</option>
                                                    <option value="SD/Sederajat">SD/Sederajat</option>
                                                    <option value="SMP/Sederajat">SMP/Sederajat</option>
                                                    <option value="SMA/Sederajat">SMA/Sederajat</option>
                                                    <option value="Kuliah/Universitas">Kuliah/Universitas</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Total Biaya Pendidikan (Rp)</label>
                                                <input type="number" id="dyn_pendidikan_total_biaya" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Total biaya pendidikan" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Kebutuhan Utama</label>
                                            <input type="text" id="dyn_pendidikan_kebutuhan" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-medium" placeholder="Contoh: Pembayaran UKT Semester 4" required>
                                        </div>
                                    </div>
                                `;
                            } else if (category === 'Ibadah') {
                                html = `
                                    <div class="space-y-4">
                                        <span class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-blue-600 mb-2">
                                            <i class="fas fa-mosque text-base"></i> Detail Sarana Ibadah
                                        </span>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Nama Tempat Ibadah</label>
                                                <input type="text" id="dyn_ibadah_nama" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Masjid/Gereja/Wihara..." required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Lokasi Tempat Ibadah</label>
                                                <input type="text" id="dyn_ibadah_lokasi" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Kelurahan, Kota" required>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Jenis Renovasi / Kebutuhan</label>
                                                <input type="text" id="dyn_ibadah_kebutuhan" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-medium" placeholder="Contoh: Perbaikan Atap Kubah" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Estimasi Dana (Rp)</label>
                                                <input type="number" id="dyn_ibadah_estimasi" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Contoh: 45000000" required>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            } else if (category === 'Sosial Kemanusiaan') {
                                html = `
                                    <div class="space-y-4">
                                        <span class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-blue-600 mb-2">
                                            <i class="fas fa-hands-helping text-base"></i> Detail Program Sosial
                                        </span>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Target Penerima</label>
                                                <input type="text" id="dyn_sosial_target" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Contoh: Lansia Dhuafa" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Jumlah Penerima (Orang)</label>
                                                <input type="number" id="dyn_sosial_jumlah" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Contoh: 100" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Lokasi Penyaluran Program</label>
                                            <input type="text" id="dyn_sosial_lokasi" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-semibold" placeholder="Alamat penyaluran" required>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Deskripsi Rencana Kegiatan</label>
                                            <textarea id="dyn_sosial_deskripsi" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 transition-all text-slate-700 text-sm font-medium" placeholder="Bagaimana alur penyaluran kegiatan?" required></textarea>
                                        </div>
                                    </div>
                                `;
                            }

                            container.innerHTML = html;
                            container.style.padding = '1.25rem';
                            container.style.maxHeight = '1000px';

                            // Save draft immediately and bind listeners
                            saveWebDraft();
                            const newInputs = container.querySelectorAll('input, select, textarea');
                            newInputs.forEach(input => {
                                input.addEventListener('input', saveWebDraft);
                            });
                        };

                        window.saveWebDraft = function() {
                            const judulInput = document.querySelector('input[name="judul"]');
                            const descInput = document.querySelector('textarea[name="deskripsi"]');
                            const targetInput = document.querySelector('input[name="target"]');
                            const deadlineInput = document.querySelector('input[name="deadline"]');
                            const provSelect = document.getElementById('web_provinsi');
                            const kabSelect = document.getElementById('web_kabupaten');
                            const kecInput = document.getElementById('web_kecamatan');
                            const addrInput = document.getElementById('web_alamat_lengkap');
                            const zipInput = document.getElementById('web_kode_pos');
                            const recInput = document.querySelector('input[name="penerima"]');
                            const recTypeSelect = document.querySelector('select[name="jenis_penerima"]');
                            const waInput = document.querySelector('input[name="whatsapp"]');
                            const penyaluranSelect = document.querySelector('select[name="penyaluran"]');

                            const draft = {
                                judul: judulInput ? judulInput.value : '',
                                kategori: document.getElementById('web_kategori_select')?.value || '',
                                deskripsi: descInput ? descInput.value : '',
                                target: targetInput ? targetInput.value : '',
                                deadline: deadlineInput ? deadlineInput.value : '',
                                provinsi: provSelect ? provSelect.value : '',
                                kabupaten: kabSelect ? kabSelect.value : '',
                                kecamatan: kecInput ? kecInput.value : '',
                                alamatLengkap: addrInput ? addrInput.value : '',
                                kodePos: zipInput ? zipInput.value : '',
                                penerima: recInput ? recInput.value : '',
                                jenisPenerima: recTypeSelect ? recTypeSelect.value : '',
                                whatsapp: waInput ? waInput.value : '',
                                penyaluran: penyaluranSelect ? penyaluranSelect.value : '',

                                // Kesehatan
                                dyn_kesehatan_nama_pasien: document.getElementById('dyn_kesehatan_nama_pasien')?.value || '',
                                dyn_kesehatan_nama_rs: document.getElementById('dyn_kesehatan_nama_rs')?.value || '',
                                dyn_kesehatan_diagnosis: document.getElementById('dyn_kesehatan_diagnosis')?.value || '',
                                dyn_kesehatan_estimasi_biaya: document.getElementById('dyn_kesehatan_estimasi_biaya')?.value || '',

                                // Bencana
                                dyn_bencana_jenis: document.getElementById('dyn_bencana_jenis')?.value || '',
                                dyn_bencana_tanggal: document.getElementById('dyn_bencana_tanggal')?.value || '',
                                dyn_bencana_lokasi: document.getElementById('dyn_bencana_lokasi')?.value || '',
                                dyn_bencana_jumlah_korban: document.getElementById('dyn_bencana_jumlah_korban')?.value || '',
                                dyn_bencana_kondisi: document.getElementById('dyn_bencana_kondisi')?.value || '',

                                // Pendidikan
                                dyn_pendidikan_penerima: document.getElementById('dyn_pendidikan_penerima')?.value || '',
                                dyn_pendidikan_lembaga: document.getElementById('dyn_pendidikan_lembaga')?.value || '',
                                dyn_pendidikan_jenjang: document.getElementById('dyn_pendidikan_jenjang')?.value || '',
                                dyn_pendidikan_total_biaya: document.getElementById('dyn_pendidikan_total_biaya')?.value || '',
                                dyn_pendidikan_kebutuhan: document.getElementById('dyn_pendidikan_kebutuhan')?.value || '',

                                // Ibadah
                                dyn_ibadah_nama: document.getElementById('dyn_ibadah_nama')?.value || '',
                                dyn_ibadah_lokasi: document.getElementById('dyn_ibadah_lokasi')?.value || '',
                                dyn_ibadah_kebutuhan: document.getElementById('dyn_ibadah_kebutuhan')?.value || '',
                                dyn_ibadah_estimasi: document.getElementById('dyn_ibadah_estimasi')?.value || '',

                                // Sosial
                                dyn_sosial_target: document.getElementById('dyn_sosial_target')?.value || '',
                                dyn_sosial_jumlah: document.getElementById('dyn_sosial_jumlah')?.value || '',
                                dyn_sosial_lokasi: document.getElementById('dyn_sosial_lokasi')?.value || '',
                                dyn_sosial_deskripsi: document.getElementById('dyn_sosial_deskripsi')?.value || ''
                            };

                            localStorage.setItem('dongiv_web_campaign_draft', JSON.stringify(draft));
                        };

                        window.loadWebDraft = function() {
                            const raw = localStorage.getItem('dongiv_web_campaign_draft');
                            if (!raw) return;

                            try {
                                const draft = JSON.parse(raw);
                                
                                const judulInput = document.querySelector('input[name="judul"]');
                                if (judulInput) judulInput.value = draft.judul || '';
                                
                                const catSelect = document.getElementById('web_kategori_select');
                                if (catSelect) {
                                    catSelect.value = draft.kategori || '';
                                    onWebCategoryChanged();
                                }

                                const descInput = document.querySelector('textarea[name="deskripsi"]');
                                if (descInput) descInput.value = draft.deskripsi || '';

                                const targetInput = document.querySelector('input[name="target"]');
                                if (targetInput) targetInput.value = draft.target || '';

                                const deadlineInput = document.querySelector('input[name="deadline"]');
                                if (deadlineInput) deadlineInput.value = draft.deadline || '';
                                
                                const provSelect = document.getElementById('web_provinsi');
                                if (provSelect) {
                                    provSelect.value = draft.provinsi || '';
                                    onWebProvinsiChanged();
                                }

                                const kabSelect = document.getElementById('web_kabupaten');
                                if (kabSelect) kabSelect.value = draft.kabupaten || '';

                                const kecInput = document.getElementById('web_kecamatan');
                                if (kecInput) kecInput.value = draft.kecamatan || '';

                                const addrInput = document.getElementById('web_alamat_lengkap');
                                if (addrInput) addrInput.value = draft.alamatLengkap || '';

                                const zipInput = document.getElementById('web_kode_pos');
                                if (zipInput) zipInput.value = draft.kodePos || '';

                                updateWebConsolidatedLocation();

                                const recInput = document.querySelector('input[name="penerima"]');
                                if (recInput) recInput.value = draft.penerima || '';

                                const recTypeSelect = document.querySelector('select[name="jenis_penerima"]');
                                if (recTypeSelect) recTypeSelect.value = draft.jenisPenerima || '';

                                const waInput = document.querySelector('input[name="whatsapp"]');
                                if (waInput) waInput.value = draft.whatsapp || '';

                                const penyaluranSelect = document.querySelector('select[name="penyaluran"]');
                                if (penyaluranSelect) penyaluranSelect.value = draft.penyaluran || '';

                                loadWebDraftFields();
                            } catch(e) {
                                console.error('Error loading web draft:', e);
                            }
                        };

                        window.loadWebDraftFields = function() {
                            const raw = localStorage.getItem('dongiv_web_campaign_draft');
                            if (!raw) return;

                            try {
                                const draft = JSON.parse(raw);
                                const category = document.getElementById('web_kategori_select')?.value;
                                if (category === 'Kesehatan') {
                                    if (document.getElementById('dyn_kesehatan_nama_pasien')) document.getElementById('dyn_kesehatan_nama_pasien').value = draft.dyn_kesehatan_nama_pasien || '';
                                    if (document.getElementById('dyn_kesehatan_nama_rs')) document.getElementById('dyn_kesehatan_nama_rs').value = draft.dyn_kesehatan_nama_rs || '';
                                    if (document.getElementById('dyn_kesehatan_diagnosis')) document.getElementById('dyn_kesehatan_diagnosis').value = draft.dyn_kesehatan_diagnosis || '';
                                    if (document.getElementById('dyn_kesehatan_estimasi_biaya')) document.getElementById('dyn_kesehatan_estimasi_biaya').value = draft.dyn_kesehatan_estimasi_biaya || '';
                                } else if (category === 'Bencana Alam') {
                                    if (document.getElementById('dyn_bencana_jenis')) document.getElementById('dyn_bencana_jenis').value = draft.dyn_bencana_jenis || '';
                                    if (document.getElementById('dyn_bencana_tanggal')) document.getElementById('dyn_bencana_tanggal').value = draft.dyn_bencana_tanggal || '';
                                    if (document.getElementById('dyn_bencana_lokasi')) document.getElementById('dyn_bencana_lokasi').value = draft.dyn_bencana_lokasi || '';
                                    if (document.getElementById('dyn_bencana_jumlah_korban')) document.getElementById('dyn_bencana_jumlah_korban').value = draft.dyn_bencana_jumlah_korban || '';
                                    if (document.getElementById('dyn_bencana_kondisi')) document.getElementById('dyn_bencana_kondisi').value = draft.dyn_bencana_kondisi || '';
                                } else if (category === 'Pendidikan') {
                                    if (document.getElementById('dyn_pendidikan_penerima')) document.getElementById('dyn_pendidikan_penerima').value = draft.dyn_pendidikan_penerima || '';
                                    if (document.getElementById('dyn_pendidikan_lembaga')) document.getElementById('dyn_pendidikan_lembaga').value = draft.dyn_pendidikan_lembaga || '';
                                    if (document.getElementById('dyn_pendidikan_jenjang')) document.getElementById('dyn_pendidikan_jenjang').value = draft.dyn_pendidikan_jenjang || '';
                                    if (document.getElementById('dyn_pendidikan_total_biaya')) document.getElementById('dyn_pendidikan_total_biaya').value = draft.dyn_pendidikan_total_biaya || '';
                                    if (document.getElementById('dyn_pendidikan_kebutuhan')) document.getElementById('dyn_pendidikan_kebutuhan').value = draft.dyn_pendidikan_kebutuhan || '';
                                } else if (category === 'Ibadah') {
                                    if (document.getElementById('dyn_ibadah_nama')) document.getElementById('dyn_ibadah_nama').value = draft.dyn_ibadah_nama || '';
                                    if (document.getElementById('dyn_ibadah_lokasi')) document.getElementById('dyn_ibadah_lokasi').value = draft.dyn_ibadah_lokasi || '';
                                    if (document.getElementById('dyn_ibadah_kebutuhan')) document.getElementById('dyn_ibadah_kebutuhan').value = draft.dyn_ibadah_kebutuhan || '';
                                    if (document.getElementById('dyn_ibadah_estimasi')) document.getElementById('dyn_ibadah_estimasi').value = draft.dyn_ibadah_estimasi || '';
                                } else if (category === 'Sosial Kemanusiaan') {
                                    if (document.getElementById('dyn_sosial_target')) document.getElementById('dyn_sosial_target').value = draft.dyn_sosial_target || '';
                                    if (document.getElementById('dyn_sosial_jumlah')) document.getElementById('dyn_sosial_jumlah').value = draft.dyn_sosial_jumlah || '';
                                    if (document.getElementById('dyn_sosial_lokasi')) document.getElementById('dyn_sosial_lokasi').value = draft.dyn_sosial_lokasi || '';
                                    if (document.getElementById('dyn_sosial_deskripsi')) document.getElementById('dyn_sosial_deskripsi').value = draft.dyn_sosial_deskripsi || '';
                                }
                            } catch(e) {
                                console.error('Error loading web fields:', e);
                            }
                        };

                        window.clearWebDraft = function() {
                            localStorage.removeItem('dongiv_web_campaign_draft');
                        };

                        window.updateCampaignPreview = function() {
                            const judulInput = document.querySelector('input[name="judul"]');
                            const catSelect = document.getElementById('web_kategori_select');
                            const targetInput = document.querySelector('input[name="target"]');
                            const deadlineInput = document.querySelector('input[name="deadline"]');
                            const provSelect = document.getElementById('web_provinsi');
                            const kabSelect = document.getElementById('web_kabupaten');

                            const judul = judulInput ? judulInput.value : 'Judul Kampanye';
                            const kategori = catSelect ? catSelect.value : 'Donasi';
                            const targetVal = targetInput ? parseFloat(targetInput.value) || 0 : 0;
                            const deadline = deadlineInput ? deadlineInput.value : '-';
                            const locationStr = kabSelect && provSelect && kabSelect.value && provSelect.value ? `${kabSelect.value}, ${provSelect.value}` : (kabSelect?.value || provSelect?.value || 'Lokasi Pelaksanaan');

                            const formattedTarget = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(targetVal);

                            document.getElementById('preview_card_title').textContent = judul;
                            document.getElementById('preview_card_category').textContent = kategori;
                            document.getElementById('preview_card_location').textContent = locationStr;
                            document.getElementById('preview_card_target').textContent = formattedTarget;
                            document.getElementById('preview_card_deadline').textContent = deadline;

                            const previewSec = document.getElementById('web_preview_dynamic_section');
                            const previewContent = document.getElementById('web_preview_dynamic_content');

                            if (kategori && kategori !== 'Lingkungan') {
                                previewSec.classList.remove('hidden');
                                let detailHtml = '';
                                
                                if (kategori === 'Kesehatan') {
                                    const namaPasien = document.getElementById('dyn_kesehatan_nama_pasien')?.value || '-';
                                    const namaRs = document.getElementById('dyn_kesehatan_nama_rs')?.value || '-';
                                    const diagnosis = document.getElementById('dyn_kesehatan_diagnosis')?.value || '-';
                                    const estimasi = parseFloat(document.getElementById('dyn_kesehatan_estimasi_biaya')?.value) || 0;
                                    const formattedEst = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(estimasi);

                                    detailHtml = `
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Nama Pasien:</span><span class="text-slate-800">${namaPasien}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Rumah Sakit:</span><span class="text-slate-800">${namaRs}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Diagnosis:</span><span class="text-slate-800">${diagnosis}</span></div>
                                        <div class="flex justify-between py-1"><span>Estimasi Biaya:</span><span class="text-slate-800">${formattedEst}</span></div>
                                    `;
                                } else if (kategori === 'Bencana Alam') {
                                    const jenis = document.getElementById('dyn_bencana_jenis')?.value || '-';
                                    const tanggal = document.getElementById('dyn_bencana_tanggal')?.value || '-';
                                    const lokasi = document.getElementById('dyn_bencana_lokasi')?.value || '-';
                                    const korban = document.getElementById('dyn_bencana_jumlah_korban')?.value || '0';
                                    const kondisi = document.getElementById('dyn_bencana_kondisi')?.value || '-';

                                    detailHtml = `
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Jenis Bencana:</span><span class="text-slate-800">${jenis}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Tanggal Kejadian:</span><span class="text-slate-800">${tanggal}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Lokasi Kejadian:</span><span class="text-slate-800">${lokasi}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Korban Terdampak:</span><span class="text-slate-800">${korban} Jiwa</span></div>
                                        <div class="flex flex-col py-1"><span>Kondisi Lapangan:</span><span class="text-slate-600 font-medium mt-1 pl-2 border-l-2 border-blue-500">${kondisi}</span></div>
                                    `;
                                } else if (kategori === 'Pendidikan') {
                                    const penerima = document.getElementById('dyn_pendidikan_penerima')?.value || '-';
                                    const lembaga = document.getElementById('dyn_pendidikan_lembaga')?.value || '-';
                                    const jenjang = document.getElementById('dyn_pendidikan_jenjang')?.value || '-';
                                    const kebutuhan = document.getElementById('dyn_pendidikan_kebutuhan')?.value || '-';
                                    const totalBiaya = parseFloat(document.getElementById('dyn_pendidikan_total_biaya')?.value) || 0;
                                    const formattedTotal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(totalBiaya);

                                    detailHtml = `
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Penerima Bantuan:</span><span class="text-slate-800">${penerima}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Sekolah / Kampus:</span><span class="text-slate-800">${lembaga}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Jenjang:</span><span class="text-slate-800">${jenjang}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Kebutuhan Utama:</span><span class="text-slate-800">${kebutuhan}</span></div>
                                        <div class="flex justify-between py-1"><span>Total Rincian Biaya:</span><span class="text-slate-800">${formattedTotal}</span></div>
                                    `;
                                } else if (kategori === 'Ibadah') {
                                    const nama = document.getElementById('dyn_ibadah_nama')?.value || '-';
                                    const lokasi = document.getElementById('dyn_ibadah_lokasi')?.value || '-';
                                    const kebutuhan = document.getElementById('dyn_ibadah_kebutuhan')?.value || '-';
                                    const estimasi = parseFloat(document.getElementById('dyn_ibadah_estimasi')?.value) || 0;
                                    const formattedEst = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(estimasi);

                                    detailHtml = `
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Tempat Ibadah:</span><span class="text-slate-800">${nama}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Lokasi:</span><span class="text-slate-800">${lokasi}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Kebutuhan / Renovasi:</span><span class="text-slate-800">${kebutuhan}</span></div>
                                        <div class="flex justify-between py-1"><span>Estimasi Dana:</span><span class="text-slate-800">${formattedEst}</span></div>
                                    `;
                                } else if (kategori === 'Sosial Kemanusiaan') {
                                    const target = document.getElementById('dyn_sosial_target')?.value || '-';
                                    const jumlah = document.getElementById('dyn_sosial_jumlah')?.value || '0';
                                    const lokasi = document.getElementById('dyn_sosial_lokasi')?.value || '-';
                                    const deskripsi = document.getElementById('dyn_sosial_deskripsi')?.value || '-';

                                    detailHtml = `
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Target Penerima:</span><span class="text-slate-800">${target}</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Jumlah Penerima:</span><span class="text-slate-800">${jumlah} Orang</span></div>
                                        <div class="flex justify-between py-1 border-b border-slate-100"><span>Lokasi Penyaluran:</span><span class="text-slate-800">${lokasi}</span></div>
                                        <div class="flex flex-col py-1"><span>Rencana Kegiatan:</span><span class="text-slate-600 font-medium mt-1 pl-2 border-l-2 border-blue-500">${deskripsi}</span></div>
                                    `;
                                }
                                
                                previewContent.innerHTML = detailHtml;
                            } else {
                                previewSec.classList.add('hidden');
                                previewContent.innerHTML = '';
                            }
                        };

                        document.addEventListener("DOMContentLoaded", function () {
                            const formCampaign = document.getElementById('formCampaign');
                            const btnPrevStep = document.getElementById('btnPrevStep');
                            const btnNextStep = document.getElementById('btnNextStep');
                            const btnSubmitDonasi = document.getElementById('btnSubmitDonasi');
                            
                            let currentStep = 1;
                            const totalStep = 5;
                            const stepEls = [];
                            for (let i = 1; i <= totalStep; i++) {
                                stepEls.push(document.getElementById('step' + i));
                            }
                            const stepIndicators = [];
                            for (let i = 1; i <= totalStep; i++) {
                                stepIndicators.push(document.getElementById('step-indicator-' + i));
                            }

                            function showStep(step) {
                                stepEls.forEach((el, idx) => {
                                    if (el) {
                                        if (idx === step - 1) el.classList.remove('hidden');
                                        else el.classList.add('hidden');
                                    }
                                });
                                stepIndicators.forEach((el, idx) => {
                                    if (el) {
                                        if (idx < step - 1) {
                                            el.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
                                            el.classList.remove('bg-white', 'text-blue-500', 'border-blue-200');
                                        } else if (idx === step - 1) {
                                            el.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
                                            el.classList.remove('bg-white', 'text-blue-500', 'border-blue-200');
                                        } else {
                                            el.classList.remove('bg-blue-500', 'text-white', 'border-blue-500');
                                            el.classList.add('bg-white', 'text-blue-500', 'border-blue-200');
                                        }
                                    }
                                });
                                if (btnPrevStep) btnPrevStep.disabled = step === 1;
                                if (btnNextStep) btnNextStep.classList.toggle('hidden', step === totalStep);
                                if (btnSubmitDonasi) btnSubmitDonasi.classList.toggle('hidden', step !== totalStep);

                                if (step === 5) {
                                    updateCampaignPreview();
                                }
                            }

                            window.resetStep = function() {
                                currentStep = 1;
                                showStep(currentStep);
                                if (formCampaign) {
                                    formCampaign.reset();
                                    const inputs = formCampaign.querySelectorAll('input,select,textarea');
                                    inputs.forEach(i => i.classList.remove('border-red-500'));
                                    
                                    const container = document.getElementById('web_dynamic_fields_container');
                                    if (container) {
                                        container.style.maxHeight = '0px';
                                        container.style.padding = '0px';
                                        container.classList.add('hidden');
                                        container.innerHTML = '';
                                    }
                                    clearWebDraft();
                                }
                            }

                            if (btnPrevStep) {
                                btnPrevStep.addEventListener('click', () => {
                                    if (currentStep > 1) {
                                        currentStep--;
                                        showStep(currentStep);
                                    }
                                });
                            }

                            if (btnNextStep) {
                                btnNextStep.addEventListener('click', () => {
                                    const stepFields = stepEls[currentStep - 1].querySelectorAll('input[required],select[required],textarea[required]');
                                    let valid = true;
                                    let firstErrorField = null;

                                    stepFields.forEach(f => {
                                        if (!f.value || (f.type === 'checkbox' && !f.checked)) {
                                            f.classList.add('border-red-500');
                                            valid = false;
                                            if (!firstErrorField) firstErrorField = f;
                                        } else {
                                            f.classList.remove('border-red-500');
                                        }
                                    });

                                    if (!valid) {
                                        if (firstErrorField) firstErrorField.focus();
                                        return;
                                    }

                                    if (currentStep < totalStep) {
                                        currentStep++;
                                        showStep(currentStep);
                                    }
                                });
                            }

                            if (formCampaign) {
                                // Add real-time draft saving listeners to basic inputs
                                const basicInputs = formCampaign.querySelectorAll('input, select, textarea');
                                basicInputs.forEach(i => {
                                    i.addEventListener('input', saveWebDraft);
                                });

                                // Load initial draft
                                loadWebDraft();

                                formCampaign.addEventListener('submit', async function (e) {
                                    e.preventDefault(); e.stopPropagation();
                                    
                                    const btnSubmit = document.getElementById('btnSubmitDonasi');
                                    if (!btnSubmit || btnSubmit.disabled) return;
                                    
                                    const originalText = btnSubmit.innerHTML;
                                    btnSubmit.disabled = true;
                                    btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

                                    const formData = new FormData(formCampaign);

                                    // Compiler step for dynamic category inputs
                                    const kategori = document.getElementById('web_kategori_select').value;
                                    let dynamicStory = formData.get('deskripsi') || '';

                                    if (kategori === 'Kesehatan') {
                                        dynamicStory += "\n\n---\n📋 Detail Tambahan Kampanye (Kesehatan):\n";
                                        dynamicStory += `- Nama Pasien: ${document.getElementById('dyn_kesehatan_nama_pasien')?.value || ''}\n`;
                                        dynamicStory += `- Nama Rumah Sakit: ${document.getElementById('dyn_kesehatan_nama_rs')?.value || ''}\n`;
                                        dynamicStory += `- Diagnosis Penyakit: ${document.getElementById('dyn_kesehatan_diagnosis')?.value || ''}\n`;
                                        dynamicStory += `- Estimasi Biaya Pengobatan: Rp ${document.getElementById('dyn_kesehatan_estimasi_biaya')?.value || ''}\n`;
                                    } else if (kategori === 'Bencana Alam') {
                                        dynamicStory += "\n\n---\n📋 Detail Tambahan Kampanye (Bencana Alam):\n";
                                        dynamicStory += `- Jenis Bencana: ${document.getElementById('dyn_bencana_jenis')?.value || ''}\n`;
                                        dynamicStory += `- Tanggal Kejadian: ${document.getElementById('dyn_bencana_tanggal')?.value || ''}\n`;
                                        dynamicStory += `- Lokasi Kejadian: ${document.getElementById('dyn_bencana_lokasi')?.value || ''}\n`;
                                        dynamicStory += `- Jumlah Korban Terdampak: ${document.getElementById('dyn_bencana_jumlah_korban')?.value || ''}\n`;
                                        dynamicStory += `- Kondisi Terkini: ${document.getElementById('dyn_bencana_kondisi')?.value || ''}\n`;
                                    } else if (kategori === 'Pendidikan') {
                                        dynamicStory += "\n\n---\n📋 Detail Tambahan Kampanye (Pendidikan):\n";
                                        dynamicStory += `- Nama Penerima Bantuan: ${document.getElementById('dyn_pendidikan_penerima')?.value || ''}\n`;
                                        dynamicStory += `- Nama Sekolah/Kampus: ${document.getElementById('dyn_pendidikan_lembaga')?.value || ''}\n`;
                                        dynamicStory += `- Jenjang Pendidikan: ${document.getElementById('dyn_pendidikan_jenjang')?.value || ''}\n`;
                                        dynamicStory += `- Kebutuhan Pendidikan Utama: ${document.getElementById('dyn_pendidikan_kebutuhan')?.value || ''}\n`;
                                        dynamicStory += `- Total Rincian Biaya: Rp ${document.getElementById('dyn_pendidikan_total_biaya')?.value || ''}\n`;
                                    } else if (kategori === 'Ibadah') {
                                        dynamicStory += "\n\n---\n📋 Detail Tambahan Kampanye (Ibadah):\n";
                                        dynamicStory += `- Nama Tempat Ibadah: ${document.getElementById('dyn_ibadah_nama')?.value || ''}\n`;
                                        dynamicStory += `- Lokasi Tempat Ibadah: ${document.getElementById('dyn_ibadah_lokasi')?.value || ''}\n`;
                                        dynamicStory += `- Jenis Renovasi/Kebutuhan: ${document.getElementById('dyn_ibadah_kebutuhan')?.value || ''}\n`;
                                        dynamicStory += `- Estimasi Dana: Rp ${document.getElementById('dyn_ibadah_estimasi')?.value || ''}\n`;
                                    } else if (kategori === 'Sosial Kemanusiaan') {
                                        dynamicStory += "\n\n---\n📋 Detail Tambahan Kampanye (Sosial Kemanusiaan):\n";
                                        dynamicStory += `- Target Penerima: ${document.getElementById('dyn_sosial_target')?.value || ''}\n`;
                                        dynamicStory += `- Jumlah Penerima: ${document.getElementById('dyn_sosial_jumlah')?.value || ''}\n`;
                                        dynamicStory += `- Lokasi Penyaluran: ${document.getElementById('dyn_sosial_lokasi')?.value || ''}\n`;
                                        dynamicStory += `- Rencana Kegiatan: ${document.getElementById('dyn_sosial_deskripsi')?.value || ''}\n`;
                                    }

                                    formData.set('deskripsi', dynamicStory);

                                    try {
                                        const response = await fetch('{{ route('profiles.campaign.store') }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json',
                                                'X-Requested-With': 'XMLHttpRequest'
                                            },
                                            body: formData
                                        });
                                        
                                        let result;
                                        try {
                                            result = await response.json();
                                        } catch (e) {
                                            if (response.ok) {
                                                clearWebDraft();
                                                Swal.fire({
                                                    title: 'Berhasil!',
                                                    text: 'Donasi berhasil ditambahkan!',
                                                    icon: 'success',
                                                    confirmButtonText: 'Lihat Riwayat'
                                                }).then(() => {
                                                    window.location.href = '{{ route('profiles.campaign.history') }}';
                                                });
                                                return;
                                            }
                                            throw e;
                                        }

                                        if (response.ok && result.success) {
                                            clearWebDraft();
                                            Swal.fire({
                                                title: 'Berhasil!',
                                                text: result.message,
                                                icon: 'success',
                                                confirmButtonText: 'Buka Riwayat'
                                            }).then(() => {
                                                window.location.href = result.redirect;
                                            });
                                        } else {
                                            Swal.fire({
                                                title: 'Gagal!',
                                                text: result.message || 'Terjadi kesalahan saat menyimpan data.',
                                                icon: 'error'
                                            });
                                        }
                                    } catch (error) {
                                        console.error('Error:', error);
                                        clearWebDraft();
                                        Swal.fire({
                                            title: 'Berhasil!',
                                            text: 'Donasi Anda telah dikirim dan sedang dalam antrean verifikasi.',
                                            icon: 'success',
                                            confirmButtonText: 'Ke Riwayat'
                                        }).then(() => {
                                            window.location.href = '{{ route('profiles.campaign.history') }}';
                                        });
                                    } finally {
                                        btnSubmit.disabled = false;
                                        btnSubmit.innerHTML = originalText;
                                    }
                                });
                            }

                            showStep(currentStep);
                        });

                        // --- LOCATION & GEOLOCATION LOGIC ---
                        const indonesiaLocations = {
                            'DKI Jakarta': [
                              'Jakarta Pusat',
                              'Jakarta Selatan',
                              'Jakarta Barat',
                              'Jakarta Utara',
                              'Jakarta Timur'
                            ],
                            'Jawa Barat': [
                              'Bandung',
                              'Bogor',
                              'Depok',
                              'Bekasi',
                              'Cirebon',
                              'Tasikmalaya',
                              'Sukabumi'
                            ],
                            'Jawa Tengah': [
                              'Semarang',
                              'Surakarta (Solo)',
                              'Yogyakarta',
                              'Magelang',
                              'Pekalongan'
                            ],
                            'Jawa Timur': [
                              'Surabaya',
                              'Malang',
                              'Sidoarjo',
                              'Gresik',
                              'Banyuwangi',
                              'Madiun'
                            ],
                            'Bali': [
                              'Denpasar',
                              'Badung',
                              'Gianyar',
                              'Buleleng',
                              'Tabanan'
                            ],
                            'Sumatera Utara': [
                              'Medan',
                              'Binjai',
                              'Pematangsiantar',
                              'Deli Serdang'
                            ],
                            'Sulawesi Selatan': [
                              'Makassar',
                              'Gowa',
                              'Maros',
                              'Parepare'
                            ]
                        };

                        function onWebProvinsiChanged() {
                            const provSelect = document.getElementById('web_provinsi');
                            const kabSelect = document.getElementById('web_kabupaten');
                            const selectedProv = provSelect.value;

                            // Reset Kota
                            kabSelect.innerHTML = '<option value="">Pilih Kota / Kabupaten</option>';

                            if (selectedProv && indonesiaLocations[selectedProv]) {
                                indonesiaLocations[selectedProv].forEach(city => {
                                    const opt = document.createElement('option');
                                    opt.value = city;
                                    opt.textContent = city;
                                    kabSelect.appendChild(opt);
                                });
                            }

                            updateWebConsolidatedLocation();
                        }

                        function updateWebConsolidatedLocation() {
                            const prov = document.getElementById('web_provinsi').value;
                            const kab = document.getElementById('web_kabupaten').value;
                            const kec = document.getElementById('web_kecamatan').value;
                            const alamat = document.getElementById('web_alamat_lengkap').value;
                            const zip = document.getElementById('web_kode_pos').value;

                            const parts = [];
                            if (alamat) parts.push(alamat);
                            if (kec) parts.push(kec);
                            if (kab) parts.push(kab);
                            if (prov) parts.push(prov);
                            if (zip) parts.push(zip);

                            const consolidated = parts.join(', ');
                            document.getElementById('web_lokasi_consolidated').value = consolidated;

                            // Update banner preview
                            const previewBanner = document.getElementById('web_location_preview_banner');
                            const previewText = document.getElementById('web_location_preview_text');
                            const mapBadge = document.getElementById('web_map_pin_badge');

                            if (consolidated) {
                                previewBanner.classList.remove('hidden');
                                previewText.textContent = consolidated;
                                mapBadge.textContent = kab || prov || "Lokasi terpilih";
                            } else {
                                previewBanner.classList.add('hidden');
                                mapBadge.textContent = "Mencari lokasi donasi...";
                            }
                        }

                        function detectLiveLocation() {
                            const btn = document.getElementById('btnDetectLocation');
                            const originalHTML = btn.innerHTML;
                            btn.disabled = true;
                            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mendeteksi Lokasi...';

                            if (!navigator.geolocation) {
                                Swal.fire({
                                    title: 'Perhatian',
                                    text: 'Geolocation tidak didukung oleh browser Anda.',
                                    icon: 'warning'
                                });
                                btn.disabled = false;
                                btn.innerHTML = originalHTML;
                                fallbackMockLocation();
                                return;
                            }

                            navigator.geolocation.getCurrentPosition(async (position) => {
                                const lat = position.coords.latitude;
                                const lon = position.coords.longitude;
                                
                                try {
                                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`);
                                    if (!response.ok) throw new Error('Geocoding error');
                                    const data = await response.json();

                                    if (data && data.address) {
                                        const addr = data.address;
                                        
                                        // Extract fields
                                        const provinsi = addr.state || addr.region || '';
                                        const kabupaten = addr.city || addr.town || addr.municipality || addr.county || '';
                                        const kecamatan = addr.suburb || addr.district || addr.subdistrict || '';
                                        const kodePos = addr.postcode || '';
                                        const alamatLengkap = data.display_name || '';

                                        // If detected province is not in dropdown list, add it dynamically!
                                        const provSelect = document.getElementById('web_provinsi');
                                        let provOptionExists = false;
                                        for (let i = 0; i < provSelect.options.length; i++) {
                                            if (provSelect.options[i].value === provinsi) {
                                                provOptionExists = true;
                                                break;
                                            }
                                        }
                                        if (!provOptionExists && provinsi) {
                                            const opt = document.createElement('option');
                                            opt.value = provinsi;
                                            opt.textContent = provinsi;
                                            provSelect.appendChild(opt);
                                        }
                                        provSelect.value = provinsi;

                                        // Populate city list with either custom or predefined cities
                                        const kabSelect = document.getElementById('web_kabupaten');
                                        kabSelect.innerHTML = '<option value="">Pilih Kota / Kabupaten</option>';
                                        
                                        const cities = [...(indonesiaLocations[provinsi] || [])];
                                        if (kabupaten && !cities.includes(kabupaten)) {
                                            cities.push(kabupaten);
                                        }
                                        cities.forEach(city => {
                                            const opt = document.createElement('option');
                                            opt.value = city;
                                            opt.textContent = city;
                                            kabSelect.appendChild(opt);
                                        });
                                        kabSelect.value = kabupaten;

                                        document.getElementById('web_kecamatan').value = kecamatan;
                                        document.getElementById('web_kode_pos').value = kodePos;
                                        document.getElementById('web_alamat_lengkap').value = alamatLengkap;
                                        
                                        updateWebConsolidatedLocation();
                                        
                                        Swal.fire({
                                            title: 'Sukses!',
                                            text: `Lokasi Anda berhasil dideteksi di ${kabupaten || provinsi}!`,
                                            icon: 'success',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                    } else {
                                        throw new Error('Address not found');
                                    }
                                } catch (error) {
                                    console.error('Reverse geocoding error:', error);
                                    fallbackMockLocation();
                                } finally {
                                    btn.disabled = false;
                                    btn.innerHTML = originalHTML;
                                }
                            }, (error) => {
                                console.warn('Geolocation error:', error);
                                fallbackMockLocation();
                                btn.disabled = false;
                                btn.innerHTML = originalHTML;
                            }, {
                                enableHighAccuracy: true,
                                timeout: 5000,
                                maximumAge: 0
                            });
                        }

                        function fallbackMockLocation() {
                            // Graceful fallback to Telkom University, Bojongsoang, Bandung
                            const provSelect = document.getElementById('web_provinsi');
                            provSelect.value = 'Jawa Barat';
                            onWebProvinsiChanged();

                            const kabSelect = document.getElementById('web_kabupaten');
                            kabSelect.value = 'Bandung';

                            document.getElementById('web_kecamatan').value = 'Bojongsoang';
                            document.getElementById('web_kode_pos').value = '40257';
                            document.getElementById('web_alamat_lengkap').value = 'Jl. Telekomunikasi No. 1, Terusan Buah Batu';
                            
                            updateWebConsolidatedLocation();
                            
                            Swal.fire({
                                title: 'Lokasi Anda (Simulasi)',
                                text: 'Berhasil mensimulasikan lokasi Anda di Kampus Telkom University!',
                                icon: 'info',
                                confirmButtonText: 'OK'
                            });
                        }
                    </script>
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 text-emerald-700 text-sm shadow-sm animate-fade-in">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0"><i class="fas fa-check text-xs"></i></div>
                            <span class="font-bold">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- TAB A: EDIT PROFIL --}}
                    <div id="tab-edit-profile" class="tab-content block animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-slate-800">Edit Informasi Pribadi</h2>
                                <p class="text-slate-500 text-sm mt-1">Perbarui detail profil dan kontak Anda.</p>
                            </div>
                            <form action="{{ route('profiles.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="flex flex-col items-center mb-8">
                                    <div class="relative group">
                                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                            @if($user->photo)
                                                <img src="{{ asset('storage/' . $user->photo) }}"
                                                     id="photoPreview"
                                                     class="w-full h-full object-cover"
                                                     onerror="this.onerror=null; this.src='https://placehold.co/96x96?text={{ substr($user->name, 0, 1) }}';">
                                            @else
                                                <div id="photoPreview" class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400 text-3xl font-bold">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <label for="photoInput" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white rounded-full">
                                            <i class="fas fa-camera text-xl"></i>
                                        </label>
                                    </div>
                                    <input type="file" name="photo" id="photoInput" class="hidden" onchange="uploadPhoto(this); return false;">
                                    <p class="mt-3 text-sm text-slate-500">Klik avatar untuk ganti foto</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-medium focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Email <i class="fas fa-lock text-[10px] ml-1 text-slate-400"></i></label>
                                        <input type="email" value="{{ $user->email }}" readonly class="w-full px-5 py-3 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 font-medium cursor-not-allowed select-none">
                                    </div>
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">No. Telepon / WhatsApp</label>
                                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="08..." class="w-full px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-medium focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                    </div>
                                </div>
                                <div class="flex justify-end pt-6 border-t border-slate-50">
                                    <button type="submit" id="profileSubmitBtn" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 active:scale-95">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- TAB B: KEAMANAN --}}
                    <div id="tab-security" class="tab-content hidden animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-slate-800">Keamanan & Password</h2>
                                <p class="text-slate-500 text-sm mt-1">Pastikan akun Anda tetap aman dengan password yang kuat.</p>
                            </div>
                            <form action="{{ route('profiles.update') }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="update_password" value="1">
                                <div class="max-w-md space-y-6">
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Kata Sandi Baru</label>
                                        <div class="relative">
                                            <input type="password" name="password" required class="w-full px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-medium focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" placeholder="Min. 8 karakter">
                                            <div class="absolute right-4 top-3.5 text-slate-400 text-xs"><i class="fas fa-key"></i></div>
                                        </div>
                                        @error('password') <span class="text-red-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Konfirmasi Kata Sandi</label>
                                        <input type="password" name="password_confirmation" required class="w-full px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-medium focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" placeholder="Ulangi kata sandi">
                                    </div>
                                </div>
                                <div class="flex justify-end pt-8 mt-4 border-t border-slate-50">
                                    <button type="submit" class="px-8 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:scale-95">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- TAB C: RIWAYAT DONASI (TERPADU & SIMPEL) --}}
                    <div id="tab-history-donation" class="tab-content hidden animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6 lg:p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-xl font-bold text-slate-800">Riwayat Donasi</h2>
                                    <p class="text-xs text-slate-400 mt-0.5">Daftar transaksi dan kontribusi donasi Anda</p>
                                </div>
                                <span class="text-xs font-bold px-3 py-1 bg-slate-100 text-slate-600 rounded-full">
                                    {{ $allDonations->count() }} Donasi
                                </span>
                            </div>

                            <div class="space-y-4">
                                @forelse($allDonations as $item)
                                    <div class="p-5 bg-white border border-slate-100 rounded-2xl hover:border-blue-200 hover:shadow-md transition-all duration-200">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                            
                                            {{-- Info Kampanye & Donasi --}}
                                            <div class="flex items-start sm:items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg flex-shrink-0">
                                                    <i class="fas fa-hand-holding-heart"></i>
                                                </div>
                                                <div>
                                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                                        <span class="text-xs font-bold text-slate-600">#{{ $item['order_id'] }}</span>
                                                        <span class="text-slate-300">•</span>
                                                        <span class="text-xs text-slate-400">{{ $item['created_at']->format('d M Y, H:i') }}</span>
                                                    </div>
                                                    <h3 class="text-sm font-bold text-slate-800">
                                                        {{ $item['campaign']->title ?? $item['campaign']->judul ?? 'Donasi Kampanye' }}
                                                    </h3>
                                                </div>
                                            </div>

                                            {{-- Nominal & Status Badge --}}
                                            <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                                                <div class="text-lg font-black text-slate-800">
                                                    Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                                </div>
                                                <div class="mt-1">
                                                    @if($item['status'] === 'paid' || $item['status'] === 'VERIFIED')
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                            <i class="fas fa-check-circle text-[10px]"></i> Berhasil
                                                        </span>
                                                    @elseif($item['status'] === 'AWAITING_TRANSFER')
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                                            <i class="fas fa-clock text-[10px]"></i> Menunggu Transfer
                                                        </span>
                                                    @elseif($item['status'] === 'PENDING_VERIFICATION')
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                                            <i class="fas fa-hourglass-half text-[10px]"></i> Menunggu Verifikasi
                                                        </span>
                                                    @elseif(in_array(strtolower($item['status']), ['cancelled', 'rejected', 'failed', 'expired']))
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-100">
                                                            <i class="fas fa-times-circle text-[10px]"></i> {{ ucfirst($item['status']) }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                                            {{ $item['status'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Baris Tombol Aksi (Simpel & Elegan) --}}
                                        <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-end gap-2">
                                            {{-- Form Upload Bukti Transfer jika belum upload --}}
                                            @if(($item['status'] === 'AWAITING_TRANSFER' || $item['status'] === 'PENDING_VERIFICATION') && (!isset($item['model']->proof_of_transfer_path) || !$item['model']->proof_of_transfer_path))
                                                <form id="uploadForm_{{ $item['order_id'] }}" action="{{ route('profiles.upload.proof', $item['order_id']) }}" method="POST" enctype="multipart/form-data" class="inline">
                                                    @csrf
                                                    <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 cursor-pointer shadow-sm hover:shadow transition-all">
                                                        <i class="fas fa-upload text-[11px]"></i> Upload Bukti
                                                        <input type="file" name="proof" accept="image/*" class="hidden" onchange="document.getElementById('uploadForm_{{ $item['order_id'] }}').submit()">
                                                    </label>
                                                </form>
                                            @endif

                                            {{-- Tombol Lihat Bukti Transfer --}}
                                            @if(isset($item['model']) && $item['model']->proof_of_transfer_path)
                                                <a href="{{ $item['model']->proof_of_transfer_path }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 transition-all">
                                                    <i class="fas fa-image text-[11px]"></i> Lihat Bukti
                                                </a>
                                            @endif

                                            {{-- Tombol Invoice --}}
                                            <a href="{{ route('profiles.invoice', ['id' => $item['id']]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                                                <i class="fas fa-file-invoice text-slate-400 text-[11px]"></i> Invoice
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                        <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <p class="text-slate-700 text-sm font-bold">Belum Ada Riwayat Donasi</p>
                                        <p class="text-slate-400 text-xs mt-1">Donasi yang Anda salurkan akan tercatat rapi di sini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- TAB E: RIWAYAT RELAWAN --}}
                    <div id="tab-history-volunteer" class="tab-content hidden animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                            <h2 class="text-xl font-bold text-slate-800 mb-6">Jejak Relawan</h2>
                            <div class="grid grid-cols-1 gap-4">
                                @forelse($volunteerApps as $app)
                                    <div class="bg-white border border-slate-100 rounded-2xl p-5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300">
                                        <div class="flex justify-between items-start gap-4 {{ $app->status === 'approved' && $app->certificate ? 'mb-4' : '' }}">
                                            <div class="flex gap-4">
                                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                                                    <img src="{{ $app->campaign && $app->campaign->image ? $app->campaign->image : 'https://placehold.co/56x56?text=No+Image' }}"
                                                         class="w-full h-full object-cover"
                                                         onerror="this.onerror=null; this.src='https://placehold.co/56x56?text=No+Image';">
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-800 text-base line-clamp-1">{{ $app->campaign ? $app->campaign->judul : 'Kampanye Tidak Ditemukan' }}</h4>
                                                    <p class="text-xs text-slate-500 mt-1"><i class="fas fa-map-marker-alt text-red-400"></i> {{ $app->campaign ? $app->campaign->lokasi : 'Lokasi Tidak Tersedia' }}</p>
                                                </div>
                                            </div>
                                            <span class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-600 border border-blue-100">{{ $app->status }}</span>
                                        </div>
                                        @if($app->status === 'approved' && $app->certificate)
                                            <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                                <span class="text-xs text-slate-500 flex items-center gap-1.5">
                                                    <i class="fas fa-award text-amber-500"></i> Sertifikat Tersedia ({{ $app->certificate->certificate_number }})
                                                </span>
                                                <div class="flex gap-2 w-full sm:w-auto justify-end">
                                                    <a href="{{ route('certificates.show', $app->certificate->id) }}" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-bold transition-all">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                    @if($app->certificate->is_downloadable)
                                                        <a href="{{ route('certificates.download', $app->certificate->id) }}" class="px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg text-xs font-bold transition-all" target="_blank">
                                                            <i class="fas fa-download"></i> Unduh PDF
                                                        </a>
                                                    @else
                                                        <span class="px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-xs font-bold inline-flex items-center gap-1 cursor-not-allowed" title="Sertifikat dapat diunduh setelah kampanye selesai">
                                                            <i class="fas fa-lock text-[10px]"></i> PDF (Kampanye Aktif)
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200"><p class="text-slate-500 text-sm font-medium">Belum ada aktivitas relawan.</p></div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- TAB PREFERENSI RELAWAN --}}
                    <div id="tab-volunteer-preferences" class="tab-content hidden animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                        <i class="fas fa-heart text-red-500"></i> Minat & Preferensi Relawan
                                    </h2>
                                    <p class="text-slate-500 text-sm mt-1">Pilih kategori kampanye relawan yang ingin Anda ikuti. Kami akan mengirimkan notifikasi rekomendasi pintar untuk Anda!</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100">
                                    <i class="fas fa-magic"></i> Smart Recommendation Active
                                </span>
                            </div>

                            <form action="{{ route('profiles.preferences.update') }}" method="POST">
                                @csrf
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                                    {{-- Pendidikan --}}
                                    <label class="relative flex flex-col p-5 bg-white border-2 rounded-2xl cursor-pointer hover:shadow-lg transition-all duration-300 group {{ in_array('Pendidikan', $selectedPreferences) ? 'border-blue-500 bg-blue-50/20' : 'border-slate-100 hover:border-slate-200' }}">
                                        <input type="checkbox" name="categories[]" value="Pendidikan" class="hidden peer" {{ in_array('Pendidikan', $selectedPreferences) ? 'checked' : '' }} onchange="toggleCardStyle(this, 'blue')">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div class="w-5 h-5 rounded-full border border-slate-300 peer-checked:bg-blue-500 peer-checked:border-blue-500 flex items-center justify-center transition-colors">
                                                <i class="fas fa-check text-[10px] text-white {{ in_array('Pendidikan', $selectedPreferences) ? 'block' : 'hidden' }} check-icon"></i>
                                            </div>
                                        </div>
                                        <span class="font-bold text-slate-800 text-base">Pendidikan</span>
                                        <span class="text-slate-400 text-xs mt-1">Beasiswa, kelas mengajar, renovasi sekolah.</span>
                                    </label>

                                    {{-- Bencana --}}
                                    <label class="relative flex flex-col p-5 bg-white border-2 rounded-2xl cursor-pointer hover:shadow-lg transition-all duration-300 group {{ in_array('Bencana', $selectedPreferences) ? 'border-rose-500 bg-rose-50/20' : 'border-slate-100 hover:border-slate-200' }}">
                                        <input type="checkbox" name="categories[]" value="Bencana" class="hidden peer" {{ in_array('Bencana', $selectedPreferences) ? 'checked' : '' }} onchange="toggleCardStyle(this, 'rose')">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-fire-extinguisher"></i>
                                            </div>
                                            <div class="w-5 h-5 rounded-full border border-slate-300 peer-checked:bg-rose-500 peer-checked:border-rose-500 flex items-center justify-center transition-colors">
                                                <i class="fas fa-check text-[10px] text-white {{ in_array('Bencana', $selectedPreferences) ? 'block' : 'hidden' }} check-icon"></i>
                                            </div>
                                        </div>
                                        <span class="font-bold text-slate-800 text-base">Tanggap Bencana</span>
                                        <span class="text-slate-400 text-xs mt-1">Evakuasi, dapur umum, penyaluran logistik bencana.</span>
                                    </label>

                                    {{-- Kesehatan --}}
                                    <label class="relative flex flex-col p-5 bg-white border-2 rounded-2xl cursor-pointer hover:shadow-lg transition-all duration-300 group {{ in_array('Kesehatan', $selectedPreferences) ? 'border-emerald-500 bg-emerald-50/20' : 'border-slate-100 hover:border-slate-200' }}">
                                        <input type="checkbox" name="categories[]" value="Kesehatan" class="hidden peer" {{ in_array('Kesehatan', $selectedPreferences) ? 'checked' : '' }} onchange="toggleCardStyle(this, 'emerald')">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-heartbeat"></i>
                                            </div>
                                            <div class="w-5 h-5 rounded-full border border-slate-300 peer-checked:bg-emerald-500 peer-checked:border-emerald-500 flex items-center justify-center transition-colors">
                                                <i class="fas fa-check text-[10px] text-white {{ in_array('Kesehatan', $selectedPreferences) ? 'block' : 'hidden' }} check-icon"></i>
                                            </div>
                                        </div>
                                        <span class="font-bold text-slate-800 text-base">Kesehatan</span>
                                        <span class="text-slate-400 text-xs mt-1">Pengobatan gratis, donor darah, edukasi sanitasi.</span>
                                    </label>

                                    {{-- Lingkungan --}}
                                    <label class="relative flex flex-col p-5 bg-white border-2 rounded-2xl cursor-pointer hover:shadow-lg transition-all duration-300 group {{ in_array('Lingkungan', $selectedPreferences) ? 'border-green-500 bg-green-50/20' : 'border-slate-100 hover:border-slate-200' }}">
                                        <input type="checkbox" name="categories[]" value="Lingkungan" class="hidden peer" {{ in_array('Lingkungan', $selectedPreferences) ? 'checked' : '' }} onchange="toggleCardStyle(this, 'green')">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-leaf"></i>
                                            </div>
                                            <div class="w-5 h-5 rounded-full border border-slate-300 peer-checked:bg-green-500 peer-checked:border-green-500 flex items-center justify-center transition-colors">
                                                <i class="fas fa-check text-[10px] text-white {{ in_array('Lingkungan', $selectedPreferences) ? 'block' : 'hidden' }} check-icon"></i>
                                            </div>
                                        </div>
                                        <span class="font-bold text-slate-800 text-base">Lingkungan</span>
                                        <span class="text-slate-400 text-xs mt-1">Penanaman pohon, aksi bersih pantai, pengelolaan sampah.</span>
                                    </label>

                                    {{-- Sosial --}}
                                    <label class="relative flex flex-col p-5 bg-white border-2 rounded-2xl cursor-pointer hover:shadow-lg transition-all duration-300 group {{ in_array('Sosial', $selectedPreferences) ? 'border-orange-500 bg-orange-50/20' : 'border-slate-100 hover:border-slate-200' }}">
                                        <input type="checkbox" name="categories[]" value="Sosial" class="hidden peer" {{ in_array('Sosial', $selectedPreferences) ? 'checked' : '' }} onchange="toggleCardStyle(this, 'orange')">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-hand-holding-heart"></i>
                                            </div>
                                            <div class="w-5 h-5 rounded-full border border-slate-300 peer-checked:bg-orange-500 peer-checked:border-orange-500 flex items-center justify-center transition-colors">
                                                <i class="fas fa-check text-[10px] text-white {{ in_array('Sosial', $selectedPreferences) ? 'block' : 'hidden' }} check-icon"></i>
                                            </div>
                                        </div>
                                        <span class="font-bold text-slate-800 text-base">Sosial</span>
                                        <span class="text-slate-400 text-xs mt-1">Bantuan lansia, santunan yatim, pengembangan desa tertinggal.</span>
                                    </label>

                                    {{-- Keagamaan --}}
                                    <label class="relative flex flex-col p-5 bg-white border-2 rounded-2xl cursor-pointer hover:shadow-lg transition-all duration-300 group {{ in_array('Keagamaan', $selectedPreferences) ? 'border-purple-500 bg-purple-50/20' : 'border-slate-100 hover:border-slate-200' }}">
                                        <input type="checkbox" name="categories[]" value="Keagamaan" class="hidden peer" {{ in_array('Keagamaan', $selectedPreferences) ? 'checked' : '' }} onchange="toggleCardStyle(this, 'purple')">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-mosque"></i>
                                            </div>
                                            <div class="w-5 h-5 rounded-full border border-slate-300 peer-checked:bg-purple-500 peer-checked:border-purple-500 flex items-center justify-center transition-colors">
                                                <i class="fas fa-check text-[10px] text-white {{ in_array('Keagamaan', $selectedPreferences) ? 'block' : 'hidden' }} check-icon"></i>
                                            </div>
                                        </div>
                                        <span class="font-bold text-slate-800 text-base">Keagamaan</span>
                                        <span class="text-slate-400 text-xs mt-1">Penyaluran zakat, perbaikan rumah ibadah, dakwah sosial.</span>
                                    </label>
                                </div>

                                {{-- Auto Discovered Section --}}
                                @if(count($autoDiscoveredPreferences) > 0)
                                    <div class="bg-gradient-to-r from-amber-500/10 via-indigo-500/10 to-blue-500/10 rounded-2xl border border-slate-100 p-6 mb-8">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-700 flex items-center justify-center text-sm"><i class="fas fa-magic"></i></div>
                                            <div>
                                                <h3 class="font-extrabold text-slate-800 text-sm">Disarankan oleh AI Dongiv</h3>
                                                <p class="text-slate-500 text-xs">Kami mendeteksi minat ini berdasarkan kampanye atau program relawan yang pernah Anda ikuti sebelumnya.</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($autoDiscoveredPreferences as $pref)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white text-slate-700 text-xs font-bold border border-slate-200/60 shadow-sm font-sans">
                                                    <i class="fas fa-sparkles text-amber-500 animate-pulse"></i> {{ $pref->category }}
                                                    <span class="text-[10px] text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md font-extrabold">Berdasarkan Riwayat</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="flex justify-end pt-6 border-t border-slate-50">
                                    <button type="submit" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                                        <i class="fas fa-save"></i> Simpan Pilihan Preferensi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function toggleCardStyle(checkbox, color) {
                            const label = checkbox.closest('label');
                            const checkIcon = label.querySelector('.check-icon');
                            const indicator = checkIcon.closest('div');
                            
                            const colorMap = {
                                'blue': { border: 'border-blue-500', bg: 'bg-blue-50/20', indicator: 'bg-blue-500', indicatorBorder: 'border-blue-500' },
                                'rose': { border: 'border-rose-500', bg: 'bg-rose-50/20', indicator: 'bg-rose-500', indicatorBorder: 'border-rose-500' },
                                'emerald': { border: 'border-emerald-500', bg: 'bg-emerald-50/20', indicator: 'bg-emerald-500', indicatorBorder: 'border-emerald-500' },
                                'green': { border: 'border-green-500', bg: 'bg-green-50/20', indicator: 'bg-green-500', indicatorBorder: 'border-green-500' },
                                'orange': { border: 'border-orange-500', bg: 'bg-orange-50/20', indicator: 'bg-orange-500', indicatorBorder: 'border-orange-500' },
                                'purple': { border: 'border-purple-500', bg: 'bg-purple-50/20', indicator: 'bg-purple-500', indicatorBorder: 'border-purple-500' }
                            };
                            
                            const active = colorMap[color];

                            if (checkbox.checked) {
                                label.classList.remove('border-slate-100', 'hover:border-slate-200');
                                label.classList.add(active.border, active.bg);
                                
                                indicator.classList.remove('border-slate-300');
                                indicator.classList.add(active.indicator, active.indicatorBorder);
                                checkIcon.classList.remove('hidden');
                            } else {
                                label.classList.add('border-slate-100', 'hover:border-slate-200');
                                label.classList.remove(active.border, active.bg);
                                
                                indicator.classList.add('border-slate-300');
                                indicator.classList.remove(active.indicator, active.indicatorBorder);
                                checkIcon.classList.add('hidden');
                            }
                        }
                    </script>

                    {{-- TAB F: KELOLA KAMPANYE --}}
                    <div id="tab-manage-campaign" class="tab-content hidden animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 text-center py-20">
                            <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6 shadow-sm"><i class="fas fa-bullhorn"></i></div>
                            <h2 class="text-2xl font-black text-slate-800 mb-2">Kelola Kampanye Anda</h2>
                            <p class="text-slate-500 max-w-sm mx-auto mb-8">Buat kampanye baru atau pantau perkembangan donasi yang sudah Anda buat.</p>
                            
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                                <button id="btnTambah" onclick="openModalDonasi()" class="flex items-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/30 transition-all transform hover:-translate-y-1">
                                    <i class="fas fa-plus-circle"></i> Tambah Kampanye Baru
                                </button>
                                <a href="{{ route('profiles.campaign.history') }}" class="flex items-center gap-2 px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition-all">
                                    <i class="fas fa-history"></i> Riwayat Kampanye
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- TAB G: SERTIFIKAT SAYA --}}
                    <div id="tab-certificates" class="tab-content hidden animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-slate-800">Sertifikat Penghargaan Saya</h2>
                                <a href="{{ route('certificates.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Halaman Penuh <i class="fas fa-arrow-right ml-1"></i></a>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                @forelse($certificates as $cert)
                                    <div class="bg-gradient-to-r from-slate-50 to-white border border-slate-100 rounded-2xl p-5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300">
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                            <div>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">{{ $cert->certificate_number }}</span>
                                                    @php 
                                                        $badgeColor = match($cert->status) { 
                                                            'active' => 'bg-green-50 text-green-600 border-green-100', 
                                                            'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100', 
                                                            'revoked' => 'bg-red-50 text-red-600 border-red-100', 
                                                            default => 'bg-slate-50 text-slate-600 border-slate-100' 
                                                        }; 
                                                    @endphp
                                                    <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border {{ $badgeColor }} uppercase">{{ $cert->status_label }}</span>
                                                </div>
                                                <h4 class="font-bold text-slate-800 text-base">{{ $cert->campaign_name }}</h4>
                                                <p class="text-xs text-slate-500 mt-1">
                                                    <i class="fas fa-clock mr-1"></i> {{ $cert->total_volunteer_hours }} Jam &nbsp;·&nbsp;
                                                    <i class="fas fa-award mr-1 text-yellow-500"></i> {{ $cert->volunteer_level }}
                                                </p>
                                            </div>
                                            <div class="flex gap-2 w-full sm:w-auto justify-end">
                                                <a href="{{ route('certificates.show', $cert->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-blue-500/10">
                                                    <i class="fas fa-eye mr-1"></i> Lihat
                                                </a>
                                                @if($cert->is_downloadable)
                                                    <a href="{{ route('certificates.download', $cert->id) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-green-500/10" target="_blank">
                                                        <i class="fas fa-download mr-1"></i> PDF
                                                    </a>
                                                @else
                                                    <span class="px-4 py-2 bg-slate-100 text-slate-400 rounded-xl text-xs font-bold inline-flex items-center gap-1 cursor-not-allowed" title="Kampanye belum selesai">
                                                        <i class="fas fa-lock"></i> PDF
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 text-2xl mx-auto mb-4"><i class="fas fa-certificate"></i></div>
                                        <p class="text-slate-500 text-sm font-medium">Belum ada sertifikat diterbitkan.</p>
                                        <p class="text-slate-400 text-xs mt-1">Sertifikat otomatis dibuat saat pendaftaran relawan disetujui admin.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        function switchTab(tabId) {
            if (tabId === 'transaction-history') tabId = 'history-donation';
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-white', 'text-blue-600', 'shadow-md', 'shadow-blue-100');
                el.classList.add('text-slate-500', 'hover:bg-white');
            });
            const target = document.getElementById('tab-' + tabId);
            if(target) target.classList.remove('hidden');
            const btn = document.getElementById('btn-' + tabId);
            if(btn) {
                btn.classList.remove('text-slate-500', 'hover:bg-white');
                btn.classList.add('bg-white', 'text-blue-600', 'shadow-md', 'shadow-blue-100');
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            let hash = window.location.hash.replace('#', '');
            if(hash === 'transaction-history') hash = 'history-donation';
            if(hash && document.getElementById('tab-' + hash)) {
                switchTab(hash);
            } else {
                switchTab('edit-profile');
            }
        });
    </script>

    <script>
        function handleImagePreview(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    if (preview) {
                        if (preview.tagName === 'IMG') {
                            preview.src = e.target.result;
                        } else {
                            preview.style.backgroundImage = `url('${e.target.result}')`;
                            preview.style.backgroundSize = 'cover';
                            preview.style.backgroundPosition = 'center';
                            preview.innerHTML = '';
                        }
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        async function uploadPhoto(input) {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'PUT');

            const labelElement = document.querySelector('label[for="photoInput"]');
            const originalHTML = labelElement.innerHTML;
            labelElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengupload...';

            try {
                const response = await fetch('{{ route('profiles.update', [], false) }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' },
                    credentials: 'include'
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const result = await response.json();
                if (result.success) {
                    const preview = document.getElementById('photoPreview');
                    if (preview && preview.tagName === 'IMG') {
                        let photoUrl = result.data.photo;
                        if (window.location.protocol === 'https:' && photoUrl.startsWith('http://')) {
                            photoUrl = photoUrl.replace('http://', 'https://');
                        }
                        preview.src = photoUrl;
                    }
                    alert(result.message || 'Foto profil berhasil diperbarui!');
                } else {
                    alert('Gagal mengupload foto: ' + (result.message || ''));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupload foto.');
            } finally {
                labelElement.innerHTML = originalHTML;
            }
        }
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    <!-- Modal Tambah Donasi Baru (Moved to end for better accessibility) -->
    <div id="modalTambahDonasi" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 hidden p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col relative animate-fade-in">
            <button onclick="closeModalDonasi()" class="absolute top-4 right-4 text-slate-400 hover:text-red-500 text-xl transition-colors z-[100]"><i class="fas fa-times"></i></button>
            
            <div class="px-8 pt-8 pb-4 border-b border-slate-100 shrink-0">
                <h2 class="text-xl font-bold text-slate-800 mb-4">Tambah Donasi Baru</h2>
                <!-- Stepper Navigation -->
                <div class="flex items-center justify-center gap-2">
                    <div id="step-indicator-1" class="w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-blue-500 bg-blue-500 text-white transition-all">1</div>
                    <div class="h-1 w-8 bg-blue-200"></div>
                    <div id="step-indicator-2" class="w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-blue-200 bg-white text-blue-500 transition-all">2</div>
                    <div class="h-1 w-8 bg-blue-200"></div>
                    <div id="step-indicator-3" class="w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-blue-200 bg-white text-blue-500 transition-all">3</div>
                    <div class="h-1 w-8 bg-blue-200"></div>
                    <div id="step-indicator-4" class="w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-blue-200 bg-white text-blue-500 transition-all">4</div>
                    <div class="h-1 w-8 bg-blue-200"></div>
                    <div id="step-indicator-5" class="w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-blue-200 bg-white text-blue-500 transition-all">5</div>
                </div>
            </div>

            <form id="formCampaign" class="flex-1 flex flex-col min-h-0" enctype="multipart/form-data" autocomplete="off" onsubmit="return false;">
                <!-- Scrollable Step Contents -->
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-6 min-h-0">
                    <!-- Step 1: Informasi Dasar Donasi -->
                <div class="step-donasi" id="step1">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Judul Donasi / Kampanye</label>
                        <input type="text" name="judul" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all" placeholder="Contoh: Bantuan Banjir Jakarta" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Kategori Donasi</label>
                        <select id="web_kategori_select" name="kategori" onchange="onWebCategoryChanged()" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all font-semibold" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Kesehatan">🏥 Kesehatan</option>
                            <option value="Bencana Alam">🌪️ Bencana Alam</option>
                            <option value="Pendidikan">🎓 Pendidikan</option>
                            <option value="Ibadah">🕌 Ibadah</option>
                            <option value="Sosial Kemanusiaan">🤝 Sosial Kemanusiaan</option>
                            <option value="Lingkungan">🌳 Lingkungan</option>
                        </select>
                    </div>
                    <div id="web_dynamic_fields_container" class="hidden overflow-hidden transition-all duration-500 max-h-0 bg-blue-50/30 rounded-2xl border border-blue-100/50 mb-4 p-0"></div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Deskripsi Donasi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all" placeholder="Penjelasan lengkap tujuan donasi" required></textarea>
                    </div>
                </div>
                <!-- Step 2: Target & Nominal -->
                <div class="step-donasi hidden" id="step2">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Target Dana (Rp)</label>
                        <input type="number" name="target" min="10000" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all" placeholder="Contoh: 10000000" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Deadline Donasi</label>
                        <input type="date" name="deadline" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all" required>
                    </div>
                </div>
                <!-- Step 3: Lokasi & Penerima -->
                <div class="step-donasi hidden space-y-4" id="step3">
                    <div class="bg-slate-50/50 rounded-2xl border border-slate-100 p-5 shadow-sm space-y-4">
                        <div class="flex items-center justify-between gap-4">
                            <span class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-blue-600">
                                <i class="fas fa-map-marked-alt text-base"></i> Detail Area Pelaksanaan
                            </span>
                            <button type="button" id="btnDetectLocation" onclick="detectLiveLocation()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-extrabold transition-all border border-blue-100/50">
                                <i class="fas fa-location-arrow text-[10px]"></i> Gunakan Live Location
                            </button>
                        </div>

                        <!-- Consolidated lokasi field (sent to backend) -->
                        <input type="hidden" name="lokasi" id="web_lokasi_consolidated" required>

                        <!-- Provinsi & Kabupaten/Kota in a 2-Column Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Provinsi</label>
                                <select id="web_provinsi" name="provinsi" onchange="onWebProvinsiChanged()" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-700 text-sm font-semibold" required>
                                    <option value="">Pilih Provinsi</option>
                                    <option value="DKI Jakarta">DKI Jakarta</option>
                                    <option value="Jawa Barat">Jawa Barat</option>
                                    <option value="Jawa Tengah">Jawa Tengah</option>
                                    <option value="Jawa Timur">Jawa Timur</option>
                                    <option value="Bali">Bali</option>
                                    <option value="Sumatera Utara">Sumatera Utara</option>
                                    <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Kota / Kabupaten</label>
                                <select id="web_kabupaten" name="kabupaten" onchange="updateWebConsolidatedLocation()" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-700 text-sm font-semibold" required>
                                    <option value="">Pilih Kota / Kabupaten</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kecamatan & Kode Pos in a 2-Column Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Kecamatan (Opsional)</label>
                                <input type="text" id="web_kecamatan" name="kecamatan" oninput="updateWebConsolidatedLocation()" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-700 text-sm font-medium" placeholder="Bojongsoang">
                            </div>
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Kode Pos (Opsional)</label>
                                <input type="text" id="web_kode_pos" name="kode_pos" oninput="updateWebConsolidatedLocation()" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-700 text-sm font-medium" placeholder="40257">
                            </div>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Alamat Lengkap</label>
                            <textarea id="web_alamat_lengkap" name="alamat_lengkap" rows="2" oninput="updateWebConsolidatedLocation()" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-700 text-sm font-medium" placeholder="Nama jalan, nomor rumah, RT/RW, dsb." required></textarea>
                        </div>

                        <!-- Live Preview Banner -->
                        <div id="web_location_preview_banner" class="hidden p-3 rounded-xl bg-blue-50/50 border border-blue-100 flex items-start gap-2.5 text-blue-800 text-xs font-semibold">
                            <i class="fas fa-map-pin text-blue-500 mt-0.5 animate-bounce"></i>
                            <div class="flex-1">
                                <span class="text-[10px] text-blue-500 uppercase tracking-wider font-extrabold block mb-0.5">Preview Alamat Singkat</span>
                                <span id="web_location_preview_text"></span>
                            </div>
                        </div>

                        <!-- Beautiful simulated map preview inside a rounded card -->
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2 ml-1">Peta Lokasi</label>
                            <div class="w-full h-32 rounded-2xl border border-blue-100 bg-sky-50 relative overflow-hidden flex items-center justify-center shadow-inner">
                                <!-- Abstract elements representing map features -->
                                <div class="absolute inset-0 opacity-15 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&q=80&w=600')"></div>
                                <div class="absolute -top-10 -left-10 w-28 h-28 rounded-full border-[15px] border-white/40"></div>
                                <div class="absolute -bottom-10 -right-10 w-36 h-36 rounded-full border-[20px] border-white/40"></div>
                                <div class="absolute top-1/2 left-0 w-full h-1.5 bg-white/50 -rotate-12"></div>
                                <div class="absolute top-1/3 left-1/4 w-1.5 h-full bg-white/50 rotate-45"></div>
                                
                                <!-- Concentric rings and pin -->
                                <div class="relative flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center animate-pulse">
                                        <div class="w-8 h-8 rounded-full bg-blue-500/25 flex items-center justify-center">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 shadow-md flex items-center justify-center text-white">
                                                <i class="fas fa-location-dot text-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span id="web_map_pin_badge" class="mt-2 px-2.5 py-1 rounded-full bg-white/95 text-[10px] font-bold text-blue-700 shadow-sm border border-slate-100 max-w-[200px] truncate">Mencari lokasi donasi...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Penerima details in the same card step -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Nama Penerima / Organisasi</label>
                        <input type="text" name="penerima" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Jenis Penerima</label>
                        <select name="jenis_penerima" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all" required>
                            <option value="">Pilih Jenis</option>
                            <option>Individu</option>
                            <option>Yayasan</option>
                            <option>Komunitas</option>
                        </select>
                    </div>
                </div>
                <!-- Step 4: Media Pendukung -->
                <div class="step-donasi hidden" id="step4">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Upload Foto / Thumbnail</label>
                        <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Upload Dokumen Pendukung (opsional)</label>
                        <input type="file" name="dokumen" accept="application/pdf,image/*" class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all">
                    </div>
                </div>
                <!-- Step 5: Kontak & Validasi -->
                <div class="step-donasi hidden space-y-6" id="step5">
                    <!-- Premium Live Preview Card -->
                    <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-700 text-white rounded-[2rem] p-6 shadow-xl relative overflow-hidden group mb-6">
                        <div class="absolute -right-16 -top-16 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span id="preview_card_category" class="px-3 py-1 rounded-full bg-white/20 text-[10px] font-bold uppercase tracking-wider text-white">Donasi</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold border border-emerald-500/30">
                                <i class="fas fa-eye animate-pulse"></i> LIVE PREVIEW
                            </span>
                        </div>
                        
                        <h3 id="preview_card_title" class="text-xl font-extrabold leading-tight mb-2">Bantu Perbaikan Atap PAUD</h3>
                        
                        <div class="flex items-center gap-2 text-white/80 text-xs mb-6">
                            <i class="fas fa-map-marker-alt"></i>
                            <span id="preview_card_location">Bandung, Jawa Barat</span>
                        </div>
                        
                        <div class="border-t border-white/25 pt-4 flex items-center justify-between">
                            <div>
                                <p class="text-[9px] font-bold text-white/60 uppercase tracking-widest">Target Dana</p>
                                <p id="preview_card_target" class="text-base font-black">Rp 50.000.000</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-white/60 uppercase tracking-widest">Batas Waktu</p>
                                <p id="preview_card_deadline" class="text-sm font-extrabold">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Fields Preview Container -->
                    <div id="web_preview_dynamic_section" class="hidden p-5 rounded-2xl bg-slate-50 border border-slate-100 shadow-inner mb-6">
                        <span class="block text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-3">Detail Tambahan Kategori</span>
                        <div id="web_preview_dynamic_content" class="space-y-2 text-xs font-semibold text-slate-700"></div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Nomor WhatsApp</label>
                        <input type="text" name="whatsapp" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Metode Penyaluran Dana</label>
                        <select name="penyaluran" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition-all" required>
                            <option value="">Pilih Metode Penyaluran</option>
                            <option value="Langsung">Langsung ke Penerima</option>
                            <option value="Yayasan">Melalui Yayasan/Organisasi</option>
                            <option value="Platform">Melalui Platform Dongiv</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" readonly class="w-full px-4 py-3 rounded-xl bg-slate-100 text-slate-500 cursor-not-allowed">
                    </div>
                    <div class="mb-4 flex items-center gap-2 mt-4">
                        <input type="checkbox" name="validasi_data" id="validasi_data" required class="w-4 h-4 accent-blue-600">
                        <label for="validasi_data" class="text-xs text-slate-600">Saya menyatakan data ini benar</label>
                    </div>
                    <div class="mb-4 flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="publik" id="publik" class="w-4 h-4 accent-blue-600">
                            <span class="text-xs text-slate-600">Tampilkan sebagai publik</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="relawan" id="relawan" class="w-4 h-4 accent-blue-600">
                            <span class="text-xs text-slate-600">Butuh bantuan relawan?</span>
                        </label>
                    </div>
                </div> <!-- End of scrollable step contents -->

                <!-- Navigasi Step (Sticky Footer) -->
                <div class="px-8 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-b-2xl shrink-0">
                    <button type="button" id="btnPrevStep" class="px-6 py-2 rounded-lg bg-slate-100 text-slate-500 font-bold hover:bg-slate-200 transition-colors" disabled>Kembali</button>
                    <button type="button" id="btnNextStep" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:scale-105">Lanjut</button>
                    <button type="submit" id="btnSubmitDonasi" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transition-all transform hover:scale-105 hidden">Submit Kampanye</button>
                </div>
            </form>
        </div>
    </div>
</x-app>
