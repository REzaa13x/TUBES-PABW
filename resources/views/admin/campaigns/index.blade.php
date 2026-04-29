@extends('admin.layouts.master')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center my-6">
        <h2 class="text-2xl font-semibold text-gray-700">Kelola Kampanye Donasi</h2>
        <a href="{{ route('admin.campaigns.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            + Tambah Baru
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="w-full overflow-hidden rounded-lg shadow-xs border border-gray-200">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Kampanye Donasi</th>
                        <th class="px-4 py-3">Target</th>
                        <th class="px-4 py-3">Terkumpul</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($campaigns as $campaign)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div class="relative hidden w-12 h-12 mr-3 rounded md:block">
                                    <img class="object-cover w-full h-full rounded"
                                        src="{{ $campaign->image ?: asset('images/placeholder.jpg') }}"
                                        alt="cover" loading="lazy"
                                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';" />
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $campaign->title }}</p>
                                    <div class="flex flex-col gap-0.5 mt-1">
                                        <p class="text-[10px] text-gray-500">
                                            <i class="far fa-calendar-alt mr-1"></i> Berakhir: {{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}
                                        </p>
                                        @if($campaign->validator_name)
                                        <p class="text-[10px] text-blue-600 font-black uppercase tracking-tighter">
                                            <i class="fas fa-check-circle mr-1"></i> Verified by: {{ $campaign->validator_name }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-xs">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full
                                @if($campaign->status == 'verified') text-green-700 bg-green-100
                                @elseif($campaign->status == 'rejected') text-red-700 bg-red-100
                                @else text-yellow-700 bg-yellow-100 @endif">
                                {{ $campaign->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2 text-sm">
                                @if(strtolower($campaign->status) == 'pending' && $campaign->verification_token)
                                    {{-- Tombol Bagikan Link Verifikasi --}}
                                    <button type="button" 
                                            onclick="shareVerificationLink('{{ route('verify.show', $campaign->verification_token) }}')" 
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg focus:outline-none focus:shadow-outline-gray" 
                                            title="Bagikan Link Verifikasi">
                                        <i class="fas fa-share-nodes"></i>
                                    </button>
                                @endif

                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Tombol Delete --}}
                                <form action="{{ route('admin.campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kampanye donasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Belum ada kampanye donasi. Silakan tambah baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $campaigns->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function shareVerificationLink(baseUrl) {
        const { value: formValues } = await Swal.fire({
            title: 'Bagikan Link Verifikasi',
            html:
                '<div class="text-left"><label class="text-xs font-bold text-gray-500 uppercase">Identitas Validator (Email/Nama)</label>' +
                '<input id="swal-input1" class="swal2-input mt-1" placeholder="contoh: budi@email.com"></div>' +
                '<div class="text-left mt-4"><label class="text-xs font-bold text-gray-500 uppercase">Nomor WhatsApp (Opsional)</label>' +
                '<input id="swal-input2" class="swal2-input mt-1" placeholder="contoh: 628123456789"></div>',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Lanjut',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const identity = document.getElementById('swal-input1').value;
                if (!identity) {
                    Swal.showValidationMessage('Identitas validator wajib diisi!');
                }
                return [
                    identity,
                    document.getElementById('swal-input2').value
                ]
            }
        });

        if (formValues) {
            const [validatorIdentity, phoneNumber] = formValues;
            const separator = baseUrl.includes('?') ? '&' : '?';
            const finalUrl = `${baseUrl}${separator}validator=${encodeURIComponent(validatorIdentity)}`;
            
            const { value: action } = await Swal.fire({
                title: 'Pilih Metode Berbagi',
                text: `Link verifikasi untuk ${validatorIdentity} telah dibuat.`,
                icon: 'question',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Kirim ke WhatsApp',
                denyButtonText: 'Salin Link Saja',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#25D366', // WA Green
                denyButtonColor: '#3b82f6', // Blue
            });

            if (action === true) { // Kirim ke WA
                if (!phoneNumber) {
                    Swal.fire('Info', 'Mohon masukkan nomor WhatsApp untuk menggunakan fitur ini.', 'info');
                    return;
                }
                const message = `Halo, mohon bantuannya untuk memverifikasi campaign donasi berikut ini:\n\n*${finalUrl}*\n\nTerima kasih!`;
                window.open(`https://wa.me/${phoneNumber.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`, '_blank');
            } else if (action === false) { // Salin Link (Deny button)
                navigator.clipboard.writeText(finalUrl).then(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil disalin!',
                        text: 'Link verifikasi telah siap di clipboard.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            }
        }
    }
</script>
@endpush