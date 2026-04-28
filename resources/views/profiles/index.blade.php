<x-app title="Profil & Riwayat">
    
    <div class="min-h-screen bg-slate-50/80 py-12 font-sans selection:bg-blue-100 selection:text-blue-600">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">

            {{-- 1. HEADER & STATS CARD --}}
            <div class="bg-white rounded-3xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-white p-8 mb-10 relative overflow-hidden group">
                
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

            <div class="flex justify-end mb-4">
                <button id="btnTambahDonasi" class="flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all">
                    <i class="fas fa-plus"></i> Tambah Donasi
                </button>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- 2. SIDEBAR MENU --}}
                <div class="lg:col-span-3">
                    <div class="sticky top-28 space-y-1">
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
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-md font-bold">{{ $donations->total() }}</span>
                        </button>

                        <button onclick="switchTab('transaction-history')" id="btn-transaction-history" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3"><i class="fas fa-table w-5 text-center group-hover:text-blue-500 transition-colors"></i> Transaksi</span>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-md font-bold">{{ $donationTransactions->total() + $legacyDonations->total() }}</span>
                        </button>

                        <button onclick="switchTab('history-volunteer')" id="btn-history-volunteer" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3"><i class="fas fa-hands-helping w-5 text-center group-hover:text-blue-500 transition-colors"></i> Relawan</span>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-md font-bold">{{ $volunteerApps->total() }}</span>
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
                    <!-- Modal Tambah Donasi Baru -->
                    <div id="modalTambahDonasi" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden">
                        <div class="bg-white rounded-2xl shadow-lg max-w-2xl w-full p-8 relative animate-fade-in">
                            <button onclick="closeModalDonasi()" class="absolute top-4 right-4 text-slate-400 hover:text-red-500 text-xl"><i class="fas fa-times"></i></button>
                            <h2 class="text-xl font-bold text-slate-800 mb-4">Tambah Donasi Baru</h2>
                            <form id="formTambahDonasi" enctype="multipart/form-data" autocomplete="off" onsubmit="return false;">
                                <!-- Stepper Navigation -->
                                <div class="flex items-center justify-center gap-2 mb-6">
                                    <div id="step-indicator-1" class="w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-blue-500 bg-blue-500 text-white">1</div>
                                    <div class="h-1 w-8 bg-blue-200"></div>
                                    <div id="step-indicator-2" class="w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-blue-200 bg-white text-blue-500">2</div>
                                    <div class="h-1 w-8 bg-blue-200"></div>
                                    <div id="step-indicator-3" class="w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-blue-200 bg-white text-blue-500">3</div>
                                    <div class="h-1 w-8 bg-blue-200"></div>
                                    <div id="step-indicator-4" class="w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-blue-200 bg-white text-blue-500">4</div>
                                    <div class="h-1 w-8 bg-blue-200"></div>
                                    <div id="step-indicator-5" class="w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-blue-200 bg-white text-blue-500">5</div>
                                </div>
                                <!-- Step 1: Informasi Dasar Donasi -->
                                <div class="step-donasi" id="step-donasi-1">
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Donasi / Kampanye</label>
                                        <input type="text" name="judul" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" placeholder="Contoh: Bantuan Banjir Jakarta" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kategori Donasi</label>
                                        <select name="kategori" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" required>
                                            <option value="">Pilih Kategori</option>
                                            <option>Bencana</option>
                                            <option>Pendidikan</option>
                                            <option>Kesehatan</option>
                                            <option>Sosial</option>
                                            <option>Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Deskripsi Donasi</label>
                                        <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" placeholder="Penjelasan lengkap tujuan donasi" required></textarea>
                                    </div>
                                </div>
                                <!-- Step 2: Target & Nominal -->
                                <div class="step-donasi hidden" id="step-donasi-2">
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Target Dana (Rp)</label>
                                        <input type="number" name="target" min="10000" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" placeholder="Contoh: 10000000" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Minimal Donasi (opsional)</label>
                                        <input type="number" name="minimal" min="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" placeholder="Contoh: 10000">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Deadline Donasi</label>
                                        <input type="date" name="deadline" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" required>
                                    </div>
                                </div>
                                <!-- Step 3: Lokasi & Penerima -->
                                <div class="step-donasi hidden" id="step-donasi-3">
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Lokasi Donasi (Kota/Kabupaten)</label>
                                        <input type="text" name="lokasi" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" placeholder="Contoh: Jakarta Selatan" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Penerima / Organisasi</label>
                                        <input type="text" name="penerima" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Penerima</label>
                                        <select name="jenis_penerima" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" required>
                                            <option value="">Pilih Jenis</option>
                                            <option>Individu</option>
                                            <option>Yayasan</option>
                                            <option>Komunitas</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Step 4: Media Pendukung -->
                                <div class="step-donasi hidden" id="step-donasi-4">
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Foto / Thumbnail</label>
                                        <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Dokumen Pendukung (opsional)</label>
                                        <input type="file" name="dokumen" accept="application/pdf,image/*" class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500">
                                    </div>
                                </div>
                                <!-- Step 5: Kontak & Validasi -->
                                <div class="step-donasi hidden" id="step-donasi-5">
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nomor WhatsApp</label>
                                        <input type="text" name="whatsapp" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" placeholder="08xxxxxxxxxx" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                                        <input type="email" name="email" value="{{ $user->email }}" readonly class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-100 text-slate-500 cursor-not-allowed">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Metode Penyaluran</label>
                                        <select name="penyaluran" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500" required>
                                            <option value="">Pilih Metode</option>
                                            <option>Langsung</option>
                                            <option>Melalui Validator</option>
                                            <option>Platform</option>
                                        </select>
                                    </div>
                                    <div class="mb-4 flex items-center gap-2">
                                        <input type="checkbox" name="validasi_data" id="validasi_data" required>
                                        <label for="validasi_data" class="text-xs text-slate-600">Saya menyatakan data ini benar</label>
                                    </div>
                                    <div class="mb-4 flex items-center gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="publik" id="publik" class="accent-blue-600">
                                            <span class="text-xs text-slate-600">Tampilkan sebagai publik</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="relawan" id="relawan" class="accent-blue-600">
                                            <span class="text-xs text-slate-600">Butuh bantuan relawan?</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Navigasi Step -->
                                <div class="flex items-center justify-between mt-8">
                                    <button type="button" id="btnPrevStep" class="px-6 py-2 rounded-lg bg-slate-100 text-slate-500 font-bold hover:bg-slate-200" disabled>Kembali</button>
                                    <button type="button" id="btnNextStep" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all">Lanjut</button>
                                    <button type="submit" id="btnSubmitDonasi" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transition-all hidden">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    {{-- Alert Success --}}
                    <script>
                        // Modal logic
                        const btnTambahDonasi = document.getElementById('btnTambahDonasi');
                        const modalTambahDonasi = document.getElementById('modalTambahDonasi');
                        function openModalDonasi() {
                            modalTambahDonasi.classList.remove('hidden');
                        }
                        function closeModalDonasi() {
                            modalTambahDonasi.classList.add('hidden');
                        }
                        btnTambahDonasi.addEventListener('click', openModalDonasi);

                        // Multi-step logic
                        let currentStep = 1;
                        const totalStep = 5;
                        const stepEls = [];
                        for (let i = 1; i <= totalStep; i++) {
                            stepEls.push(document.getElementById('step-donasi-' + i));
                        }
                        const stepIndicators = [];
                        for (let i = 1; i <= totalStep; i++) {
                            stepIndicators.push(document.getElementById('step-indicator-' + i));
                        }
                        const btnPrevStep = document.getElementById('btnPrevStep');
                        const btnNextStep = document.getElementById('btnNextStep');
                        const btnSubmitDonasi = document.getElementById('btnSubmitDonasi');

                        function showStep(step) {
                            stepEls.forEach((el, idx) => {
                                if (idx === step - 1) el.classList.remove('hidden');
                                else el.classList.add('hidden');
                            });
                            stepIndicators.forEach((el, idx) => {
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
                            });
                            btnPrevStep.disabled = step === 1;
                            btnNextStep.classList.toggle('hidden', step === totalStep);
                            btnSubmitDonasi.classList.toggle('hidden', step !== totalStep);
                        }
                        btnPrevStep.addEventListener('click', () => {
                            if (currentStep > 1) {
                                currentStep--;
                                showStep(currentStep);
                            }
                        });
                        btnNextStep.addEventListener('click', () => {
                            // Validasi sederhana per step
                            const form = document.getElementById('formTambahDonasi');
                            const stepFields = stepEls[currentStep - 1].querySelectorAll('input,select,textarea');
                            let valid = true;
                            stepFields.forEach(f => {
                                if (f.hasAttribute('required') && !f.value) {
                                    f.classList.add('border-red-500');
                                    valid = false;
                                } else {
                                    f.classList.remove('border-red-500');
                                }
                            });
                            if (!valid) return;
                            if (currentStep < totalStep) {
                                currentStep++;
                                showStep(currentStep);
                            }
                        });

                        // Submit logic
                        const formTambahDonasi = document.getElementById('formTambahDonasi');
                        formTambahDonasi.addEventListener('submit', async function (e) {
                            e.preventDefault();
                            const formData = new FormData(formTambahDonasi);
                            try {
                                const response = await fetch('{{ route('donation.store') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                });
                                if (response.ok) {
                                    alert('Donasi berhasil ditambahkan!');
                                    closeModalDonasi();
                                    location.reload();
                                } else {
                                    alert('Gagal menambahkan donasi. Silakan coba lagi.');
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan. Silakan coba lagi.');
                            }
                        });
                        // Reset step saat modal dibuka
                        function resetDonasiForm() {
                            currentStep = 1;
                            showStep(currentStep);
                            // Reset field error
                            stepEls.forEach(step => {
                                step.querySelectorAll('input,select,textarea').forEach(f => f.classList.remove('border-red-500'));
                            });
                        }
                        btnTambahDonasi.addEventListener('click', resetDonasiForm);
                        // Inisialisasi awal
                        showStep(currentStep);
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
                                <!-- Note: 'alamat' field doesn't exist in the users table, so we remove this field -->
                                <!-- If you need address field, you'll need to create a migration to add it to the users table -->
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

                    {{-- TAB C: RIWAYAT DONASI --}}
                    <div id="tab-history-donation" class="tab-content hidden animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                            <h2 class="text-xl font-bold text-slate-800 mb-6">Riwayat Donasi (Card View)</h2>
                            <div class="space-y-4">
                                @forelse($allDonations as $item)
                                    <div class="group flex flex-col md:flex-row items-center justify-between p-5 bg-white border border-slate-100 rounded-2xl hover:border-blue-200 hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300">
                                        <div class="flex items-center gap-5 w-full md:w-auto">
                                            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-hand-holding-usd"></i></div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-sm font-bold text-slate-800">Donasi #{{ $item['order_id'] }}</span>
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                                    <span class="text-xs text-slate-500">{{ $item['created_at']->format('d M Y') }}</span>
                                                </div>
                                                <p class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg inline-block">{{ $item['campaign']->title ?? $item['campaign']->judul ?? 'Kampanye Umum' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right w-full md:w-auto flex flex-row md:flex-col justify-between items-center md:items-end mt-4 md:mt-0 space-y-2 md:space-y-0">
                                            <p class="text-lg font-black text-slate-800">Rp {{ number_format($item['amount'], 0, ',', '.') }}</p>
                                            <div class="flex flex-col items-end space-y-1">
                                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ ($item['status'] == 'paid' || $item['status'] == 'VERIFIED') ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                                    {{ $item['status'] }}
                                                </span>
                                                @if(isset($item['model']) && $item['model']->proof_of_transfer_path)
                                                    <div class="flex items-center space-x-2">
                                                        <a href="{{ $item['model']->proof_of_transfer_path }}" target="_blank" class="text-blue-600 hover:text-blue-900 text-xs font-bold"
                                                           onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=No+Image';">
                                                           <i class="fas fa-image mr-1"></i>Lihat Bukti
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200"><p class="text-slate-500 text-sm font-medium">Belum ada riwayat donasi.</p></div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- TAB D: TRANSAKSI (TABLE) --}}
                    <div id="tab-transaction-history" class="tab-content hidden animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                            <h2 class="text-xl font-bold text-slate-800 mb-6">Riwayat Transaksi</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bukti Transfer</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($donationTransactions as $t)
                                            <tr>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $t->order_id }}</td>
                                                <td class="px-6 py-4 text-sm font-bold">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4"><span class="px-2 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $t->status }}</span></td>
                                                <td class="px-6 py-4">
                                                    @if($t->proof_of_transfer_path)
                                                        <a href="{{ $t->proof_of_transfer_path }}" target="_blank" class="text-blue-600 hover:text-blue-900 text-xs font-bold"
                                                           onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=No+Image';">
                                                           <i class="fas fa-image mr-1"></i>Lihat Bukti
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($t->status === 'AWAITING_TRANSFER' || $t->status === 'PENDING_VERIFICATION')
                                                        @if(!$t->proof_of_transfer_path)
                                                            <div class="flex flex-col space-y-2">
                                                                <form id="uploadForm_{{ $t->order_id }}" action="{{ route('profiles.upload.proof', $t->order_id) }}" method="POST" enctype="multipart/form-data" class="inline">
                                                                    @csrf
                                                                    <label class="text-blue-600 hover:text-blue-900 cursor-pointer text-xs font-bold mr-3 flex items-center">
                                                                        <i class="fas fa-upload mr-1"></i> Upload
                                                                        <input type="file" name="proof" accept="image/*" class="hidden" onchange="handleImagePreview(this, 'preview_{{ $t->order_id }}'); document.getElementById('uploadForm_{{ $t->order_id }}').submit()" required>
                                                                    </label>
                                                                </form>
                                                                <div class="mt-1">
                                                                    <img id="preview_{{ $t->order_id }}" class="hidden max-w-xs max-h-24 rounded-lg border border-gray-200" src="" alt="Preview Bukti Transfer">
                                                                </div>
                                                                <a href="{{ route('profiles.invoice', ['id' => $t->id]) }}" class="text-blue-600 hover:text-blue-900 text-xs font-bold"><i class="fas fa-file-invoice"></i> Invoice</a>
                                                            </div>
                                                        @else
                                                            <div class="flex flex-col space-y-2">
                                                                <a href="{{ route('profiles.invoice', ['id' => $t->id]) }}" class="text-blue-600 hover:text-blue-900 text-xs font-bold mr-3"><i class="fas fa-file-invoice"></i> Invoice</a>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('profiles.invoice', ['id' => $t->id]) }}" class="text-blue-600 hover:text-blue-900 text-xs font-bold"><i class="fas fa-file-invoice"></i> Invoice</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                                        <div class="flex justify-between items-start gap-4 mb-4">
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
                                    </div>
                                @empty
                                    <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200"><p class="text-slate-500 text-sm font-medium">Belum ada aktivitas relawan.</p></div>
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
            // Cek jika ada hash di URL (misal: #my-campaigns) untuk direct link
            const hash = window.location.hash.replace('#', '');
            if(hash && document.getElementById('tab-' + hash)) {
                switchTab(hash);
            } else {
                switchTab('edit-profile'); // Default tab
            }
        });
    </script>

    <script>
        // Function to handle image preview for file inputs
        function handleImagePreview(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    if (preview) {
                        // Update the src attribute for img tags
                        if (preview.tagName === 'IMG') {
                            preview.src = e.target.result;
                        }
                        // Update the background for div tags
                        else {
                            preview.style.backgroundImage = `url('${e.target.result}')`;
                            preview.style.backgroundSize = 'cover';
                            preview.style.backgroundPosition = 'center';
                            preview.innerHTML = ''; // Clear any existing content
                        }
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Function to upload photo via AJAX
        async function uploadPhoto(input) {
            if (!input.files || !input.files[0]) {
                return;
            }

            const file = input.files[0];
            console.log('Selected file:', file.name, file.size, file.type);

            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'PUT');

            // Show loading indicator
            const labelElement = document.querySelector('label[for="photoInput"]');
            const originalHTML = labelElement.innerHTML;
            labelElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengupload...';

            try {
                const response = await fetch('{{ route('profiles.update', [], false) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'include' // Better for CSRF in production
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server error response:', errorText);
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('Response data:', result);

                if (result.success) {
                    // Update the image preview with the new photo from server response
                    const preview = document.getElementById('photoPreview');
                    if (preview && preview.tagName === 'IMG') {
                        // Ensure the image URL uses HTTPS if the page is loaded over HTTPS
                        let photoUrl = result.data.photo;
                        if (window.location.protocol === 'https:' && photoUrl.startsWith('http://')) {
                            photoUrl = photoUrl.replace('http://', 'https://');
                        }
                        preview.src = photoUrl;
                    }

                    // Show success message
                    alert(result.message || 'Foto profil berhasil diperbarui!');
                } else {
                    // Show error message
                    const errorMessage = result.message || 'Gagal mengupload foto';
                    alert('Gagal mengupload foto: ' + errorMessage);
                }
            } catch (error) {
                console.error('Error:', error);
                // More specific error handling for different error types
                if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    alert('Koneksi gagal: Pastikan jaringan internet stabil dan coba lagi. Jika masalah terus berlanjut, hubungi administrator.');
                } else {
                    alert('Terjadi kesalahan saat mengupload foto: ' + error.message);
                }
            } finally {
                // Restore label
                labelElement.innerHTML = originalHTML;
            }
        }
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app>