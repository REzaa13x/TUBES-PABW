@extends('admin.layouts.master')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Edit Kampanye Donasi</h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.campaigns.update', $campaign->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- PENTING: Method PUT untuk update --}}

            {{-- Title --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-bold mb-2">Judul</label>
                <input name="title" value="{{ old('title', $campaign->title) }}" class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-bold mb-2">Deskripsi</label>
                <textarea name="description" rows="5" class="w-full border px-3 py-2 rounded-lg" required>{{ old('description', $campaign->description) }}</textarea>
            </div>

            {{-- Target Amount & Current Amount --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-2">Target Donasi (Rp)</label>
                    <input type="number" name="target_amount" value="{{ old('target_amount', $campaign->target_amount) }}" class="w-full border px-3 py-2 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-2">Donasi Terkumpul (Rp)</label>
                    <input type="number" name="current_amount" value="{{ old('current_amount', $campaign->current_amount) }}" class="w-full border px-3 py-2 rounded-lg">
                </div>
            </div>

            {{-- Status & End Date --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-2">Status</label>
                    <select name="status" class="w-full border px-3 py-2 rounded-lg">
                        <option value="verified" {{ $campaign->status == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="pending" {{ $campaign->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ $campaign->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-2">Tanggal Berakhir</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $campaign->end_date) }}" class="w-full border px-3 py-2 rounded-lg" required>
                </div>
            </div>

            {{-- Yayasan --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-bold mb-2">Nama Yayasan</label>
                <input name="yayasan" value="{{ old('yayasan', $campaign->yayasan) }}" class="w-full border px-3 py-2 rounded-lg" placeholder="Contoh: Yayasan Peduli Lingkungan Indonesia">
            </div>

            {{-- Kategori --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-bold mb-2">Kategori</label>
                <select name="kategori" class="w-full border px-3 py-2 rounded-lg" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Lingkungan" {{ old('kategori', $campaign->kategori) == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                    <option value="Kesehatan" {{ old('kategori', $campaign->kategori) == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                    <option value="Pendidikan" {{ old('kategori', $campaign->kategori) == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                    <option value="Sosial Kemanusiaan" {{ old('kategori', $campaign->kategori) == 'Sosial Kemanusiaan' ? 'selected' : '' }}>Sosial Kemanusiaan</option>
                    <option value="Bencana Alam" {{ old('kategori', $campaign->kategori) == 'Bencana Alam' ? 'selected' : '' }}>Bencana Alam</option>
                </select>
            </div>

            {{-- Lokasi Kampanye --}}
            <div class="mb-4 p-4 rounded-xl border border-gray-100 bg-gray-50/50 space-y-4">
                <span class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-600">
                    <i class="fas fa-map-marked-alt text-blue-600 text-sm"></i> Detail Lokasi Donasi
                </span>

                <!-- Consolidated lokasi field (sent to database) -->
                <input type="hidden" name="lokasi" id="admin_lokasi_consolidated" value="{{ old('lokasi', $campaign->lokasi) }}" required>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-700 text-xs font-bold">Provinsi</span>
                        <select id="admin_provinsi" name="provinsi" onchange="onAdminProvinsiChanged()" class="w-full border px-3 py-2 rounded-lg bg-white mt-1 text-sm font-semibold" required>
                            <option value="">Pilih Provinsi</option>
                            <option value="DKI Jakarta" {{ old('provinsi', $campaign->provinsi) == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                            <option value="Jawa Barat" {{ old('provinsi', $campaign->provinsi) == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                            <option value="Jawa Tengah" {{ old('provinsi', $campaign->provinsi) == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                            <option value="Jawa Timur" {{ old('provinsi', $campaign->provinsi) == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                            <option value="Bali" {{ old('provinsi', $campaign->provinsi) == 'Bali' ? 'selected' : '' }}>Bali</option>
                            <option value="Sumatera Utara" {{ old('provinsi', $campaign->provinsi) == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                            <option value="Sulawesi Selatan" {{ old('provinsi', $campaign->provinsi) == 'Sulawesi Selatan' ? 'selected' : '' }}>Sulawesi Selatan</option>
                        </select>
                    </div>
                    <div>
                        <span class="text-gray-700 text-xs font-bold">Kota / Kabupaten</span>
                        <select id="admin_kabupaten" name="kabupaten" onchange="updateAdminConsolidatedLocation()" class="w-full border px-3 py-2 rounded-lg bg-white mt-1 text-sm font-semibold" required>
                            <option value="">Pilih Kota / Kabupaten</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-700 text-xs font-bold">Kecamatan (Opsional)</span>
                        <input type="text" id="admin_kecamatan" name="kecamatan" value="{{ old('kecamatan', $campaign->kecamatan) }}" oninput="updateAdminConsolidatedLocation()" class="w-full border px-3 py-2 rounded-lg bg-white mt-1 text-sm font-medium" placeholder="Bojongsoang" />
                    </div>
                    <div>
                        <span class="text-gray-700 text-xs font-bold">Kode Pos (Opsional)</span>
                        <input type="text" id="admin_kode_pos" name="kode_pos" value="{{ old('kode_pos', $campaign->kode_pos) }}" oninput="updateAdminConsolidatedLocation()" class="w-full border px-3 py-2 rounded-lg bg-white mt-1 text-sm font-medium" placeholder="40257" />
                    </div>
                </div>

                <div>
                    <span class="text-gray-700 text-xs font-bold">Alamat Lengkap</span>
                    <textarea id="admin_alamat_lengkap" name="alamat_lengkap" rows="2" oninput="updateAdminConsolidatedLocation()" class="w-full border px-3 py-2 rounded-lg bg-white mt-1 text-sm font-medium" placeholder="Nama jalan, nomor rumah, RT/RW, dsb." required>{{ old('alamat_lengkap', $campaign->alamat_lengkap) }}</textarea>
                </div>
            </div>

            {{-- Gambar --}}
            <div class="mb-6">
                <label class="block text-sm text-gray-700 font-bold mb-2">Ganti Gambar (Opsional)</label>
                <input type="file" name="image" class="w-full border px-3 py-2 rounded-lg">
                @if($campaign->image)
                <p class="text-xs text-gray-500 mt-2">Gambar saat ini:</p>
                <img src="{{ $campaign->image }}" class="h-20 w-auto rounded mt-1">
                @endif
            </div>

            {{-- Buttons --}}
            <div class="flex justify-between items-center pt-4 border-t border-gray-100 mt-6">
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Update Kampanye Donasi</button>
                    <a href="{{ route('admin.campaigns.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-400 transition">Batal</a>
                </div>
                
                {{-- Tombol Hapus --}}
                <button type="button" onclick="confirmDeleteCampaign()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fas fa-trash-alt text-sm"></i> Hapus Kampanye
                </button>
            </div>
        </form>

        <form id="delete-campaign-form" action="{{ route('admin.campaigns.destroy', $campaign->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <script>
            function confirmDeleteCampaign() {
                if (confirm('Apakah Anda yakin ingin menghapus kampanye donasi ini secara permanen? Semua data transaksi dan penyaluran terkait juga akan terhapus.')) {
                    document.getElementById('delete-campaign-form').submit();
                }
            }
        </script>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

    function onAdminProvinsiChanged() {
        const provSelect = document.getElementById('admin_provinsi');
        const kabSelect = document.getElementById('admin_kabupaten');
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

        updateAdminConsolidatedLocation();
    }

    function updateAdminConsolidatedLocation() {
        const prov = document.getElementById('admin_provinsi').value;
        const kab = document.getElementById('admin_kabupaten').value;
        const kec = document.getElementById('admin_kecamatan').value;
        const alamat = document.getElementById('admin_alamat_lengkap').value;
        const zip = document.getElementById('admin_kode_pos').value;

        const parts = [];
        if (alamat) parts.push(alamat);
        if (kec) parts.push(kec);
        if (kab) parts.push(kab);
        if (prov) parts.push(prov);
        if (zip) parts.push(zip);

        const consolidated = parts.join(', ');
        document.getElementById('admin_lokasi_consolidated').value = consolidated;
    }

    // Set initial city if province is set
    window.addEventListener('DOMContentLoaded', () => {
        const initialProv = "{{ old('provinsi', $campaign->provinsi) }}";
        const initialKab = "{{ old('kabupaten', $campaign->kabupaten) }}";

        if (initialProv) {
            onAdminProvinsiChanged();
            document.getElementById('admin_kabupaten').value = initialKab;
        }
    });
</script>
@endpush