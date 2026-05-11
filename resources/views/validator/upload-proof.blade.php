@extends('validator.layouts.validator')

@section('title', 'Upload Bukti Penyaluran')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-6 text-3xl shadow-sm">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Form Penyaluran Dana</h1>
            <p class="text-slate-400 text-sm font-medium">Mohon isi data penyaluran dengan jujur dan lengkap.</p>
        </div>

        {{-- Info Kampanye Aktif --}}
        <div class="mb-10 p-6 bg-blue-50 rounded-3xl border border-blue-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 shadow-sm">
                <img src="{{ $campaign->image }}" class="w-full h-full object-cover">
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Melaporkan Untuk:</p>
                <h4 class="text-sm font-black text-slate-800 leading-tight">{{ $campaign->title }}</h4>
                <p class="text-[10px] font-bold text-blue-600 mt-1">Saldo Tersedia: Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
            </div>
        </div>

        <form action="{{ route('validator.store', $token) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- Amount -->
            <div class="space-y-3">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Jumlah Dana yang Disalurkan (Rp)</label>
                <div class="relative">
                    <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-slate-400">Rp</span>
                    <input type="number" name="amount" required min="1" placeholder="Contoh: 1000000"
                           class="w-full pl-14 pr-6 py-5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-600 focus:ring-0 transition-all font-black text-slate-800">
                </div>
                @error('amount') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div class="space-y-3">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Keterangan Penyaluran</label>
                <textarea name="description" required rows="4" placeholder="Jelaskan detail penyaluran (contoh: Pembelian 100 paket sembako untuk warga terdampak banjir)"
                          class="w-full px-6 py-5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-600 focus:ring-0 transition-all font-medium text-slate-600"></textarea>
                @error('description') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <!-- Photo Upload -->
            <div class="space-y-3">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Dokumentasi / Foto Penyaluran</label>
                <div class="relative group">
                    <input type="file" name="proof_image" id="proof_image" required accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div id="preview-container" class="w-full min-h-[200px] bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl flex flex-col items-center justify-center p-8 transition-all group-hover:border-blue-300 group-hover:bg-blue-50/30">
                        <i class="fas fa-image text-3xl text-slate-300 mb-4" id="upload-icon"></i>
                        <p class="text-sm font-bold text-slate-400" id="upload-text">Klik atau seret foto ke sini</p>
                        <p class="text-[10px] text-slate-300 mt-2">JPG, PNG atau WEBP (Maks. 5MB)</p>
                        <img id="image-preview" src="#" alt="Preview" class="hidden mt-4 rounded-xl max-h-48 shadow-lg">
                    </div>
                </div>
                @error('proof_image') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-500/20 transition-all transform hover:-translate-y-1">
                    Kirim Laporan Penyaluran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('proof_image').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            const preview = document.getElementById('image-preview');
            const icon = document.getElementById('upload-icon');
            const text = document.getElementById('upload-text');
            const container = document.getElementById('preview-container');
            
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            icon.classList.add('hidden');
            text.textContent = 'Ganti Foto';
            container.classList.add('border-blue-400', 'bg-blue-50/50');
        }
    }
</script>
@endsection
