@extends('admin.layouts.master')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Edit Kampanye</h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.campaigns.update', $campaign->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- PENTING: Method PUT untuk update --}}

            {{-- Judul --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-bold mb-2">Judul</label>
                <input name="judul" value="{{ old('judul', $campaign->judul) }}" class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>

            {{-- Status & Kategori --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-2">Status</label>
                    <select name="status" class="w-full border px-3 py-2 rounded-lg">
                        <option value="Aktif" {{ $campaign->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ $campaign->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="Selesai" {{ $campaign->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-2">Kategori</label>
                    <select name="kategori" class="w-full border px-3 py-2 rounded-lg">
                        @foreach(['Pendidikan', 'Bencana', 'Kesehatan', 'Lingkungan', 'Sosial'] as $cat)
                        <option value="{{ $cat }}" {{ $campaign->kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Lokasi & Kuota --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-2">Lokasi</label>
                    <input name="lokasi" value="{{ old('lokasi', $campaign->lokasi) }}" class="w-full border px-3 py-2 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-2">Target Kuota</label>
                    <input type="number" name="kuota_total" value="{{ old('kuota_total', $campaign->kuota_total) }}" class="w-full border px-3 py-2 rounded-lg" required>
                </div>
            </div>

            {{-- Tanggal --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-2">Tgl Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $campaign->tanggal_mulai) }}" class="w-full border px-3 py-2 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-2">Tgl Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $campaign->tanggal_selesai) }}" class="w-full border px-3 py-2 rounded-lg" required>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-bold mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="5" class="w-full border px-3 py-2 rounded-lg" required>{{ old('deskripsi', $campaign->deskripsi) }}</textarea>
            </div>

            {{-- Gambar --}}
            <div class="mb-6">
                <label class="block text-sm text-gray-700 font-bold mb-2">Ganti Gambar (Opsional)</label>
                <input type="file" name="image" class="w-full border px-3 py-2 rounded-lg">
                @if($campaign->image)
                <p class="text-xs text-gray-500 mt-2">Gambar saat ini:</p>
                <img src="{{ asset('storage/' . $campaign->image) }}" class="h-20 w-auto rounded mt-1">
                @endif
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Update Kampanye</button>
                <a href="{{ route('admin.campaigns.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-400 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection