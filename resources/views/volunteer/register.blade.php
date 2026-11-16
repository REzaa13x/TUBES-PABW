<x-app title="Daftar Relawan - {{ $campaign->judul }}">
    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="min-h-screen bg-transparent py-10 md:py-14">
        <div class="container mx-auto px-4 sm:px-6 max-w-3xl">

            {{-- Tombol Kembali --}}
            <div class="mb-6">
                <a href="{{ route('volunteer.campaigns.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/70 backdrop-blur-md border border-white/80 text-slate-600 hover:text-indigo-600 hover:bg-white shadow-sm transition-all font-medium text-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>Batalkan & Kembali</span>
                </a>
            </div>

            {{-- Tampilkan Error Global jika ada --}}
            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50/90 backdrop-blur-md border border-red-200 text-red-700 rounded-2xl shadow-sm">
                <p class="font-bold flex items-center gap-2"><i class="fas fa-exclamation-circle text-red-500"></i> Ada beberapa data yang perlu diperbaiki:</p>
                <ul class="list-disc ml-5 text-sm mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Card Utama --}}
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/80 overflow-hidden">

                {{-- Header Formulir --}}
                <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-700 p-8 sm:p-10 text-white relative overflow-hidden">
                    {{-- Dekorasi Latar --}}
                    <div class="absolute -top-10 -right-10 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute top-0 right-0 opacity-10 transform translate-x-6 -translate-y-6 pointer-events-none">
                        <i class="fas fa-hands-helping text-[11rem]"></i>
                    </div>

                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider mb-3 border border-white/30">
                            <i class="fas fa-id-card"></i> Pendaftaran Relawan
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">Formulir Pendaftaran Relawan</h1>
                        <p class="text-indigo-100 text-sm leading-relaxed max-w-xl">
                            Terima kasih atas niat baikmu. Lengkapi informasi di bawah ini untuk diverifikasi oleh tim koordinator DonGiv.
                        </p>
                    </div>
                </div>

                {{-- Program Preview Card (Rapi & Tidak Bertumpuk) --}}
                <div class="p-6 sm:p-8 bg-slate-50/70 border-b border-slate-100">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-5 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/60 shadow-sm">
                        @if($campaign->image)
                        <div class="w-full sm:w-44 h-32 sm:h-28 rounded-xl overflow-hidden flex-shrink-0 relative shadow-sm border border-slate-100">
                            <img src="{{ $campaign->image }}"
                                 alt="{{ $campaign->judul }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.src='https://placehold.co/400x200?text=No+Image';">
                            <span class="absolute top-2 left-2 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/95 text-indigo-700 shadow-sm">
                                {{ $campaign->kategori ?? 'Sosial' }}
                            </span>
                        </div>
                        @endif

                        <div class="flex-grow w-full">
                            <span class="text-[11px] text-indigo-600 font-bold uppercase tracking-wider block mb-1">
                                <i class="fas fa-bullhorn mr-1"></i> Program yang Dilamar
                            </span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 leading-snug line-clamp-2 mb-2.5">
                                {{ $campaign->judul }}
                            </h2>

                            <div class="flex flex-wrap items-center gap-y-1.5 gap-x-4 text-xs text-slate-500 font-medium">
                                @if($campaign->lokasi)
                                    <span class="flex items-center gap-1.5 text-slate-600">
                                        <i class="fas fa-map-marker-alt text-rose-500"></i> {{ $campaign->lokasi }}
                                    </span>
                                @endif
                                @if($campaign->tanggal_mulai)
                                    <span class="flex items-center gap-1.5 text-slate-600">
                                        <i class="far fa-calendar-alt text-blue-500"></i> {{ \Carbon\Carbon::parse($campaign->tanggal_mulai)->format('d M Y') }}
                                    </span>
                                @endif
                                @if($campaign->kuota_total)
                                    <span class="flex items-center gap-1.5 text-slate-600">
                                        <i class="fas fa-users text-emerald-500"></i> Kuota: {{ $campaign->kuota_terisi ?? 0 }}/{{ $campaign->kuota_total }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <div class="p-6 sm:p-10">
                    <form id="registrationForm" action="{{ route('volunteer.register.store', $campaign->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- Data Akun Pemohon --}}
                        <div class="bg-gradient-to-r from-blue-50/70 to-indigo-50/50 p-4 sm:p-5 rounded-2xl border border-blue-100 flex items-center gap-4 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white text-lg font-bold shadow-md shadow-blue-500/20 flex-shrink-0">
                                @if(Auth::check() && Auth::user()->name)
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-xs text-blue-600 font-bold uppercase tracking-wider">Pemohon Terdaftar</p>
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                </div>
                                @if(Auth::check())
                                    <p class="text-base text-slate-900 font-extrabold truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                @else
                                    <p class="text-base text-slate-900 font-bold">Tamu / Belum Login</p>
                                    <p class="text-xs text-slate-500">Silakan login untuk melanjutkan</p>
                                @endif
                            </div>
                        </div>

                        {{-- Posisi yang Dilamar --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">
                                Posisi yang Dilamar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-briefcase text-sm"></i>
                                </div>
                                <select name="posisi_dilamar"
                                    class="appearance-none w-full pl-11 pr-10 py-3.5 rounded-xl border {{ $errors->has('posisi_dilamar') ? 'border-red-500 bg-red-50/30' : 'border-slate-200 bg-white' }} focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none cursor-pointer text-slate-700 text-sm font-medium"
                                    required>
                                    <option value="" disabled {{ old('posisi_dilamar') ? '' : 'selected' }}>Pilih posisi tugas relawan...</option>
                                    <option value="Lapangan" {{ old('posisi_dilamar') == 'Lapangan' ? 'selected' : '' }}>Tim Lapangan (Aksi Fisik & Penyaluran)</option>
                                    <option value="Medis" {{ old('posisi_dilamar') == 'Medis' ? 'selected' : '' }}>Tim Medis & P3K</option>
                                    <option value="Logistik" {{ old('posisi_dilamar') == 'Logistik' ? 'selected' : '' }}>Logistik & Dapur Umum</option>
                                    <option value="Dokumentasi" {{ old('posisi_dilamar') == 'Dokumentasi' ? 'selected' : '' }}>Dokumentasi, Foto & Media Sosial</option>
                                    <option value="Pendidikan" {{ old('posisi_dilamar') == 'Pendidikan' ? 'selected' : '' }}>Pengajar & Edukator Anak</option>
                                    <option value="Fasilitator" {{ old('posisi_dilamar') == 'Fasilitator' ? 'selected' : '' }}>Fasilitator & Koordinator Lapangan</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('posisi_dilamar')
                            <p class="text-xs text-red-500 mt-1.5 font-bold flex items-center gap-1"><i class="fas fa-times-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat Domisili --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">
                                Alamat Domisili Saat Ini <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute top-3.5 left-4 text-slate-400 pointer-events-none">
                                    <i class="fas fa-map-marked-alt text-sm"></i>
                                </div>
                                <input type="text" name="alamat"
                                    value="{{ old('alamat') }}"
                                    class="w-full pl-11 pr-4 py-3.5 rounded-xl border {{ $errors->has('alamat') ? 'border-red-500 bg-red-50/30' : 'border-slate-200 bg-white' }} focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm font-medium text-slate-800"
                                    placeholder="Contoh: Jl. Sudirman No. 12, Tebet, Jakarta Selatan" required>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5">Digunakan untuk koordinasi titik kumpul dan logistik penugasan (min. 10 karakter).</p>
                            @error('alamat')
                            <p class="text-xs text-red-500 mt-1 font-bold flex items-center gap-1"><i class="fas fa-times-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mengapa Anda ingin bergabung? --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-bold text-slate-800">
                                    Mengapa Anda ingin bergabung? <span class="text-red-500">*</span>
                                </label>
                                <span id="charCount" class="text-xs text-slate-400 font-medium">0 / 20 min</span>
                            </div>
                            <div class="relative">
                                <textarea name="alasan_bergabung" id="alasanInput" rows="4"
                                    class="w-full p-4 rounded-xl border {{ $errors->has('alasan_bergabung') ? 'border-red-500 bg-red-50/30' : 'border-slate-200 bg-white' }} focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm font-medium text-slate-800 leading-relaxed resize-y"
                                    placeholder="Ceritakan motivasi, pengalaman kerelawanan sebelumnya, atau keterampilan yang ingin kamu kontribusikan..."
                                    required>{{ old('alasan_bergabung') }}</textarea>
                            </div>
                            @error('alasan_bergabung')
                            <p class="text-xs text-red-500 mt-1 font-bold flex items-center gap-1"><i class="fas fa-times-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Upload CV / Resume --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">
                                Upload CV / Portofolio Singkat <span class="text-red-500">*</span>
                            </label>

                            <div id="uploadBox" class="relative border-2 border-dashed {{ $errors->has('cv') ? 'border-red-500 bg-red-50' : 'border-slate-300 bg-slate-50/60' }} rounded-2xl p-6 sm:p-8 text-center hover:bg-indigo-50/50 hover:border-indigo-400 transition-all duration-300 cursor-pointer group">

                                <input type="file" id="cvInput" name="cv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf" required onchange="handleFileUpload(this)">

                                {{-- Default State --}}
                                <div id="uploadDefault" class="pointer-events-none transition-opacity duration-300">
                                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-md shadow-slate-200 border border-slate-100 group-hover:scale-105 group-hover:shadow-indigo-200 transition-all">
                                        <i class="fas fa-file-pdf text-2xl text-indigo-600"></i>
                                    </div>
                                    <p class="text-sm text-slate-800 font-bold mb-1">Pilih atau Seret File CV (PDF)</p>
                                    <p class="text-xs text-slate-400">Format file .PDF • Maksimal 2MB</p>
                                </div>

                                {{-- Success State --}}
                                <div id="uploadSuccess" class="hidden pointer-events-none transition-opacity duration-300">
                                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm border border-emerald-200">
                                        <i class="fas fa-check-circle text-2xl text-emerald-600"></i>
                                    </div>
                                    <p class="text-xs text-emerald-700 font-bold uppercase tracking-wider mb-1">File Berhasil Dipilih</p>
                                    <p id="fileName" class="text-sm text-slate-900 font-bold truncate max-w-xs mx-auto">nama_file.pdf</p>
                                    <p class="text-xs text-indigo-600 mt-2 font-semibold hover:underline">Klik area ini jika ingin mengganti file</p>
                                </div>

                            </div>
                            @error('cv')
                            <p class="text-xs text-red-500 mt-1.5 font-bold flex items-center gap-1"><i class="fas fa-times-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="pt-4 border-t border-slate-100">
                            <button type="submit" id="submitBtn" class="w-full py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-700 hover:from-blue-700 hover:via-indigo-700 hover:to-indigo-800 text-white font-extrabold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2.5 text-base">
                                <span>Kirim Pendaftaran Relawan</span>
                                <i class="fas fa-paper-plane text-sm"></i>
                            </button>
                            <p class="text-center text-xs text-slate-400 mt-3 flex items-center justify-center gap-1.5">
                                <i class="fas fa-shield-alt text-slate-400"></i>
                                Data Anda aman dan hanya digunakan untuk keperluan verifikasi kerelawanan DonGiv.
                            </p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        // 1. Logic Upload File
        function handleFileUpload(input) {
            const box = document.getElementById('uploadBox');
            const defaultView = document.getElementById('uploadDefault');
            const successView = document.getElementById('uploadSuccess');
            const fileName = document.getElementById('fileName');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                fileName.textContent = file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
                box.classList.remove('border-slate-300', 'bg-slate-50/60');
                box.classList.add('border-emerald-400', 'bg-emerald-50/40');
                defaultView.classList.add('hidden');
                successView.classList.remove('hidden');
            } else {
                box.classList.add('border-slate-300', 'bg-slate-50/60');
                box.classList.remove('border-emerald-400', 'bg-emerald-50/40');
                defaultView.classList.remove('hidden');
                successView.classList.add('hidden');
            }
        }

        // 2. Character counter for alasan bergabung
        const alasanInput = document.getElementById('alasanInput');
        const charCount = document.getElementById('charCount');
        if (alasanInput && charCount) {
            function updateCharCount() {
                const len = alasanInput.value.trim().length;
                charCount.textContent = len + ' / 20 min';
                if (len >= 20) {
                    charCount.classList.remove('text-slate-400', 'text-amber-500');
                    charCount.classList.add('text-emerald-600', 'font-bold');
                } else {
                    charCount.classList.remove('text-emerald-600', 'font-bold');
                    charCount.classList.add('text-slate-400');
                }
            }
            alasanInput.addEventListener('input', updateCharCount);
            updateCharCount();
        }

        // 3. Loading State on Submit
        const form = document.getElementById('registrationForm');
        const btn = document.getElementById('submitBtn');

        if (form && btn) {
            form.addEventListener('submit', function() {
                btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Sedang Mengirim Pendaftaran...';
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                btn.disabled = true;
            });
        }
    </script>
</x-app>