@extends('admin.layouts.master')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Kampanye Relawan</h1>
            <p class="text-sm text-gray-500 mt-1">Buat dan kelola program relawan Anda di sini.</p>
        </div>
        
        {{-- Tombol Tambah --}}
        {{-- Pastikan route 'admin.relawan.create' sudah ada di web.php --}}
        <a href="{{ route('admin.relawan.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Buat Kampanye Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- TABEL LIST KAMPANYE --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Info Kampanye</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jadwal & Lokasi</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kuota Relawan</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($campaigns as $campaign)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        {{-- 1. Info Kampanye --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-16 w-16 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                    <img class="h-16 w-16 object-cover"
                                         src="{{ $campaign->image ? $campaign->image : 'https://placehold.co/64x64?text=No+Image' }}"
                                         alt="{{ $campaign->judul }}"
                                         onerror="this.onerror=null; this.src='https://placehold.co/64x64?text=No+Image';">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900 line-clamp-1">{{ $campaign->judul }}</div>
                                    <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ Str::limit($campaign->deskripsi, 40) }}</div>
                                    <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                        {{ $campaign->kategori }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- 2. Jadwal & Lokasi --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 flex items-center gap-2">
                                <i class="far fa-calendar text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($campaign->tanggal_mulai)->format('d M Y') }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                {{ Str::limit($campaign->lokasi, 20) }}
                            </div>
                        </td>

                        {{-- 3. Kuota (Progress Bar) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $percent = $campaign->kuota_total > 0 ? ($campaign->kuota_terisi / $campaign->kuota_total) * 100 : 0;
                                $color = $percent >= 100 ? 'bg-red-500' : 'bg-blue-500';
                            @endphp
                            <div class="w-full max-w-[120px] mx-auto">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-semibold text-gray-700">{{ $campaign->kuota_terisi }}</span>
                                    <span class="text-gray-400">/ {{ $campaign->kuota_total }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        </td>

                        {{-- 4. Status --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($campaign->status == 'Aktif')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ $campaign->status }}
                                </span>
                            @endif
                        </td>

                        {{-- 5. Aksi (Disatukan dalam 1 Dropdown Menu) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="relative inline-block text-left">
                                <button type="button" 
                                        onclick="toggleRelawanActionMenu(event, 'relawan-menu-{{ $campaign->id }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 hover:text-blue-600 text-gray-700 text-xs font-bold rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                    <i class="fas fa-ellipsis-h text-gray-400 text-xs"></i>
                                    <span>Aksi</span>
                                    <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                                </button>

                                {{-- Floating Popup Menu --}}
                                <div id="relawan-menu-{{ $campaign->id }}" 
                                     class="relawan-action-menu hidden fixed z-[9999] w-52 bg-white rounded-xl shadow-2xl border border-gray-100 py-1.5 text-left text-xs divide-y divide-gray-100 transition-all">
                                    
                                    {{-- Group 1: Navigasi & Lihat --}}
                                    <div class="py-1">
                                        {{-- Lihat Detail --}}
                                        <a href="{{ route('admin.relawan.show', $campaign->id) }}" 
                                           class="flex items-center gap-2.5 px-3.5 py-2 hover:bg-blue-50 hover:text-blue-600 text-gray-700 font-semibold transition-colors">
                                            <span class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center text-xs flex-shrink-0">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                            <span>Lihat Detail</span>
                                        </a>

                                        {{-- Lihat Pendaftar --}}
                                        <a href="{{ route('admin.verifikasi-relawan.index', ['campaign_id' => $campaign->id]) }}" 
                                           class="flex items-center gap-2.5 px-3.5 py-2 hover:bg-indigo-50 hover:text-indigo-600 text-gray-700 font-semibold transition-colors">
                                            <span class="w-6 h-6 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs flex-shrink-0">
                                                <i class="fas fa-users"></i>
                                            </span>
                                            <div class="flex-1 flex items-center justify-between">
                                                <span>Pendaftar</span>
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">
                                                    {{ $campaign->kuota_terisi }}
                                                </span>
                                            </div>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.relawan.edit', $campaign->id) }}" 
                                           class="flex items-center gap-2.5 px-3.5 py-2 hover:bg-amber-50 hover:text-amber-600 text-gray-700 font-semibold transition-colors">
                                            <span class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center text-xs flex-shrink-0">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                            <span>Edit Kampanye</span>
                                        </a>
                                    </div>

                                    {{-- Group 2: Status & Hapus --}}
                                    <div class="py-1">
                                        {{-- Toggle Status --}}
                                        <form action="{{ route('admin.relawan.update', $campaign->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="judul" value="{{ $campaign->judul }}">
                                            <input type="hidden" name="kategori" value="{{ $campaign->kategori }}">
                                            <input type="hidden" name="lokasi" value="{{ $campaign->lokasi }}">
                                            <input type="hidden" name="kuota_total" value="{{ $campaign->kuota_total }}">
                                            <input type="hidden" name="tanggal_mulai" value="{{ $campaign->tanggal_mulai }}">
                                            <input type="hidden" name="tanggal_selesai" value="{{ $campaign->tanggal_selesai }}">
                                            <input type="hidden" name="deskripsi" value="{{ $campaign->deskripsi }}">
                                            <input type="hidden" name="status" value="{{ $campaign->status == 'Aktif' ? 'Nonaktif' : 'Aktif' }}">
                                            <button type="submit" 
                                                    class="w-full flex items-center gap-2.5 px-3.5 py-2 hover:bg-gray-50 text-left font-semibold transition-colors {{ $campaign->status == 'Aktif' ? 'text-orange-600 hover:bg-orange-50' : 'text-emerald-600 hover:bg-emerald-50' }}">
                                                <span class="w-6 h-6 rounded-md {{ $campaign->status == 'Aktif' ? 'bg-orange-50 text-orange-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center text-xs flex-shrink-0">
                                                    <i class="fas {{ $campaign->status == 'Aktif' ? 'fa-pause' : 'fa-play' }}"></i>
                                                </span>
                                                <span>{{ $campaign->status == 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                            </button>
                                        </form>

                                        {{-- Hapus --}}
                                        <form action="{{ route('admin.relawan.destroy', $campaign->id) }}" method="POST" class="m-0"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kampanye {{ addslashes($campaign->judul) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full flex items-center gap-2.5 px-3.5 py-2 hover:bg-red-50 text-red-600 text-left font-semibold transition-colors">
                                                <span class="w-6 h-6 rounded-md bg-red-50 text-red-600 flex items-center justify-center text-xs flex-shrink-0">
                                                    <i class="fas fa-trash-alt"></i>
                                                </span>
                                                <span>Hapus Kampanye</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-gray-100 p-4 rounded-full mb-3">
                                    <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-gray-900 font-medium text-lg">Belum ada kampanye</h3>
                                <p class="text-gray-500 text-sm mt-1 mb-4">Mulai buat gerakan kebaikan pertama Anda hari ini.</p>
                                <a href="{{ route('admin.relawan.create') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm hover:underline">
                                    + Tambah Kampanye Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($campaigns->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $campaigns->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleRelawanActionMenu(event, menuId) {
        event.stopPropagation();
        const menu = document.getElementById(menuId);
        if (!menu) return;

        const isCurrentlyOpen = !menu.classList.contains('hidden');

        // Tutup semua menu lain
        closeAllRelawanMenus();

        if (!isCurrentlyOpen) {
            const btn = event.currentTarget;
            const rect = btn.getBoundingClientRect();
            
            menu.classList.remove('hidden');

            const menuWidth = menu.offsetWidth || 208;
            const menuHeight = menu.offsetHeight || 220;

            // Posisikan menu: sejajarkan kanan menu dengan kanan tombol
            let left = rect.right - menuWidth;
            if (left < 10) left = 10;

            // Posisikan vertikal: jika dekat tepi bawah layar, balikkan ke atas tombol
            let top = rect.bottom + 6;
            if (top + menuHeight > window.innerHeight && rect.top > menuHeight) {
                top = rect.top - menuHeight - 6;
            }

            menu.style.top = `${top}px`;
            menu.style.left = `${left}px`;
        }
    }

    function closeAllRelawanMenus() {
        document.querySelectorAll('.relawan-action-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    // Tutup dropdown saat klik di luar area
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relawan-action-menu')) {
            closeAllRelawanMenus();
        }
    });

    // Tutup saat window scroll atau resize agar posisi tidak melayang salah
    window.addEventListener('scroll', closeAllRelawanMenus, true);
    window.addEventListener('resize', closeAllRelawanMenus);
</script>
@endpush