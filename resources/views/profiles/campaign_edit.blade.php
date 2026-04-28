<x-app title="Edit Kampanye">
    <div class="min-h-screen bg-slate-50/80 py-12 font-sans">
        <div class="container mx-auto px-4 lg:px-8 max-w-2xl">
            
            <div class="mb-8">
                <a href="{{ route('profiles.campaign.history') }}" class="text-blue-600 font-bold text-sm flex items-center gap-2 hover:underline mb-4">
                    <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                </a>
                <h1 class="text-3xl font-black text-slate-800">Edit Kampanye</h1>
                <p class="text-slate-500 mt-1">Perbarui informasi kampanye Anda.</p>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
                <form action="{{ route('profiles.campaign.update', $campaign->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Judul Kampanye</label>
                            <input type="text" name="judul" value="{{ old('judul', $campaign->title) }}" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 transition-all outline-none" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Kategori</label>
                            <select name="kategori" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 transition-all outline-none" required>
                                @foreach(['Bencana', 'Pendidikan', 'Kesehatan', 'Sosial', 'Lainnya'] as $cat)
                                    <option value="{{ $cat }}" {{ $campaign->kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 transition-all outline-none" required>{{ old('deskripsi', $campaign->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Target Dana (Rp)</label>
                                <input type="number" name="target" value="{{ old('target', $campaign->target_amount) }}" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 transition-all outline-none" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Deadline</label>
                                <input type="date" name="deadline" value="{{ old('deadline', $campaign->end_date ? $campaign->end_date->format('Y-m-d') : '') }}" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 transition-all outline-none" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Foto Kampanye (Kosongkan jika tidak diubah)</label>
                            <div class="flex items-center gap-4">
                                @if($campaign->image)
                                    <img src="{{ asset('storage/' . $campaign->image) }}" class="w-20 h-20 rounded-xl object-cover border border-slate-200">
                                @endif
                                <input type="file" name="foto" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 transition-all outline-none">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50 flex justify-end gap-4">
                            <button type="submit" class="px-10 py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:-translate-y-1">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app>
