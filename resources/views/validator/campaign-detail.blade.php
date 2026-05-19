@extends('validator.layouts.validator')

@section('title', 'Detail Kampanye')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('validator.dashboard', $token) }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-500 hover:text-blue-600 bg-white border border-slate-200/60 px-4 py-2.5 rounded-xl transition-all shadow-xs">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kampanye
        </a>
    </div>

    <!-- Header Image -->
    <div class="relative h-96 rounded-[3rem] overflow-hidden shadow-2xl">
        <img src="{{ $campaign->image }}" class="w-full h-full object-cover" alt="{{ $campaign->title }}">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        <div class="absolute bottom-12 left-12 right-12">
            <span class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full">
                {{ $campaign->kategori ?: 'Sosial' }}
            </span>
            <h1 class="text-4xl font-black text-white mt-4 tracking-tighter">{{ $campaign->title }}</h1>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Target</p>
            <p class="text-xl font-black text-slate-800">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Terkumpul</p>
            <p class="text-xl font-black text-blue-600">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status</p>
            <span class="px-3 py-1 bg-green-100 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-full">
                {{ $campaign->status }}
            </span>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Deadline</p>
            <p class="text-lg font-black text-slate-800">{{ $campaign->end_date->format('d M Y') }}</p>
        </div>
    </div>

    <!-- Description -->
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
        <h3 class="text-xl font-black text-slate-800 mb-6 tracking-tight flex items-center gap-3">
            <i class="fas fa-align-left text-blue-600"></i>
            Deskripsi Kampanye
        </h3>
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed italic">
            {!! nl2br(e($campaign->description)) !!}
        </div>
    </div>

    <!-- Need Details -->
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
        <h3 class="text-xl font-black text-slate-800 mb-6 tracking-tight flex items-center gap-3">
            <i class="fas fa-list-check text-blue-600"></i>
            Kebutuhan Penyaluran
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Lokasi Distribusi</p>
                <p class="font-black text-slate-800">{{ $campaign->lokasi ?: 'Nasional / Belum ditentukan' }}</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Penerima Manfaat</p>
                <p class="font-black text-slate-800">{{ $campaign->jenis_penerima ?: 'Umum' }}</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Yayasan / Penanggung Jawab</p>
                <p class="font-black text-slate-800">{{ $campaign->yayasan ?: 'DonGiv Foundation' }}</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">WhatsApp Official</p>
                <p class="font-black text-blue-600">{{ $campaign->whatsapp ?: '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Financial Transparency Block -->
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <h3 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                <i class="fas fa-chart-line text-blue-600"></i>
                Transparansi Aliran Dana Kampanye
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

        <!-- Financial Mini Stats Inside Ledger -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-slate-50 p-6 rounded-3xl border border-slate-100">
            <div class="text-center md:text-left">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pemasukan</p>
                <p class="text-xl font-black text-emerald-600">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
            </div>
            <div class="text-center md:text-left border-y md:border-y-0 md:border-x border-slate-200 py-4 md:py-0 md:px-6">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pengeluaran</p>
                <p class="text-xl font-black text-rose-600">Rp {{ number_format($withdrawals->sum('amount') + $distributions->sum('amount'), 0, ',', '.') }}</p>
            </div>
            <div class="text-center md:text-right md:pl-6">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sisa Saldo Kampanye</p>
                <p class="text-xl font-black text-blue-600">Rp {{ number_format($campaign->current_amount - ($withdrawals->sum('amount') + $distributions->sum('amount')), 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Pemasukan Content Tab -->
        <div id="tab-pemasukan-content" class="space-y-4">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2 mb-4">
                <i class="fas fa-hand-holding-heart text-emerald-500"></i>
                Daftar Donasi Terverifikasi (Pemasukan)
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
                        Penarikan Pengelola (Yayasan)
                    </h4>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
                        @forelse($withdrawals as $withdrawal)
                            <div class="p-4 bg-rose-50/30 rounded-2xl border border-rose-100/50 hover:bg-rose-50/60 transition-colors flex justify-between items-start gap-4">
                                <div>
                                    <h5 class="text-xs font-bold text-slate-800">Penarikan Dana Mandiri</h5>
                                    <p class="text-[9px] font-semibold text-slate-400 mt-1">
                                        {{ $withdrawal->transferred_at ? $withdrawal->transferred_at->format('d M Y') : $withdrawal->created_at->format('d M Y') }} • {{ $withdrawal->bank_name }}
                                    </p>
                                    @if($withdrawal->admin_note)
                                        <p class="text-[11px] text-slate-500 italic mt-2 bg-white/70 p-2 rounded-xl border border-slate-100/80 shadow-xs">
                                            "{{ $withdrawal->admin_note }}"
                                        </p>
                                    @endif
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
                            <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <p class="text-[10px] font-bold text-slate-400">Belum ada penarikan dana.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Sub-section: Distributions -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-receipt text-amber-500"></i>
                        Penyaluran Lapangan (Validator)
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
                                        {{ $dist->created_at->format('d M Y') }} • Penyaluran Lapangan
                                    </p>
                                </div>
                                <div class="text-right flex flex-col items-end gap-1.5 flex-shrink-0">
                                    <span class="text-xs font-black text-amber-600">- Rp {{ number_format($dist->amount, 0, ',', '.') }}</span>
                                    @if($dist->proof_image)
                                        <a href="{{ asset('storage/' . $dist->proof_image) }}" target="_blank" class="inline-flex items-center gap-1 text-[8px] font-bold text-blue-600 bg-white border border-blue-100 px-2 py-1 rounded-lg hover:bg-blue-50 transition-colors shadow-sm">
                                            <i class="fas fa-image"></i> Foto Bukti
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <p class="text-[10px] font-bold text-slate-400">Belum ada laporan penyaluran.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
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

    {{-- Info note: Validator hanya dapat memantau transparansi aliran dana --}}
    <div class="flex justify-center pt-8">
        <div class="inline-flex items-center gap-3 px-8 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-500">
            <i class="fas fa-shield-check text-blue-500"></i>
            <span class="text-sm font-bold">Anda berperan sebagai pengawas transparansi dana kampanye ini.</span>
        </div>
    </div>
</div>
@endsection
