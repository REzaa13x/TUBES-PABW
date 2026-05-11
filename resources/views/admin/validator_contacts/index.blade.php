@extends('admin.layouts.master')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center my-6">
        <h2 class="text-2xl font-semibold text-gray-700">Daftar Kontak Validator</h2>
        <a href="{{ route('admin.validator-contacts.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-sm">
            + Tambah Kontak
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="w-full overflow-hidden rounded-lg shadow-xs border border-gray-100 bg-white p-6">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Nama Validator</th>
                        <th class="px-4 py-3">Nomor WhatsApp</th>
                        <th class="px-4 py-3">Ditambahkan Pada</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($contacts as $contact)
                    <tr class="text-gray-700 hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                    <i class="fas fa-user text-xs"></i>
                                </div>
                                <p class="font-bold">{{ $contact->name }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center text-green-600 font-bold">
                                <i class="fab fa-whatsapp mr-2"></i>
                                {{ $contact->phone }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $contact->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('admin.validator-contacts.edit', $contact->id) }}" class="text-blue-600 hover:text-blue-900 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.validator-contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Hapus kontak ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-address-book text-4xl mb-3 opacity-20"></i>
                                <p class="text-sm font-medium">Belum ada kontak validator tersimpan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@endsection
