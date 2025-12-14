<x-app title="Profil & Riwayat">
    
    <div class="min-h-screen bg-slate-50/80 py-12 font-sans selection:bg-blue-100 selection:text-blue-600">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">

            {{-- 1. HEADER & STATS CARD --}}
            <div class="bg-white rounded-3xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-white p-8 mb-10 relative overflow-hidden group">
                
                {{-- Hiasan Background Halus (Modern Touch) --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-60 group-hover:opacity-100 transition-opacity duration-700"></div>

                <div class="relative z-10 flex flex-col lg:flex-row items-center gap-8">
                    
                    {{-- Foto Profil --}}
                    <div class="relative">
                        <div class="w-28 h-28 rounded-full p-1 bg-white shadow-lg border border-slate-100">
                            <div class="w-full h-full rounded-full overflow-hidden relative group/img">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400 text-4xl font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                {{-- Overlay Kamera saat Hover Foto --}}
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

                    {{-- STATS WIDGETS (Floating Style) --}}
                    <div class="w-full lg:w-auto grid grid-cols-2 gap-4">
                        {{-- Poin Kebaikan --}}
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-4 rounded-2xl border border-amber-100/50 shadow-sm flex items-center gap-3 min-w-[160px]">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm text-amber-500 flex items-center justify-center text-lg">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Poin Kebaikan</p>
                                <p class="text-lg font-black text-slate-800">{{ number_format($totalPoints) }}</p>
                            </div>
                        </div>

                        {{-- Total Donasi --}}
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-2xl border border-blue-100/50 shadow-sm flex items-center gap-3 min-w-[160px]">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm text-blue-600 flex items-center justify-center text-lg">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Total Donasi</p>
                                <p class="text-lg font-black text-slate-800">Rp {{ number_format($totalDonationAmount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- 2. SIDEBAR MENU (Modern Pills) --}}
                <div class="lg:col-span-3">
                    <div class="sticky top-28 space-y-1">
                        <button onclick="switchTab('edit-profile')" id="btn-edit-profile" class="tab-btn active w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-user-edit w-5 text-center group-hover:text-blue-500 transition-colors"></i> Edit Profil
                            </span>
                            <i class="fas fa-chevron-right text-xs opacity-0 group-hover:opacity-50 transition-opacity"></i>
                        </button>

                        <button onclick="switchTab('security')" id="btn-security" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-lock w-5 text-center group-hover:text-blue-500 transition-colors"></i> Keamanan
                            </span>
                            <i class="fas fa-chevron-right text-xs opacity-0 group-hover:opacity-50 transition-opacity"></i>
                        </button>

                        <div class="h-px bg-slate-200/60 my-3 mx-4"></div>

                        <button onclick="switchTab('history-donation')" id="btn-history-donation" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-receipt w-5 text-center group-hover:text-blue-500 transition-colors"></i> Riwayat Donasi
                            </span>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-md font-bold">{{ $donations->total() }}</span>
                        </button>

                        <button onclick="switchTab('history-volunteer')" id="btn-history-volunteer" class="tab-btn w-full flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-hands-helping w-5 text-center group-hover:text-blue-500 transition-colors"></i> Riwayat Relawan
                            </span>
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
                    
                    {{-- ALERT SUCCESS (Modern Toast Style) --}}
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 text-emerald-700 text-sm shadow-sm animate-fade-in">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-xs"></i>
                            </div>
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
                                @csrf
                                @method('PUT')
                                <input type="file" name="photo" id="photoInput" class="hidden" onchange="this.form.submit()">

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

                                <div class="mb-8 group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Alamat Domisili</label>
                                    <textarea name="alamat" rows="3" class="w-full px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-medium focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none resize-none" placeholder="Masukkan alamat lengkap...">{{ old('alamat', $user->address ?? '') }}</textarea>
                                </div>

                                <div class="flex justify-end pt-6 border-t border-slate-50">
                                    <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 active:scale-95">
                                        Simpan Perubahan
                                    </button>
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
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="update_password" value="1">

                                <div class="max-w-md space-y-6">
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Kata Sandi Baru</label>
                                        <div class="relative">
                                            <input type="password" name="password" required class="w-full px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-medium focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" placeholder="Min. 8 karakter">
                                            <div class="absolute right-4 top-3.5 text-slate-400 text-xs">
                                                <i class="fas fa-key"></i>
                                            </div>
                                        </div>
                                        @error('password') <span class="text-red-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Konfirmasi Kata Sandi</label>
                                        <input type="password" name="password_confirmation" required class="w-full px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-medium focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" placeholder="Ulangi kata sandi">
                                    </div>
                                </div>

                                <div class="flex justify-end pt-8 mt-4 border-t border-slate-50">
                                    <button type="submit" class="px-8 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:scale-95">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- TAB C: RIWAYAT DONASI --}}
                    <div id="tab-history-donation" class="tab-content hidden animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                            <h2 class="text-xl font-bold text-slate-800 mb-6">Riwayat Donasi</h2>
                            
                            <div class="space-y-4">
                                @forelse($donations as $donation)
                                    <div class="group flex flex-col md:flex-row items-center justify-between p-5 bg-white border border-slate-100 rounded-2xl hover:border-blue-200 hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300">
                                        <div class="flex items-center gap-5 w-full md:w-auto">
                                            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-hand-holding-usd"></i>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-sm font-bold text-slate-800">Donasi #{{ $donation->order_id }}</span>
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                                    <span class="text-xs text-slate-500">{{ $donation->created_at->format('d M Y') }}</span>
                                                </div>
                                                <p class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg inline-block">
                                                    {{ $donation->campaign->judul ?? 'Kampanye Umum' }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="text-right w-full md:w-auto flex flex-row md:flex-col justify-between items-center md:items-end mt-4 md:mt-0">
                                            <p class="text-lg font-black text-slate-800">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                                            
                                            @if($donation->status == 'paid')
                                                <div class="flex items-center gap-1.5 mt-1 text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    <span class="text-[10px] font-bold uppercase tracking-wider">Berhasil</span>
                                                </div>
                                            @elseif($donation->status == 'pending')
                                                <div class="flex items-center gap-1.5 mt-1 text-amber-600 bg-amber-50 px-3 py-1 rounded-full border border-amber-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    <span class="text-[10px] font-bold uppercase tracking-wider">Menunggu</span>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-1.5 mt-1 text-red-600 bg-red-50 px-3 py-1 rounded-full border border-red-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                    <span class="text-[10px] font-bold uppercase tracking-wider">Gagal</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                            <i class="fas fa-box-open text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 text-sm font-medium">Belum ada riwayat donasi.</p>
                                    </div>
                                @endforelse

                                <div class="mt-6">
                                    {{ $donations->appends(['volunteer_page' => $volunteerApps->currentPage()])->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB D: RIWAYAT RELAWAN --}}
                    <div id="tab-history-volunteer" class="tab-content hidden animate-fade-in">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                            <h2 class="text-xl font-bold text-slate-800 mb-6">Jejak Relawan</h2>

                            <div class="grid grid-cols-1 gap-4">
                                @forelse($volunteerApps as $app)
                                    <div class="bg-white border border-slate-100 rounded-2xl p-5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300 group">
                                        <div class="flex justify-between items-start gap-4 mb-4">
                                            <div class="flex gap-4">
                                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 shadow-sm border border-slate-100">
                                                    <img src="{{ asset('storage/' . $app->campaign->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-800 text-base line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $app->campaign->judul }}</h4>
                                                    <p class="text-xs text-slate-500 flex items-center gap-1.5 mt-1 font-medium">
                                                        <i class="fas fa-map-marker-alt text-red-400"></i> {{ $app->campaign->lokasi }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            @php
                                                $status = match($app->status) {
                                                    'approved' => ['class' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'icon' => 'fa-check', 'text' => 'Diterima'],
                                                    'rejected' => ['class' => 'bg-red-50 text-red-600 border-red-100', 'icon' => 'fa-times', 'text' => 'Ditolak'],
                                                    default => ['class' => 'bg-blue-50 text-blue-600 border-blue-100', 'icon' => 'fa-clock', 'text' => 'Menunggu']
                                                };
                                            @endphp
                                            <span class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase border {{ $status['class'] }} flex items-center gap-1.5">
                                                <i class="fas {{ $status['icon'] }}"></i> {{ $status['text'] }}
                                            </span>
                                        </div>

                                        <div class="bg-slate-50/80 rounded-xl p-3 flex justify-between items-center text-sm border border-slate-100">
                                            <div class="flex items-center gap-2">
                                                <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">Posisi</span>
                                                <span class="text-slate-800 font-bold bg-white px-2 py-0.5 rounded border border-slate-200 shadow-sm">{{ $app->posisi_dilamar }}</span>
                                            </div>
                                            <span class="text-slate-400 text-xs font-medium">{{ $app->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                            <i class="fas fa-user-slash text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 text-sm font-medium">Belum ada aktivitas relawan.</p>
                                    </div>
                                @endforelse

                                <div class="mt-6">
                                    {{ $volunteerApps->appends(['donations_page' => $donations->currentPage()])->links() }}
                                </div>
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
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            
            // Reset buttons
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-white', 'text-blue-600', 'shadow-md', 'shadow-blue-100');
                el.classList.add('text-slate-500', 'hover:bg-white');
            });

            // Show target tab
            const target = document.getElementById('tab-' + tabId);
            if(target) target.classList.remove('hidden');

            // Set active button
            const btn = document.getElementById('btn-' + tabId);
            if(btn) {
                btn.classList.remove('text-slate-500', 'hover:bg-white');
                btn.classList.add('bg-white', 'text-blue-600', 'shadow-md', 'shadow-blue-100');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            switchTab('edit-profile');
        });
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app>