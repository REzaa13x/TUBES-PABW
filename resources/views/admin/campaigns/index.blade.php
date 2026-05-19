@extends('admin.layouts.master')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="mb-8 mt-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Kampanye Donasi</h1>
            <p class="mt-1 text-sm text-gray-500">Manajemen, verifikasi, dan pemantauan program kampanye donasi aktif.</p>
        </div>
        <a href="{{ route('admin.campaigns.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
            <i class="fas fa-plus text-xs"></i>
            <span>Tambah Baru</span>
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
        <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check text-xs"></i>
        </div>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="w-full overflow-hidden rounded-2xl shadow-sm border border-gray-200 bg-white">
        <div class="w-full overflow-x-auto">
            <table class="w-full divide-y divide-gray-100">
                <thead>
                    <tr class="text-xs font-bold tracking-wider text-left text-gray-400 uppercase bg-gray-50/50">
                        <th class="px-6 py-4">Kampanye Donasi</th>
                        <th class="px-6 py-4 text-right">Target</th>
                        <th class="px-6 py-4 text-right">Terkumpul</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($campaigns as $campaign)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        {{-- Kampanye Info --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center text-sm">
                                <div class="relative hidden w-12 h-12 mr-3 rounded-lg md:block overflow-hidden border border-gray-200 flex-shrink-0 bg-gray-50">
                                    <img class="object-cover w-full h-full"
                                        src="{{ $campaign->image ?: asset('images/placeholder.jpg') }}"
                                        alt="cover" loading="lazy"
                                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';" />
                                </div>
                                <div>
                                    <p class="font-bold text-gray-950 text-sm leading-snug">{{ $campaign->title }}</p>
                                    <div class="flex flex-col gap-0.5 mt-1.5">
                                        <p class="text-[10px] text-gray-500 font-semibold flex items-center gap-1">
                                            <i class="fas fa-user-edit text-[9px]"></i> Pembuat: {{ $campaign->user->name ?? 'Admin' }}
                                        </p>
                                        <p class="text-[10px] text-gray-500 font-semibold flex items-center gap-1">
                                            <i class="far fa-calendar-alt text-[9px]"></i> Berakhir: {{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}
                                        </p>
                                        <div id="validator-info-{{ $campaign->id }}">
                                            @if($campaign->validator_name)
                                                @if(strtolower($campaign->status) == 'pending')
                                                    <p class="text-[10px] text-orange-500 font-bold uppercase italic mt-0.5 flex items-center gap-1">
                                                        <i class="fas fa-user-clock text-[9px]"></i> Menunggu Verifikasi: {{ $campaign->validator_name }}
                                                    </p>
                                                @else
                                                    <p class="text-[10px] text-blue-600 font-black uppercase tracking-tighter mt-0.5 flex items-center gap-1">
                                                        <i class="fas fa-user-check text-[9px]"></i> Diverifikasi Oleh: {{ $campaign->validator_name }}
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Target --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                            Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                        </td>
                        
                        {{-- Terkumpul --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-blue-600">
                            Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}
                        </td>
                        
                        {{-- Status Badge --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border
                                @if($campaign->status == 'verified') text-emerald-700 bg-emerald-50 border-emerald-200
                                @elseif($campaign->status == 'rejected') text-red-700 bg-red-50 border-red-200
                                @else text-amber-700 bg-amber-50 border-amber-200 @endif">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                        
                        {{-- Unified Action Column --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                {{-- Tombol Link Portal Validator (Unified) --}}
                                <button type="button" 
                                        onclick="showValidatorSelection({{ $campaign->id }}, '{{ $campaign->title }}', {{ $campaign->distribution_token ? 'true' : 'false' }}, '{{ $campaign->validator_phone }}', '{{ $campaign->validator_name }}', '{{ $campaign->distribution_token ? route('validator.dashboard', $campaign->distribution_token) : '' }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 hover:text-blue-700 rounded-lg text-xs font-bold shadow-sm transition-all whitespace-nowrap" 
                                        title="Tentukan Validator Kampanye">
                                    <i class="fas fa-user-shield text-xs"></i>
                                    <span>Pilih Validator</span>
                                </button>

                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-slate-700 border border-gray-200 hover:bg-gray-50 hover:text-blue-600 rounded-lg text-xs font-bold shadow-sm transition-all whitespace-nowrap"
                                   title="Edit Kampanye">
                                    <i class="fas fa-edit text-xs"></i>
                                    <span>Edit</span>
                                </a>
                            </div>    
                                {{-- Form Tersembunyi untuk Generate Link --}}
                                <form id="form-generate-link-{{ $campaign->id }}" action="{{ route('admin.campaigns.generateLink', $campaign->id) }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="validator_id" id="selected-validator-{{ $campaign->id }}">
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300 border border-gray-100">
                                    <i class="fas fa-inbox text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Belum Ada Data</h3>
                                <p class="text-xs text-gray-400 mt-1">Belum ada kampanye donasi. Silakan tambah baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
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

    async function showValidatorSelection(campaignId, campaignTitle, hasValidator = false, validatorPhone = '', validatorName = '', validatorLink = '') {
        const validators = {!! json_encode($validators->keyBy('id')) !!};

        if (hasValidator) {
            // Opsi cepat jika validator sudah dipilih sebelumnya
            const { value: action } = await Swal.fire({
                title: 'Validator Terdaftar',
                html: `
                    <div class="text-left bg-blue-50 p-4 rounded-xl border border-blue-100 text-xs text-blue-800 space-y-2 mb-4">
                        <p class="font-bold flex items-center gap-1.5"><i class="fas fa-user-shield"></i> Validator: <strong>${validatorName || 'Validator Lapangan'}</strong></p>
                        <p class="font-bold flex items-center gap-1.5"><i class="fab fa-whatsapp"></i> HP/WA: <strong>${validatorPhone || '-'}</strong></p>
                    </div>
                    <p class="text-xs text-gray-500 text-left">Pilih tindakan yang ingin Anda lakukan:</p>
                `,
                icon: 'info',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Kirim ke WhatsApp',
                denyButtonText: 'Ganti Validator',
                cancelButtonText: 'Salin Link',
                confirmButtonColor: '#25D366',
                denyButtonColor: '#3b82f6',
            });

            if (action === true) {
                // Langsung bagikan ke WA
                shareDistributionLink(validatorLink, campaignTitle, validatorName, validatorPhone);
                return;
            } else if (action === false) {
                // Melanjutkan ke form dropdown untuk mengganti validator
            } else {
                // User mengklik Cancel/Salin Link
                if (validatorLink) {
                    copyToClipboard(validatorLink);
                }
                return;
            }
        }
        
        // Alur pemilihan validator standar
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
            confirmButtonText: 'Generate & Bagikan',
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
                            <p class="text-[10px] text-orange-500 font-bold uppercase italic mt-0.5 flex items-center gap-1">
                                <i class="fas fa-user-clock text-[9px]"></i> Menunggu Verifikasi: ${data.validator_name}
                            </p>
                        `;
                    }

                    // Tampilkan sukses lalu ajak bagikan ke WA secara otomatis
                    Swal.fire({
                        title: 'Berhasil Terpilih!',
                        text: `Validator ${data.validator_name} berhasil ditugaskan.`,
                        icon: 'success',
                        confirmButtonText: 'Kirim ke WhatsApp Sekarang',
                        confirmButtonColor: '#25D366'
                    }).then(() => {
                        shareDistributionLink(data.link, campaignTitle, data.validator_name, data.validator_phone);
                    });
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