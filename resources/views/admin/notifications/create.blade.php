@extends('admin.layouts.master')

@section('title', 'Tambah Notifikasi')

@section('content')
<div class="max-w-2xl mx-auto pb-16">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2.5">
                <i class="fas fa-plus-circle text-blue-600"></i>
                Tambah Notifikasi
            </h1>
            <p class="text-slate-500 text-sm mt-1">Buat pesan pemberitahuan untuk sistem atau pengguna.</p>
        </div>
        <a href="{{ route('admin.notifications.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-all">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.notifications.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Judul --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Judul Notifikasi <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="Contoh: Pemeliharaan Sistem atau Info Penting"
                       class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all font-medium @error('title') border-rose-400 @enderror">
                @error('title')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kategori & Prioritas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori</label>
                    <select name="category" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all font-medium">
                        <option value="sistem" {{ old('category') == 'sistem' ? 'selected' : '' }}>Sistem</option>
                        <option value="kampanye" {{ old('category') == 'kampanye' ? 'selected' : '' }}>Kampanye</option>
                        <option value="donasi" {{ old('category') == 'donasi' ? 'selected' : '' }}>Donasi</option>
                        <option value="relawan" {{ old('category') == 'relawan' ? 'selected' : '' }}>Relawan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Prioritas</label>
                    <select name="priority" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all font-medium">
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>
            </div>

            {{-- Pesan --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Isi Pesan <span class="text-rose-500">*</span>
                </label>
                <textarea name="message" rows="4" required
                          placeholder="Tuliskan pesan notifikasi secara jelas dan ringkas..."
                          class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all font-medium @error('message') border-rose-400 @enderror">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Checkbox Broadcast --}}
            <div class="flex items-center gap-2.5 pt-2">
                <input type="checkbox" name="send_to_users" id="send_to_users" value="1" {{ old('send_to_users') ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                <label for="send_to_users" class="text-xs font-semibold text-slate-700 cursor-pointer">
                    Kirimkan juga notifikasi ini ke seluruh pengguna DonGiv
                </label>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.notifications.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-200 transition-all transform hover:-translate-y-0.5">
                    Kirim Notifikasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
