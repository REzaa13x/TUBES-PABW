@extends('admin.layouts.master')

@section('title', 'Verifikasi Donasi')

@section('content')
<div class="min-h-screen pb-20">
    <!-- HEADER & STATS -->
    <div class="mb-10 animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i class="fas fa-hand-holding-heart text-xl"></i>
                    </div>
                    Verifikasi Donasi
                </h2>
                <p class="text-slate-500 mt-2 font-medium">Manajemen transparansi dan validasi kontribusi donatur.</p>
            </div>
            
            <div class="flex items-center gap-3 no-print">
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                    <input type="text" id="searchInput" 
                           class="pl-12 pr-6 py-4 rounded-2xl border-none bg-white shadow-sm focus:ring-4 focus:ring-blue-500/10 w-full md:w-80 font-semibold text-sm outline-none transition-all" 
                           placeholder="Cari Order ID atau Nama...">
                </div>
            </div>
        </div>

        <!-- QUICK STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 hover:scale-[1.02] transition-all duration-300">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Transaksi</p>
                    <h4 class="text-2xl font-black text-slate-900">{{ number_format($transactions->count()) }}</h4>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 hover:scale-[1.02] transition-all duration-300">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Butuh Verifikasi</p>
                    <h4 class="text-2xl font-black text-slate-900">{{ number_format($transactions->where('status', 'PENDING_VERIFICATION')->count()) }}</h4>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 hover:scale-[1.02] transition-all duration-300">
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-check-double"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Donasi Berhasil</p>
                    <h4 class="text-2xl font-black text-slate-900">{{ number_format($transactions->where('status', 'VERIFIED')->count()) }}</h4>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="mb-8 bg-green-600 text-white px-6 py-4 rounded-2xl shadow-lg shadow-green-200 flex items-center justify-between animate-bounce">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <span class="font-bold text-sm uppercase tracking-wide">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-white/50 hover:text-white"><i class="fas fa-times"></i></button>
    </div>
    @endif

    <!-- MAIN TABLE CARD -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Order ID & Tanggal</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Donatur</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kampanye / Nominal</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Metode & Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($transactions as $transaction)
                    <tr class="hover:bg-slate-50/80 transition-all duration-200 group">
                        <td class="px-8 py-6">
                            <div class="text-sm font-black text-slate-900 mb-1 group-hover:text-blue-600 transition-colors">#{{ $transaction->order_id }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ $transaction->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs border border-white shadow-sm">
                                    {{ substr($transaction->donor_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $transaction->donor_name }}</div>
                                    <div class="text-xs text-slate-400 font-medium">{{ $transaction->donor_email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-xs font-bold text-blue-600 mb-1">
                                {{ $transaction->campaign ? Str::limit($transaction->campaign->title, 30) : 'Donasi Umum' }}
                            </div>
                            <div class="text-lg font-black text-slate-900 tracking-tighter">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex flex-col items-center gap-1.5 justify-center">
                                <div class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-tighter border border-slate-200">
                                    {{ str_replace('_', ' ', $transaction->payment_method) }}
                                </div>
                                
                                @if($transaction->status === 'VERIFIED')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-700 border border-green-200">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </span>
                                @elseif($transaction->status === 'PENDING_VERIFICATION')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-700 border border-amber-200 animate-pulse">
                                        <i class="fas fa-clock"></i> Waiting
                                    </span>
                                @elseif($transaction->status === 'AWAITING_TRANSFER')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-blue-100 text-blue-700 border border-blue-200">
                                        <i class="fas fa-hourglass-half"></i> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-100 text-red-700 border border-red-200">
                                        <i class="fas fa-times-circle"></i> Rejected
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end">
                                <button onclick="openVerifyModal(this)" 
                                        data-transaction="{{ json_encode([
                                            'order_id' => $transaction->order_id,
                                            'donor_name' => $transaction->donor_name,
                                            'donor_email' => $transaction->donor_email,
                                            'campaign_title' => $transaction->campaign ? $transaction->campaign->title : 'Donasi Umum',
                                            'amount' => number_format($transaction->amount, 0, ',', '.'),
                                            'payment_method' => str_replace('_', ' ', $transaction->payment_method),
                                            'status' => $transaction->status,
                                            'created_at' => $transaction->created_at->format('d M Y, H:i'),
                                            'proof_url' => $transaction->proof_of_transfer_path,
                                            'update_url' => route('admin.donations.updateStatus', $transaction->order_id),
                                            'invoice_url' => route('admin.donations.invoice', $transaction->order_id),
                                        ]) }}"
                                        class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider transition-all duration-300 shadow-md shadow-blue-100 hover:shadow-lg hover:-translate-y-0.5">
                                    <i class="fas fa-eye text-xs"></i>
                                    <span>Tinjau</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 text-4xl mb-4">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h5 class="text-slate-900 font-black uppercase tracking-widest">Tidak Ada Transaksi</h5>
                                <p class="text-slate-400 text-sm mt-1">Data donasi akan muncul di sini setelah ada transaksi masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL VERIFIKASI DONASI PREMIUM -->
