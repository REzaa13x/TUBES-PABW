@extends('validator.layouts.validator')

@section('title', 'Detail & Hasil Laporan Kampanye')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Back Button & Quick Jump -->
    <div class="flex items-center justify-between">
        <a href="{{ route('validator.dashboard', $token) }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-500 hover:text-blue-600 bg-white border border-slate-200/60 px-4 py-2.5 rounded-xl transition-all shadow-xs">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kampanye
        </a>

        <div class="flex items-center gap-2">
            <a href="#hasil-laporan-section" class="inline-flex items-center gap-1.5 text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-3.5 py-2 rounded-xl hover:bg-emerald-100 transition-all">
                <i class="fas fa-clipboard-check"></i> Langsung ke Hasil Laporan ({{ $distributions->count() }})
            </a>
            <a href="{{ route('validator.upload', $token) }}" class="inline-flex items-center gap-1.5 text-[10px] font-black text-blue-600 bg-blue-50 border border-blue-200/60 px-3.5 py-2 rounded-xl hover:bg-blue-100 transition-all">
                <i class="fas fa-plus"></i> Unggah Laporan
            </a>
        </div>
    </div>

    <!-- Header Image -->
    <div class="relative h-96 rounded-[3rem] overflow-hidden shadow-2xl">
        <img src="{{ $campaign->image }}" class="w-full h-full object-cover" alt="{{ $campaign->title }}">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        <div class="absolute bottom-12 left-12 right-12">
            <span class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full">
                {{ $campaign->kategori ?: 'Sosial' }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-white mt-4 tracking-tighter">{{ $campaign->title }}</h1>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Target</p>
            <p class="text-lg sm:text-xl font-black text-slate-800">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Terkumpul</p>
            <p class="text-lg sm:text-xl font-black text-blue-600">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Dana Disalurkan</p>
            <p class="text-lg sm:text-xl font-black text-emerald-600">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status</p>
            <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-full inline-block">
                {{ $campaign->status }}
            </span>
        </div>
    </div>

    <!-- =============================================================== -->
    <!-- HASIL LAPORAN KAMPANYE & DOKUMENTASI PENYALURAN (VALIDATOR) -->
    <!-- =============================================================== -->
    <div id="hasil-laporan-section" class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 mb-2">
                    <i class="fas fa-shield-alt text-xs"></i> Laporan Resmi Validator
                </span>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span>Hasil Laporan & Dokumentasi Penyaluran</span>
                </h3>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">Daftar realisasi dan hasil kegiatan kampanye yang telah disalurkan kepada penerima manfaat.</p>
            </div>
            
            <a href="{{ route('validator.upload', $token) }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-bold shadow-md shadow-blue-500/20 transition-all transform hover:-translate-y-0.5 self-start sm:self-auto">
                <i class="fas fa-plus-circle"></i>
                <span>Unggah Laporan Tambahan</span>
            </a>
        </div>

        {{-- Mini Highlight Dana --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50/80 p-5 rounded-2xl border border-slate-100">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Dana Disalurkan</p>
                <h4 class="text-xl font-black text-emerald-600 mt-0.5">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</h4>
            </div>
            <div class="border-t sm:border-t-0 sm:border-x border-slate-200 pt-3 sm:pt-0 sm:px-5">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jumlah Laporan Hasil</p>
                <h4 class="text-xl font-black text-slate-800 mt-0.5">{{ $distributions->count() }} Laporan</h4>
            </div>
            <div class="border-t sm:border-t-0 border-slate-200 pt-3 sm:pt-0 sm:pl-5">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sisa Saldo Kampanye</p>
                <h4 class="text-xl font-black text-blue-600 mt-0.5">Rp {{ number_format($sisaSaldo, 0, ',', '.') }}</h4>
            </div>
        </div>

        {{-- Grid Kartu Hasil Laporan Penyaluran --}}
        @if($distributions->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            @foreach($distributions as $dist)
            <div class="bg-white rounded-2xl border border-slate-200/80 hover:border-blue-300 hover:shadow-xl hover:shadow-blue-500/5 transition-all overflow-hidden flex flex-col group">
                
                {{-- Foto Dokumentasi Bukti --}}
                <div class="relative h-56 w-full bg-slate-100 overflow-hidden">
                    <img src="{{ $dist->proof_image }}" alt="Dokumentasi Hasil Penyaluran" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-transparent to-transparent"></div>
                    
                    {{-- Status Badge --}}
                    <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm
                        {{ $dist->status === 'verified' ? 'bg-emerald-600 text-white' : ($dist->status === 'rejected' ? 'bg-rose-600 text-white' : 'bg-amber-500 text-white') }}">
                        {{ $dist->status === 'verified' ? '✓ Terverifikasi' : ucfirst($dist->status) }}
                    </span>

                    {{-- Nominal di atas foto --}}
                    <div class="absolute bottom-3 left-4 right-4 flex items-center justify-between text-white">
                        <span class="text-base font-black tracking-tight drop-shadow">
                            Rp {{ number_format($dist->amount, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] font-bold opacity-90 drop-shadow">
                            <i class="far fa-calendar-alt mr-1"></i> {{ $dist->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>

                {{-- Detail Laporan --}}
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-2.5">
                        {{-- Penerima Manfaat --}}
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs flex-shrink-0">
                                <i class="fas fa-user-tag text-[10px]"></i>
                            </span>
                            <span class="text-xs font-bold text-slate-900">
                                Penerima: <span class="text-blue-600 font-extrabold">{{ $dist->recipient ?: 'Penerima Manfaat Lapangan' }}</span>
                            </span>
                        </div>

                        {{-- Uraian Hasil Kegiatan --}}
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100/80">
                            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                {{ $dist->description }}
                            </p>
                        </div>

                        {{-- Catatan Verifikasi / Admin --}}
                        @if($dist->admin_note)
                        <div class="flex items-start gap-2 text-[11px] text-slate-500 italic pt-1">
                            <i class="fas fa-comment-alt text-blue-500 mt-0.5 text-xs"></i>
                            <span>Catatan: "{{ $dist->admin_note }}"</span>
                        </div>
                        @endif
                    </div>

                    {{-- Tombol Buka Foto Penuh --}}
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400">
                            <i class="far fa-clock mr-1"></i> Dilaporkan {{ $dist->created_at->diffForHumans() }}
                        </span>
                        <a href="{{ $dist->proof_image }}" target="_blank" 
                           class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                            <span>Lihat Foto Penuh</span>
                            <i class="fas fa-external-link-alt text-[10px]"></i>
                        </a>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
        @else
        {{-- Empty State jika belum ada laporan --}}
        <div class="text-center py-12 bg-slate-50/80 rounded-2xl border border-dashed border-slate-200">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-xs text-slate-400">
                <i class="fas fa-clipboard-list text-2xl"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800">Belum Ada Laporan Hasil Kampanye</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Dokumentasi hasil penyaluran dana akan otomatis tercantum di sini setelah admin atau validator mengunggah bukti penyaluran.</p>
            <a href="{{ route('validator.upload', $token) }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Unggah Laporan Lapangan</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Description -->
    <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100">
        <h3 class="text-xl font-black text-slate-800 mb-6 tracking-tight flex items-center gap-3">
            <i class="fas fa-align-left text-blue-600"></i>
            Deskripsi Program Kampanye
        </h3>
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed italic text-sm">
            {!! nl2br(e($campaign->description)) !!}
        </div>
    </div>

    <!-- Need Details -->
    <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100">
        <h3 class="text-xl font-black text-slate-800 mb-6 tracking-tight flex items-center gap-3">
            <i class="fas fa-list-check text-blue-600"></i>
            Informasi Penyaluran & Lokasi
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Lokasi Distribusi</p>
                <p class="font-black text-slate-800 text-sm">{{ $campaign->lokasi ?: 'Nasional / Belum ditentukan' }}</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sasaran Penerima Manfaat</p>
                <p class="font-black text-slate-800 text-sm">{{ $campaign->jenis_penerima ?: 'Umum' }}</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Yayasan / Penanggung Jawab</p>
                <p class="font-black text-slate-800 text-sm">{{ $campaign->yayasan ?: 'DonGiv Foundation' }}</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">WhatsApp Official</p>
                <p class="font-black text-blue-600 text-sm">{{ $campaign->whatsapp ?: '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Financial Transparency Ledger -->
    <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <h3 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                <i class="fas fa-chart-line text-blue-600"></i>
                Buku Besar Aliran Dana (Ledger)
            </h3>
            
            <!-- Tab Switcher Buttons -->
            <div class="inline-flex bg-slate-100 p-1 rounded-2xl" role="tablist">
                <button onclick="switchTab('pemasukan')" id="tab-pemasukan-btn" class="px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all bg-white text-blue-600 shadow-sm">
                    Pemasukan ({{ $donations->count() }})
                </button>
                <button onclick="switchTab('pengeluaran')" id="tab-pengeluaran-btn" class="px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all text-slate-500 hover:text-slate-800">
                    Pengeluaran ({{ $withdrawals->count() + $distributions->count() }})
                </button>
            </div>
        </div>

        <!-- Pemasukan Content Tab -->
        <div id="tab-pemasukan-content" class="space-y-4">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2 mb-4">
                <i class="fas fa-hand-holding-heart text-emerald-500"></i>
                Daftar Donasi Terverifikasi Masuk
            </h4>
            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                @forelse($donations as $donation)
                    <div class="flex justify-between items-start p-4 bg-emerald-50/30 rounded-2xl border border-emerald-100/50 hover:bg-emerald-50/60 transition-colors gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 flex-shrink-0 mt-0.5">
                                <i class="fas fa-arrow-down text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">{{ $donation->donor_name ?: 'Hamba Allah' }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">
                                    {{ $donation->verified_at ? $donation->verified_at->format('d M Y H:i') : $donation->created_at->format('d M Y H:i') }} WIB • {{ $donation->payment_method ?: 'Manual Transfer' }}
                                </p>
                                @if($donation->prayer)
                                    <p class="text-xs text-slate-500 italic mt-2 bg-white/70 p-2.5 rounded-xl border border-slate-100/80 shadow-xs leading-relaxed">
                                        "{{ $donation->prayer }}"
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-sm font-black text-emerald-600">+ Rp {{ number_format($donation->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-300">
                            <i class="fas fa-folder-open text-lg"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-400">Belum ada donasi terverifikasi masuk.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pengeluaran Content Tab (Hidden by Default) -->
        <div id="tab-pengeluaran-content" class="space-y-6 hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Sub-section: Withdrawals -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-university text-rose-500"></i>
                        Penarikan Yayasan / Pengelola
                    </h4>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
                        @forelse($withdrawals as $withdrawal)
                            <div class="p-4 bg-rose-50/30 rounded-2xl border border-rose-100/50 hover:bg-rose-50/60 transition-colors flex justify-between items-start gap-4">
                                <div>
                                    <h5 class="text-xs font-bold text-slate-800">Penarikan Dana Mandiri</h5>
                                    <p class="text-[9px] font-semibold text-slate-400 mt-1">
                                        {{ $withdrawal->transferred_at ? $withdrawal->transferred_at->format('d M Y') : $withdrawal->created_at->format('d M Y') }} • {{ $withdrawal->bank_name }}
                                    </p>
                                </div>
                                <div class="text-right flex flex-col items-end gap-1.5 flex-shrink-0">
                                    <span class="text-xs font-black text-rose-600">- Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</span>
                                    @if($withdrawal->proof_file)
                                        <a href="{{ asset('storage/' . $withdrawal->proof_file) }}" target="_blank" class="inline-flex items-center gap-1 text-[8px] font-bold text-blue-600 bg-white border border-blue-100 px-2 py-1 rounded-lg hover:bg-blue-50 transition-colors shadow-sm">
                                            <i class="fas fa-file-pdf"></i> Bukti Transfer
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <p class="text-[10px] font-bold text-slate-400">Belum ada penarikan dana.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Sub-section: Distributions -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-receipt text-amber-500"></i>
                        Laporan Penyaluran Lapangan
                    </h4>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
                        @forelse($distributions as $dist)
                            <div class="p-4 bg-amber-50/30 rounded-2xl border border-amber-100/50 hover:bg-amber-50/60 transition-colors flex justify-between items-start gap-4">
                                <div>
                                    <h5 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $dist->description }}</h5>
                                    @if($dist->recipient)
                                        <p class="text-[10px] font-black text-blue-600 mt-1 flex items-center gap-1">
                                            <i class="fas fa-user-tag text-[9px]"></i> Penerima: {{ $dist->recipient }}
                                        </p>
                                    @endif
                                    <p class="text-[9px] font-semibold text-slate-400 mt-1">
                                        {{ $dist->created_at->format('d M Y') }} • {{ $dist->status }}
                                    </p>
                                </div>
                                <div class="text-right flex flex-col items-end gap-1.5 flex-shrink-0">
                                    <span class="text-xs font-black text-amber-600">- Rp {{ number_format($dist->amount, 0, ',', '.') }}</span>
                                    <a href="{{ $dist->proof_image }}" target="_blank" class="inline-flex items-center gap-1 text-[8px] font-bold text-blue-600 bg-white border border-blue-100 px-2 py-1 rounded-lg hover:bg-blue-50 transition-colors shadow-sm">
                                        <i class="fas fa-image"></i> Foto Bukti
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <p class="text-[10px] font-bold text-slate-400">Belum ada laporan penyaluran.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security/Role Notice -->
    <div class="flex justify-center pt-4">
        <div class="inline-flex items-center gap-3 px-8 py-4 bg-slate-50 border border-slate-200/80 rounded-2xl text-slate-500 shadow-xs">
            <i class="fas fa-shield-check text-blue-500 text-lg"></i>
            <span class="text-xs font-bold">Portal ini menjamin transparansi penuh antara pihak yayasan, admin, dan validator lapangan.</span>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        const pemBtn = document.getElementById('tab-pemasukan-btn');
        const pengBtn = document.getElementById('tab-pengeluaran-btn');
        const pemCont = document.getElementById('tab-pemasukan-content');
        const pengCont = document.getElementById('tab-pengeluaran-content');
        
        if (tab === 'pemasukan') {
            pemBtn.className = "px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all bg-white text-blue-600 shadow-sm";
            pengBtn.className = "px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all text-slate-500 hover:text-slate-800";
            
            pemCont.classList.remove('hidden');
            pengCont.classList.add('hidden');
        } else {
            pengBtn.className = "px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all bg-white text-blue-600 shadow-sm";
            pemBtn.className = "px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all text-slate-500 hover:text-slate-800";
            
            pengCont.classList.remove('hidden');
            pemCont.classList.add('hidden');
        }
    }
</script>
@endsection
