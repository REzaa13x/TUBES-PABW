<x-app title="Riwayat Kampanye Saya">
    <div class="min-h-screen bg-slate-50/80 py-12 font-sans">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
            
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Riwayat Kampanye</h1>
                    <p class="text-slate-500 mt-1">Pantau status dan perkembangan kampanye donasi yang Anda buat.</p>
                </div>
                <a href="{{ route('profiles.index') }}#manage-campaign" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i> Kembali ke Profil
                </a>
            </div>

            @if($campaigns->isEmpty())
                <div class="bg-white rounded-[2.5rem] p-20 text-center border border-slate-100 shadow-sm">
                    <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Kampanye</h2>
                    <p class="text-slate-500 mb-8 max-w-xs mx-auto">Anda belum pernah membuat kampanye donasi. Mulai langkah kebaikan Anda sekarang!</p>
                    <a href="{{ route('profiles.index') }}#manage-campaign" class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:-translate-y-1">
                        <i class="fas fa-plus-circle"></i> Buat Kampanye Pertama
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($campaigns as $c)
                        <div class="group bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-500 flex flex-col h-full">
                            
                            {{-- Image Header --}}
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $c->image ? asset('storage/' . $c->image) : 'https://placehold.co/600x400?text=No+Image' }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=No+Image';">
                                
                                {{-- Status Badge --}}
                                <div class="absolute top-4 right-4">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'icon' => 'fa-clock', 'label' => 'Menunggu'],
                                            'verified' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'icon' => 'fa-check-circle', 'label' => 'Terverifikasi'],
                                            'rejected' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-600', 'icon' => 'fa-times-circle', 'label' => 'Ditolak'],
                                        ];
                                        $s = $statusConfig[$c->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'icon' => 'fa-info-circle', 'label' => ucfirst($c->status)];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $s['bg'] }} {{ $s['text'] }} text-[10px] font-black uppercase tracking-wider shadow-sm backdrop-blur-md bg-opacity-90">
                                        <i class="fas {{ $s['icon'] }}"></i> {{ $s['label'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-6 flex-grow flex flex-col">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-widest">{{ $c->kategori ?? 'Umum' }}</span>
                                    <span class="text-slate-300 text-xs">•</span>
                                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">{{ $c->created_at->format('d M Y') }}</span>
                                </div>

                                <h3 class="text-lg font-extrabold text-slate-800 line-clamp-1 mb-2 group-hover:text-blue-600 transition-colors">{{ $c->title }}</h3>
                                <p class="text-slate-500 text-sm line-clamp-2 mb-6 leading-relaxed">{{ $c->description }}</p>

                                {{-- Progress --}}
                                <div class="mt-auto space-y-3">
                                    <div class="flex justify-between items-end">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Terkumpul</p>
                                        <p class="text-sm font-black text-slate-800">Rp {{ number_format($c->current_amount ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                        @php
                                            $percent = $c->target_amount > 0 ? min(100, ($c->current_amount / $c->target_amount) * 100) : 0;
                                        @endphp
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 transition-all duration-1000" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest">
                                        <span class="text-blue-600">{{ round($percent) }}%</span>
                                        <span class="text-slate-400">Target: Rp {{ number_format($c->target_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="p-6 pt-0 mt-4 border-t border-slate-50 flex gap-3">
                                @if(in_array($c->status, ['pending', 'rejected']))
                                    <a href="{{ route('profiles.campaign.edit', $c->id) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-50 text-slate-600 font-bold rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all text-xs">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('profiles.campaign.destroy', $c->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kampanye ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-50 text-rose-500 font-bold rounded-xl hover:bg-rose-100 transition-all text-xs">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('donations.details', $c->slug) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-600 font-bold rounded-xl hover:bg-blue-600 hover:text-white transition-all text-xs">
                                        <i class="fas fa-external-link-alt"></i> Lihat Detail Publik
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app>
