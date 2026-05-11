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
                                            <i class="fas fa-user-edit mr-1"></i> Pembuat: {{ $campaign->user->name ?? 'Admin' }}
                                        </p>
                                        <p class="text-[10px] text-gray-500">
                                            <i class="far fa-calendar-alt mr-1"></i> Berakhir: {{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}
                                        </p>
                                        <div id="validator-info-{{ $campaign->id }}">
                                            @if($campaign->validator_name)
                                                @if(strtolower($campaign->status) == 'pending')
                                                    <p class="text-[10px] text-orange-500 font-bold uppercase italic">
                                                        <i class="fas fa-user-clock mr-1"></i> Menunggu Verifikasi: {{ $campaign->validator_name }}
                                                    </p>
                                                @else
                                                    <p class="text-[10px] text-blue-600 font-black uppercase tracking-tighter">
                                                        <i class="fas fa-user-check mr-1"></i> Diverifikasi Oleh: {{ $campaign->validator_name }}
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
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
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Tombol Delete --}}
                                <form action="{{ route('admin.campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kampanye donasi ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                {{-- Tombol Link Portal Validator (Unified) --}}
                                <button type="button" 
                                        onclick="showValidatorSelection({{ $campaign->id }}, '{{ $campaign->title }}')"
                                        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg focus:outline-none focus:shadow-outline-gray" 
                                        title="Buka Portal Validator">
                                    <i class="fas fa-shield-halved"></i>
                                </button>
                                
                                {{-- Form Tersembunyi untuk Generate Link --}}
                                <form id="form-generate-link-{{ $campaign->id }}" action="{{ route('admin.campaigns.generateLink', $campaign->id) }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="validator_id" id="selected-validator-{{ $campaign->id }}">
                                </form>
                                
                                @if($campaign->distribution_token)
                                    <button type="button" 
                                            data-url="{{ route('validator.dashboard', $campaign->distribution_token) }}"
                                            data-title="{{ $campaign->title }}"
                                            data-validator="{{ $campaign->validator_name }}"
                                            data-phone="{{ $campaign->validator_phone }}"
                                            onclick="handleShareClick(this)"
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-green-500 rounded-lg focus:outline-none focus:shadow-outline-gray" 
                                            title="Bagikan Link Portal Validator">
                                        <i class="fab fa-whatsapp"></i>
                                    </button>
                                @endif
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

    async function showValidatorSelection(campaignId, campaignTitle) {
        const validators = {!! json_encode($validators->keyBy('id')) !!};
        
        let options = '<option value="">-- Lewati (Validator Tamu) --</option>';
        for (const [id, contact] of Object.entries(validators)) {
            options += `<option value="${id}">${contact.name} (${contact.phone})</option>`;
        }

        const { value: validatorId } = await Swal.fire({
            title: 'Pilih Validator',
            text: `Tentukan penanggung jawab untuk: ${campaignTitle}`,
            html: `<select id="validator-select" class="swal2-input">${options}</select>`,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Generate Link',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                return document.getElementById('validator-select').value;
            }
        });

        if (validatorId !== undefined) {
            // Tampilkan loading
            Swal.fire({
                title: 'Sedang membuat link...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Kirim via AJAX
            fetch(`/admin/campaigns/${campaignId}/generate-link`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'validator_id': validatorId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update UI secara instan
                    const infoDiv = document.getElementById(`validator-info-${campaignId}`);
                    if (infoDiv) {
                        infoDiv.innerHTML = `
                            <p class="text-[10px] text-orange-500 font-bold uppercase italic">
                                <i class="fas fa-user-clock mr-1"></i> Menunggu Verifikasi: ${data.validator_name}
                            </p>
                        `;
                    }

                    // Lanjut proses share link
                    shareDistributionLink(data.link, campaignTitle, data.validator_name, data.validator_phone);
                } else {
                    Swal.fire('Error', 'Gagal membuat link. Silakan coba lagi.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
            });
        }
    }

    function handleShareClick(btn) {
        const url = btn.getAttribute('data-url');
        const title = btn.getAttribute('data-title');
        const validator = btn.getAttribute('data-validator');
        const phone = btn.getAttribute('data-phone');
        shareDistributionLink(url, title, validator, phone);
    }

    async function shareDistributionLink(finalUrl, campaignTitle, validatorName, validatorPhone) {
        let phoneNumber = validatorPhone;
        const validators = {!! json_encode($validators->keyBy('id')) !!};

        // Jika nomor belum terdaftar, tampilkan LIST (Dropdown) bukan input manual
        if (!phoneNumber) {
            let options = '<option value="">-- Pilih dari Daftar --</option>';
            for (const [id, contact] of Object.entries(validators)) {
                options += `<option value="${contact.phone}">${contact.name} (${contact.phone})</option>`;
            }

            const { value: selectedPhone } = await Swal.fire({
                title: 'Pilih Tujuan Pengiriman',
                text: `Pilih validator untuk kampanye: ${campaignTitle}`,
                html: `
                    <div class="text-left mb-2 text-sm text-gray-500">Nomor WhatsApp ${validatorName || 'Validator'} belum terdaftar.</div>
                    <select id="swal-validator-select" class="swal2-input w-full">
                        ${options}
                    </select>
                    <div class="mt-4 text-[10px] text-gray-400 italic">Atau masukkan manual di bawah jika tidak ada di daftar:</div>
                    <input id="swal-manual-phone" class="swal2-input" placeholder="62812345xxx">
                `,
                showCancelButton: true,
                confirmButtonText: 'Kirim via WhatsApp',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#25D366',
                preConfirm: () => {
                    const dropdownVal = document.getElementById('swal-validator-select').value;
                    const manualVal = document.getElementById('swal-manual-phone').value;
                    return manualVal || dropdownVal;
                }
            });
            phoneNumber = selectedPhone;
        }

        // Jika user memilih nomor atau mengisi manual
        if (phoneNumber) {
            // Bersihkan nomor dari karakter non-angka
            let cleanNumber = phoneNumber.replace(/[^0-9]/g, '');
            
            // Otomatis ubah 08xxx menjadi 628xxx (Indonesia)
            if (cleanNumber.startsWith('0')) {
                cleanNumber = '62' + cleanNumber.substring(1);
            }

            const greeting = validatorName ? `Halo *${validatorName}*` : 'Halo';
            const message = `${greeting}, berikut adalah link Portal Validator khusus untuk kampanye *${campaignTitle}*:\n\n${finalUrl}\n\nMelalui link ini, Anda dapat menyetujui kampanye dan mengunggah bukti penyaluran. Terima kasih!`;
            
            // Gunakan format wa.me yang lebih stabil
            const waUrl = `https://wa.me/${cleanNumber}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank').focus();
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Link Berhasil Disalin!',
                text: 'Link telah siap di clipboard Anda.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });
    }
</script>
@endpush