@extends('admin.layouts.master')

@section('title', 'Buat Laporan Penyaluran Kampanye')

@section('content')
<div class="max-w-3xl mx-auto pb-16">
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-200 flex-shrink-0">
                    <i class="fas fa-file-signature text-base"></i>
                </div>
                <span>Buat Laporan Penyaluran Kampanye</span>
            </h1>
            <p class="text-slate-500 text-sm mt-1">Dokumentasikan hasil penyaluran dana agar validator dapat memantaunya secara transparan.</p>
        </div>
        <a href="{{ route('admin.distribution.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-all self-start sm:self-auto flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-2xl text-xs font-semibold">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.distribution.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Pilih Kampanye --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Pilih Kampanye Donasi <span class="text-rose-500">*</span>
                </label>
                <select name="campaign_id" id="campaign_id" required onchange="updateCampaignInfo()"
                        class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:border-blue-500 font-medium">
                    <option value="">-- Pilih Program Kampanye --</option>
                    @foreach($campaigns as $camp)
                        <option value="{{ $camp->id }}" 
                                data-target="{{ $camp->target_amount }}"
                                data-current="{{ $camp->current_amount }}"
                                data-image="{{ $camp->image }}"
                                {{ (old('campaign_id') == $camp->id || (isset($selectedCampaign) && $selectedCampaign->id == $camp->id)) ? 'selected' : '' }}>
                            {{ $camp->title }} (Terkumpul: Rp {{ number_format($camp->current_amount, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                <div id="campaign-info-box" class="hidden mt-3 p-3 bg-blue-50/60 border border-blue-100 rounded-xl flex items-center justify-between text-xs font-semibold text-blue-900">
                    <span id="camp-current-text">Dana Terkumpul: Rp 0</span>
                    <span id="camp-target-text" class="text-slate-500">Target: Rp 0</span>
                </div>
            </div>

            {{-- Nominal Dana Disalurkan & Penerima Manfaat --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nominal Dana Disalurkan (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" name="amount" value="{{ old('amount') }}" required min="1"
                               placeholder="Contoh: 5000000"
                               class="w-full pl-11 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 font-bold text-slate-800">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Penerima Manfaat / Lembaga <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="recipient" value="{{ old('recipient') }}" required
                           placeholder="Contoh: Warga Terdampak Longsor RT 03 / Panti Kasih"
                           class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 font-medium">
                </div>
            </div>

            {{-- Keterangan Hasil Pelaksanaan / Penyaluran --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Uraian Hasil Penyaluran & Kegiatan <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" rows="4" required
                          placeholder="Jelaskan secara detail hasil kegiatan kampanye, jumlah bantuan (misal 50 paket sembako, biaya pengobatan, dll), serta kondisi penerima di lapangan..."
                          class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 font-medium leading-relaxed">{{ old('description') }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Uraian ini akan langsung dibaca oleh validator saat meninjau laporan hasil kampanye.</p>
            </div>

            {{-- Upload Foto Dokumentasi Bukti Penyaluran --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Foto Dokumentasi Penyaluran <span class="text-rose-500">*</span>
                </label>
                <div class="border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-2xl p-6 text-center cursor-pointer bg-slate-50/50 hover:bg-blue-50/20 transition-all relative">
                    <input type="file" name="proof_image" id="proof_image" required accept="image/*"
                           onchange="previewProofImage(this)"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div id="upload-prompt" class="space-y-2">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto text-xl">
                            <i class="fas fa-camera"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-700">Pilih atau Seret Foto Dokumentasi ke Sini</p>
                        <p class="text-[10px] text-slate-400">Format JPG, PNG, atau WEBP (Maksimal 5MB)</p>
                    </div>
                    <div id="image-preview-wrapper" class="hidden mt-3">
                        <img id="image-preview" src="#" alt="Preview Foto Dokumentasi" class="max-h-56 mx-auto rounded-xl shadow-md border border-slate-200 object-cover">
                        <p class="text-xs font-bold text-blue-600 mt-2"><i class="fas fa-check-circle"></i> Foto siap diunggah</p>
                    </div>
                </div>
            </div>

            {{-- Status & Catatan Tambahan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Laporan</label>
                    <select name="status" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 font-medium">
                        <option value="verified" {{ old('status', 'verified') == 'verified' ? 'selected' : '' }}>Terverifikasi Resmi (Langsung Tampil)</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu Konfirmasi Lanjutan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan Admin (Opsional)</label>
                    <input type="text" name="admin_note" value="{{ old('admin_note', 'Penyaluran resmi telah terlaksana dan terverifikasi.') }}"
                           placeholder="Catatan pelengkap untuk validator"
                           class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 font-medium">
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.distribution.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-200 transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-check-circle mr-1.5"></i> Terbitkan Laporan Penyaluran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateCampaignInfo() {
        const select = document.getElementById('campaign_id');
        const infoBox = document.getElementById('campaign-info-box');
        const selected = select.options[select.selectedIndex];
        
        if (selected && selected.value) {
            const current = parseInt(selected.getAttribute('data-current') || '0').toLocaleString('id-ID');
            const target = parseInt(selected.getAttribute('data-target') || '0').toLocaleString('id-ID');
            document.getElementById('camp-current-text').innerText = 'Dana Terkumpul: Rp ' + current;
            document.getElementById('camp-target-text').innerText = 'Target: Rp ' + target;
            infoBox.classList.remove('hidden');
        } else {
            infoBox.classList.add('hidden');
        }
    }

    function previewProofImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview-wrapper').classList.remove('hidden');
                document.getElementById('upload-prompt').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', updateCampaignInfo);
</script>
@endsection