<div id="verifyDonationModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-all duration-300 flex items-center justify-center p-4">
    <div class="relative mx-auto border-0 w-full max-w-4xl shadow-2xl rounded-[2.5rem] bg-white overflow-hidden animate-in fade-in zoom-in duration-200">
        
        <!-- Header Modal -->
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Pemeriksaan Transaksi Donasi</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5" id="modalOrderId">#DONGIV-ORDERID</p>
                </div>
            </div>
            
            <button onclick="closeVerifyModal()" class="w-10 h-10 rounded-full bg-white hover:bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 transition-all shadow-xs">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Info Section -->
                <div class="space-y-6">
                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Donatur</p>
                            <p class="text-sm font-black text-slate-800" id="modalDonorName">-</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email Donatur</p>
                            <p class="text-sm font-bold text-slate-500" id="modalDonorEmail">-</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kampanye Tujuan</p>
                            <p class="text-sm font-black text-blue-600 line-clamp-2 leading-snug" id="modalCampaignTitle">-</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-5 bg-blue-50/50 rounded-3xl border border-blue-100/60">
                            <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-1">Nominal Donasi</p>
                            <p class="text-xl font-black text-slate-900 tracking-tighter" id="modalAmount">Rp 0</p>
                        </div>
                        <div class="p-5 bg-slate-50 rounded-3xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Metode & Status</p>
                            <div class="flex flex-col gap-1.5 mt-1 items-start">
                                <span class="px-2.5 py-0.5 rounded-lg bg-slate-200 text-slate-700 text-[9px] font-black uppercase tracking-wider" id="modalPaymentMethod">-</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase" id="modalStatusBadge">-</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal Transaksi</p>
                        <p class="text-xs font-bold text-slate-500" id="modalCreatedAt">-</p>
                    </div>
                </div>

                <!-- Proof of Payment Image Section -->
                <div class="space-y-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Bukti Pembayaran</p>
                    <div id="modalProofContainer" class="rounded-[2rem] overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center p-3 min-h-[250px] relative group">
                        <img id="modalProofImage" src="" class="max-w-full max-h-[240px] rounded-2xl object-contain shadow-xs transition-transform duration-500 group-hover:scale-105" alt="Bukti Transfer">
                        <div id="modalNoProof" class="hidden text-center p-8 space-y-3">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-300 mx-auto shadow-xs border border-slate-100">
                                <i class="fas fa-file-invoice text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500">Bukti Transfer Belum Tersedia</p>
                                <p class="text-[10px] text-slate-400 mt-1">Donatur belum mengunggah bukti pembayaran fisik.</p>
                            </div>
                        </div>
                    </div>
                    <a id="modalFullImageLink" href="#" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline pl-1">
                        <i class="fas fa-external-link-alt text-[10px]"></i> Buka Gambar Ukuran Penuh
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Action Buttons -->
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex flex-wrap gap-3 justify-between items-center">
            <!-- Invoice Link -->
            <a id="modalInvoiceLink" href="#" target="_blank" class="px-5 py-3.5 bg-white hover:bg-slate-100 text-slate-600 border border-slate-200 rounded-2xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 shadow-xs">
                <i class="fas fa-file-invoice"></i> Buka Invoice
            </a>

            <!-- Action Forms -->
            <div id="modalVerificationForm" class="flex gap-2">
                <!-- Reject Form -->
                <form id="modalRejectForm" action="" method="POST" class="inline">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="CANCELLED">
                    <button type="submit" class="px-6 py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider transition-all shadow-lg shadow-red-200 flex items-center gap-2">
                        <i class="fas fa-times-circle"></i> Tolak Donasi
                    </button>
                </form>

                <!-- Verify Form -->
                <form id="modalVerifyForm" action="" method="POST" class="inline">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="VERIFIED">
                    <button type="submit" class="px-6 py-3.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider transition-all shadow-lg shadow-green-200 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Verifikasi & Terima
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    // Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Verification Modal Controllers
    function openVerifyModal(button) {
        const data = JSON.parse(button.getAttribute('data-transaction'));
        
        document.getElementById('modalOrderId').textContent = '#' + data.order_id;
        document.getElementById('modalDonorName').textContent = data.donor_name;
        document.getElementById('modalDonorEmail').textContent = data.donor_email;
        document.getElementById('modalCampaignTitle').textContent = data.campaign_title;
        document.getElementById('modalAmount').textContent = 'Rp ' + data.amount;
        document.getElementById('modalPaymentMethod').textContent = data.payment_method;
        document.getElementById('modalCreatedAt').textContent = data.created_at;

        // Image container and trigger
        const img = document.getElementById('modalProofImage');
        const noProof = document.getElementById('modalNoProof');
        const fullLink = document.getElementById('modalFullImageLink');
        
        if (data.proof_url) {
            img.src = data.proof_url;
            img.classList.remove('hidden');
            noProof.classList.add('hidden');
            fullLink.href = data.proof_url;
            fullLink.classList.remove('hidden');
        } else {
            img.classList.add('hidden');
            noProof.classList.remove('hidden');
            fullLink.classList.add('hidden');
        }

        // Action routing updates
        document.getElementById('modalInvoiceLink').href = data.invoice_url;
        document.getElementById('modalRejectForm').action = data.update_url;
        document.getElementById('modalVerifyForm').action = data.update_url;

        // Form confirmations trigger
        document.getElementById('modalRejectForm').onsubmit = function() {
            return confirm(`Yakin ingin menolak pembayaran #${data.order_id} dari ${data.donor_name}?`);
        };
        document.getElementById('modalVerifyForm').onsubmit = function() {
            return confirm(`Yakin ingin memverifikasi pembayaran #${data.order_id} sebesar Rp ${data.amount} sebagai lunas?`);
        };

        // Status dynamic pill update
        const badge = document.getElementById('modalStatusBadge');
        badge.textContent = data.status;
        badge.className = "px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase border ";
        if (data.status === 'VERIFIED') {
            badge.classList.add('bg-green-100', 'text-green-700', 'border-green-200');
        } else if (data.status === 'PENDING_VERIFICATION') {
            badge.classList.add('bg-amber-100', 'text-amber-700', 'border-amber-200');
        } else if (data.status === 'AWAITING_TRANSFER') {
            badge.classList.add('bg-blue-100', 'text-blue-700', 'border-blue-200');
        } else {
            badge.classList.add('bg-red-100', 'text-red-700', 'border-red-200');
        }

        // Display the modal
        const modal = document.getElementById('verifyDonationModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // prevent background scrolling
    }

    function closeVerifyModal() {
        const modal = document.getElementById('verifyDonationModal');
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // restore background scrolling
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeVerifyModal();
        }
    });

    // Close on click outside modal content container
    document.getElementById('verifyDonationModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeVerifyModal();
        }
    });
</script>
@endsection