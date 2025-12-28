@extends('admin.layouts.master')

@section('title', 'Verifikasi Donasi')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2"><i class="fas fa-hand-holding-usd mr-3 text-blue-600"></i>Verifikasi Donasi</h2>
        <p class="text-gray-600">Kelola dan verifikasi bukti transfer donasi dari para donatur</p>
    </div>
</div>

@if (session('success'))
    <div class="alert mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
        <div class="flex items-start">
            <div class="flex-shrink-0 mr-3">
                <i class="fas fa-check-circle text-xl mt-1"></i>
            </div>
            <div>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
    <div class="py-4 px-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex justify-between items-center">
            <h5 class="m-0 font-bold text-lg text-white">Daftar Transaksi Donasi</h5>
            <div class="relative">
                <input type="text" id="searchInput" class="pl-10 pr-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 w-full md:w-64" placeholder="Cari transaksi...">
                <i class="fas fa-search absolute left-3 top-3 text-white/80"></i>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Donatur</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kampanye</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Transfer</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($transactions as $transaction)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $transaction->order_id }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $transaction->donor_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $transaction->donor_email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($transaction->campaign)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $transaction->campaign->title }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Umum
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($transaction->payment_method == 'bank_transfer')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-university mr-1"></i> Bank Transfer
                            </span>
                        @elseif($transaction->payment_method == 'e_wallet')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-wallet mr-1"></i> e-Wallet
                            </span>
                        @elseif($transaction->payment_method == 'qris')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-qrcode mr-1"></i> QRIS
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-credit-card mr-1"></i> {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($transaction->status === 'VERIFIED')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Paid
                            </span>
                        @elseif($transaction->status === 'PENDING_VERIFICATION')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Waiting
                            </span>
                        @elseif($transaction->status === 'AWAITING_TRANSFER')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-hourglass-half mr-1"></i> Pending
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Rejected
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $transaction->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($transaction->proof_of_transfer_path)
                            <a href="{{ asset('storage/' . $transaction->proof_of_transfer_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700" title="Lihat Bukti Transfer">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="space-y-1">
                            <!-- Invoice button -->
                            <a href="{{ route('admin.donations.invoice', $transaction->order_id) }}" class="inline-flex items-center px-3 py-1 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 block">
                                <i class="fas fa-file-invoice mr-1"></i> Invoice
                            </a>

                            @if($transaction->status === 'PENDING_VERIFICATION')
                                <!-- Verification buttons for Pending Verification -->
                                <div class="space-y-1 mt-2">
                                    <form action="{{ route('admin.donations.updateStatus', $transaction->order_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="VERIFIED">
                                        <button type="submit" class="w-full inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700" onclick="return confirm('Yakin ingin memverifikasi pembayaran ini?')">
                                            <i class="fas fa-check mr-1"></i> Verifikasi
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.donations.updateStatus', $transaction->order_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="CANCELLED">
                                        <button type="submit" class="w-full inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700" onclick="return confirm('Yakin ingin menolak pembayaran ini?')">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="relative mt-2">
                                    <button class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 w-full" type="button" id="statusDropdown{{ $transaction->id }}" onclick="toggleDropdown('{{ $transaction->id }}')">
                                        <i class="fas fa-ellipsis-v mr-1"></i> Ubah Status
                                    </button>
                                    <div id="dropdownMenu{{ $transaction->id }}" class="hidden absolute right-0 mt-1 w-48 bg-white shadow-lg rounded-md py-1 z-10">
                                        <form action="{{ route('admin.donations.updateStatus', $transaction->order_id) }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="VERIFIED">
                                            <button type="submit" class="w-full text-left" onclick="return confirm('Yakin ingin mengubah status menjadi Paid?')">Paid</button>
                                        </form>
                                        <form action="{{ route('admin.donations.updateStatus', $transaction->order_id) }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="CANCELLED">
                                            <button type="submit" class="w-full text-left" onclick="return confirm('Yakin ingin menolak pembayaran ini?')">Rejected</button>
                                        </form>
                                        <form action="{{ route('admin.donations.updateStatus', $transaction->order_id) }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="PENDING_VERIFICATION">
                                            <button type="submit" class="w-full text-left" onclick="return confirm('Yakin ingin mengubah status menjadi Waiting?')">Waiting</button>
                                        </form>
                                        <form action="{{ route('admin.donations.updateStatus', $transaction->order_id) }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="AWAITING_TRANSFER">
                                            <button type="submit" class="w-full text-left" onclick="return confirm('Yakin ingin mengubah status menjadi Pending?')">Pending</button>
                                        </form>

                                        <!-- Delete button -->
                                        <form action="{{ route('admin.donations.destroy', $transaction->order_id) }}" method="POST" class="block px-4 py-2 text-sm text-red-700 hover:bg-red-50 w-full text-left border-t border-gray-100 mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left text-red-700 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus transaksi ini? Data yang dihapus tidak dapat dikembalikan.')">
                                                <i class="fas fa-trash mr-2 text-red-600"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-receipt text-5xl text-gray-300 mb-4"></i>
                            <h5 class="text-gray-500 text-lg font-medium mb-2">Belum Ada Transaksi Donasi</h5>
                            <p class="text-gray-400 max-w-md">Transaksi donasi yang masuk akan ditampilkan di sini untuk diverifikasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

<script>
    // Simple search functionality
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

    // Toggle dropdown functionality
    function toggleDropdown(transactionId) {
        const dropdown = document.getElementById('dropdownMenu' + transactionId);
        const allDropdowns = document.querySelectorAll('[id^="dropdownMenu"]');

        // Close all other dropdowns
        allDropdowns.forEach(dropdown => {
            dropdown.classList.add('hidden');
        });

        // Toggle the clicked dropdown
        dropdown.classList.toggle('hidden');
    }

    // Close dropdowns when clicking elsewhere
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[id^="statusDropdown"]') && !event.target.closest('[id^="dropdownMenu"]')) {
            const allDropdowns = document.querySelectorAll('[id^="dropdownMenu"]');
            allDropdowns.forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
</script>