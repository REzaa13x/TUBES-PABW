@extends('admin.layouts.master')

@section('content')
<div class="container px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Edit Kontak Validator</h2>

    <div class="px-8 py-10 mb-8 bg-white rounded-3xl shadow-sm border border-gray-100 max-w-2xl">
        <form action="{{ route('admin.validator-contacts.update', $validatorContact->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $validatorContact->name }}" required class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-0 text-sm" placeholder="Contoh: Budi Santoso">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp</label>
                <input type="text" name="phone" value="{{ $validatorContact->phone }}" required class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-0 text-sm" placeholder="Contoh: 628123456789">
                <p class="mt-2 text-[10px] text-gray-400 italic font-medium">* Gunakan kode negara (misal 62 untuk Indonesia) tanpa spasi atau tanda (+).</p>
            </div>

            <div class="flex gap-4 pt-4 border-t border-gray-50">
                <button type="submit" class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-blue-500/20 transition-all">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.validator-contacts.index') }}" class="flex-1 py-4 bg-gray-100 hover:bg-gray-200 text-gray-600 text-center font-black uppercase tracking-widest rounded-2xl transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
