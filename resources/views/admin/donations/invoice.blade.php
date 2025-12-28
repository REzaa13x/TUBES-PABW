<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice Verifikasi Donasi - DonGiv Admin</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 text-gray-800 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/dongiv-logo.png') }}" alt="DonGiv Logo" class="h-8">
                    <span class="text-xl font-bold text-primary">DonGiv Admin</span>
                </div>
                <div class="no-print">
                    <a href="{{ route('admin.donations.index') }}" class="text-gray-700 hover:text-primary font-medium transition mr-4">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Invoice Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Invoice Verifikasi Donasi</h1>
                        <p class="text-gray-600 mt-2">ID Transaksi: {{ $transaction->order_id }}</p>
                        <p class="text-gray-600">Tanggal: {{ $transaction->created_at->format('d F Y H:i') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 bg-blue-50 px-4 py-2 rounded-lg">
                        <span class="font-semibold text-blue-800">Status:</span>
                        <span class="ml-2 px-3 py-1 rounded-full text-sm font-semibold
                            {{ $transaction->status === 'VERIFIED' ? 'bg-green-100 text-green-800' :
                               ($transaction->status === 'PENDING' || $transaction->status === 'AWAITING_TRANSFER' ? 'bg-yellow-100 text-yellow-800' :
                               ($transaction->status === 'PENDING_VERIFICATION' ? 'bg-amber-100 text-amber-800' :
                               'bg-red-100 text-red-800')) }}">
                            {{ $transaction->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Donor Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Informasi Donatur</h3>
                        <div class="space-y-2">
                            <p class="font-medium"><i class="fas fa-user mr-2"></i>{{ $transaction->donor_name }}</p>
                            <p class="text-gray-600"><i class="fas fa-envelope mr-2"></i>{{ $transaction->donor_email }}</p>
                            @if($transaction->donor_phone)
                                <p class="text-gray-600"><i class="fas fa-phone mr-2"></i>{{ $transaction->donor_phone }}</p>
                            @endif
                            <p class="text-gray-600">
                                <i class="fas fa-user-secret mr-2"></i>
                                @if($transaction->anonymous)
                                    Anonim
                                @else
                                    Nama Terlihat
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Detail Donasi</h3>
                        <div class="space-y-2">
                            <p class="font-medium"><i class="fas fa-money-bill-wave mr-2"></i>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                            <p class="text-gray-600"><i class="fas fa-calendar mr-2"></i>{{ $transaction->created_at->format('d F Y H:i') }}</p>
                            <p class="text-gray-600">
                                <i class="fas fa-credit-card mr-2"></i>
                                @if($transaction->payment_method == 'bank_transfer')
                                    Bank Transfer
                                @elseif($transaction->payment_method == 'e_wallet')
                                    e-Wallet
                                @elseif($transaction->payment_method == 'qris')
                                    QRIS
                                @elseif($transaction->payment_method == 'midtrans')
                                    Midtrans
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                @endif
                            </p>
                            @if($transaction->transfer_deadline)
                                <p class="text-gray-600"><i class="fas fa-clock mr-2"></i>Batas Transfer: {{ $transaction->transfer_deadline->format('d F Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Campaign Information -->
                @if($transaction->campaign)
                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 mb-3">Kampanye</h3>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="font-medium text-gray-800"><i class="fas fa-heart mr-2"></i>{{ $transaction->campaign->title }}</p>
                        <p class="text-gray-600 text-sm mt-1">{{ Str::limit($transaction->campaign->description, 150) }}</p>
                    </div>
                </div>
                @endif

                <!-- Bank Information (for manual transfers) -->
                @if(in_array($transaction->payment_method, ['bank_transfer', 'manual_transfer']))
                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 mb-3">Informasi Transfer</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Nama Akun</p>
                            <p class="font-medium">{{ $transaction->bank_account_name }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Nomor Rekening</p>
                            <p class="font-mono font-medium">{{ $transaction->bank_account_number }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Nama Bank</p>
                            <p class="font-medium">{{ $transaction->bank_name }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Transaction Details Table -->
                <div class="overflow-x-auto mb-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    Donasi untuk {{ $transaction->campaign ? $transaction->campaign->title : 'Kampanye Umum' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-900">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Verification Action Buttons -->
                <div class="no-print mt-8 border-t pt-8">
                    <h3 class="font-bold text-gray-800 mb-4">Aksi Verifikasi</h3>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if($transaction->status === 'PENDING_VERIFICATION')
                            <form action="{{ route('admin.donations.updateStatus', $transaction->order_id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="VERIFIED">
                                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg" onclick="return confirm('Yakin ingin memverifikasi pembayaran ini?')">
                                    <i class="fas fa-check mr-2"></i>Verifikasi Donasi
                                </button>
                            </form>

                            <form action="{{ route('admin.donations.updateStatus', $transaction->order_id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="CANCELLED">
                                <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg" onclick="return confirm('Yakin ingin menolak pembayaran ini?')">
                                    <i class="fas fa-times mr-2"></i>Tolak Donasi
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.donations.updateStatus', $transaction->order_id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-2 gap-4">
                                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="AWAITING_TRANSFER" {{ $transaction->status === 'AWAITING_TRANSFER' ? 'selected' : '' }}>Pending</option>
                                        <option value="PENDING_VERIFICATION" {{ $transaction->status === 'PENDING_VERIFICATION' ? 'selected' : '' }}>Waiting</option>
                                        <option value="VERIFIED" {{ $transaction->status === 'VERIFIED' ? 'selected' : '' }}>Paid</option>
                                        <option value="CANCELLED" {{ $transaction->status === 'CANCELLED' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        <i class="fas fa-sync mr-2"></i>Update Status
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="no-print mt-8 flex flex-col sm:flex-row gap-4 justify-end">
                    <button onclick="window.print()" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-print mr-2"></i>Cetak Invoice
                    </button>
                    <a href="{{ route('admin.donations.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4">Catatan Verifikasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                        <h4 class="font-semibold text-gray-800">Verifikasi</h4>
                        <p class="text-sm text-gray-600 mt-1">Periksa keaslian bukti transfer</p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <i class="fas fa-receipt text-blue-600 text-2xl mb-2"></i>
                        <h4 class="font-semibold text-gray-800">Validasi</h4>
                        <p class="text-sm text-gray-600 mt-1">Pastikan jumlah sesuai dengan donasi</p>
                    </div>
                    <div class="text-center p-4 bg-amber-50 rounded-lg">
                        <i class="fas fa-history text-amber-600 text-2xl mb-2"></i>
                        <h4 class="font-semibold text-gray-800">Riwayat</h4>
                        <p class="text-sm text-gray-600 mt-1">Simpan catatan verifikasi</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12 mt-16 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center md:text-left">
                    <h4 class="text-xl font-bold text-white mb-4">DonGiv Admin</h4>
                    <p class="text-sm">Sistem verifikasi donasi untuk manajemen transparansi</p>
                </div>
                <div class="text-center md:text-left">
                    <h5 class="font-semibold text-white mb-4">Admin</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-white transition">Dashboard</a></li>
                        <li><a href="{{ route('admin.donations.index') }}" class="hover:text-white transition">Verifikasi Donasi</a></li>
                        <li><a href="{{ route('admin.campaigns.index') }}" class="hover:text-white transition">Kampanye</a></li>
                        <li><a href="{{ route('admin.profiles.index') }}" class="hover:text-white transition">Pengguna</a></li>
                    </ul>
                </div>
                <div class="text-center md:text-left">
                    <h5 class="font-semibold text-white mb-4">Sistem</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Log Aktivitas</a></li>
                        <li><a href="#" class="hover:text-white transition">Pengaturan</a></li>
                        <li><a href="#" class="hover:text-white transition">Laporan</a></li>
                    </ul>
                </div>
                <div class="text-center md:text-left">
                    <h5 class="font-semibold text-white mb-4">Dukungan</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Bantuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm">
                <p>&copy; {{ date('Y') }} DonGiv Admin — Sistem Verifikasi Donasi</p>
            </div>
        </div>
    </footer>
</body>
</html>