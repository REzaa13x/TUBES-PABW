@extends('admin.layouts.master')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Buat Kampanye Baru
    </h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">

        {{-- Form Start --}}
        <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 1. Judul --}}
            <label class="block text-sm">
                <span class="text-gray-700">Judul Kampanye</span>
                <input name="judul" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" placeholder="Contoh: Mengajar Anak Jalanan..." required />
            </label>

            {{-- 2. Grid (Kategori & Lokasi) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <label class="block text-sm">
                    <span class="text-gray-700">Kategori</span>
                    <select name="kategori" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-select">
                        <option value="Pendidikan">Pendidikan</option>
                        <option value="Bencana">Bencana Alam</option>
                        <option value="Kesehatan">Kesehatan</option>
                        <option value="Lingkungan">Lingkungan</option>
                        <option value="Sosial">Sosial & Kemanusiaan</option>
                    </select>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700">Lokasi Kegiatan</span>
                    <input name="lokasi" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" placeholder="Kota / Kabupaten" required />
                </label>
            </div>

            {{-- 3. Grid (Kuota & Tanggal) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <label class="block text-sm">
                    <span class="text-gray-700">Target Relawan (Orang)</span>
                    <input type="number" name="kuota_total" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" placeholder="50" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700">Tanggal Mulai</span>
                    <input type="date" name="tanggal_mulai" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700">Tanggal Selesai</span>
                    <input type="date" name="tanggal_selesai" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input" required />
                </label>
            </div>

            {{-- 4. Upload Gambar --}}
            <label class="block mt-4 text-sm">
                <span class="text-gray-700">Foto Sampul (Cover)</span>
                <input type="file" name="image" class="block w-full mt-1 text-sm form-input border border-gray-300 rounded-md p-2" required />
                <span class="text-xs text-gray-500">Format: JPG, PNG. Max: 2MB.</span>
            </label>

            {{-- 5. Deskripsi --}}
            <label class="block mt-4 text-sm">
                <span class="text-gray-700">Deskripsi Lengkap</span>
                <textarea name="deskripsi" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-textarea" rows="5" placeholder="Jelaskan detail kegiatan, persyaratan relawan, dan fasilitas..." required></textarea>
            </label>

            {{-- Tombol Submit --}}
            <div class="mt-6">
                <button type="submit" class="px-5 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Simpan Kampanye
                </button>
                <a href="{{ route('admin.campaigns.index') }}" class="px-5 py-3 ml-2 font-medium leading-5 text-gray-700 transition-colors duration-150 bg-gray-100 border border-transparent rounded-lg active:bg-gray-200 hover:bg-gray-200 focus:outline-none focus:shadow-outline-gray">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection