<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Transaksi - DonGiv</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-gradient-to-br from-blue-50 to-cyan-50 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow-sm py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/dongiv-logo.png') }}" alt="DonGiv Logo" class="h-8">
                    <span class="text-xl font-bold text-primary">DonGiv</span>
                </div>
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary font-medium transition">
                    <i class="fas fa-home mr-1"></i>Beranda
                </a>
            </div>
        </div>
    </header>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Riwayat Transaksi</h1>
                <p class="text-gray-600">Lihat semua transaksi donasi Anda</p>
            </div>
			<!-- Search and Filter -->
            <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
               
                <
				
				
				
				
				
				
            </div>
		

            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                @if($transactions->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200/50">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">Tanggal</th>
                                    <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">Nomor Order</th>
                                    <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">Nominal</th>
                                    <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">Metode Pembayaran</th>
                                    <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">Status</th>
                                <th class="py-4 px-6 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">Bukti Transfer</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200/30">
                                @foreach($transactions as $transaction)
                                    <tr class="hover:bg-gray-50/50 transition-all duration-200 border-b border-gray-100/50">
                                        <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                                <span>{{ $transaction->created_at->format('d M Y H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <div class="flex items-center">
                                                <i class="fas fa-hashtag mr-2 text-gray-500"></i>
                                                <span class="font-mono">{{ $transaction->order_id }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <div class="flex items-center">
                                                <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>
                                                <span class="font-bold text-green-600">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm">
                                            <div class="flex items-center">
                                                @if($transaction->payment_method == 'bank_transfer')
                                                    <div class="flex items-center px-3 py-1 rounded-full bg-blue-100/80 text-blue-800 text-xs font-medium">
                                                        <i class="fas fa-university mr-2"></i> Bank Transfer
                                                    </div>
                                                @elseif($transaction->payment_method == 'e_wallet')
                                                    <div class="flex items-center px-3 py-1 rounded-full bg-green-100/80 text-green-800 text-xs font-medium">
                                                        <i class="fab fa-google-pay mr-2"></i> e-Wallet
                                                    </div>
                                                @elseif($transaction->payment_method == 'qris')
                                                    <div class="flex items-center px-3 py-1 rounded-full bg-purple-100/80 text-purple-800 text-xs font-medium">
                                                        <i class="fas fa-qrcode mr-2"></i> QRIS
                                                    </div>
                                                @else
                                                    <div class="flex items-center px-3 py-1 rounded-full bg-gray-100/80 text-gray-800 text-xs font-medium">
                                                        <i class="fas fa-wallet mr-2"></i> {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm">
                                            @if($transaction->status === 'VERIFIED')
                                                <div class="flex items-center px-3 py-1 rounded-full bg-green-100/80 text-green-800 text-xs font-medium">
                                                    <i class="fas fa-check-circle mr-2"></i> Paid
                                                </div>
                                            @elseif($transaction->status === 'PENDING_VERIFICATION')
                                                <div class="flex items-center px-3 py-1 rounded-full bg-yellow-100/80 text-yellow-800 text-xs font-medium">
                                                    <i class="fas fa-clock mr-2"></i> Waiting
                                                </div>
                                            @elseif($transaction->status === 'AWAITING_TRANSFER')
                                                <div class="flex items-center px-3 py-1 rounded-full bg-blue-100/80 text-blue-800 text-xs font-medium">
                                                    <i class="fas fa-hourglass-half mr-2"></i> Pending
                                                </div>
                                            @else
                                                <div class="flex items-center px-3 py-1 rounded-full bg-red-100/80 text-red-800 text-xs font-medium">
                                                    <i class="fas fa-times-circle mr-2"></i> Rejected
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-right text-sm font-medium">
                                            @if($transaction->proof_of_transfer_path)
                                                <a href="{{ asset('storage/' . $transaction->proof_of_transfer_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-blue-300 text-blue-700 rounded-md hover:bg-blue-50 transition-colors">
                                                    <i class="fas fa-image mr-1"></i> Lihat Bukti
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-receipt text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Belum ada transaksi</h3>
                        <p class="text-gray-500 mb-6">Anda belum memiliki riwayat transaksi donasi</p>
                        <a href="{{ route('home') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-list mr-2"></i>Lihat Semua Kampanye
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center md:text-left">
                    <h4 class="text-xl font-bold text-white mb-4">DonGiv</h4>
                    <p class="text-sm">Creating positive change through transparent and effective charitable giving.</p>
                </div>
                <div class="text-center md:text-left">
                    <h5 class="font-semibold text-white mb-4">Explore</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('donation.details') }}" class="hover:text-white transition">Donations</a></li>
                        <li><a href="#" class="hover:text-white transition">Volunteer</a></li>
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                    </ul>
                </div>
                <div class="text-center md:text-left">
                    <h5 class="font-semibold text-white mb-4">Legal</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition">Charity Registration</a></li>
                    </ul>
                </div>
                <div class="text-center md:text-left">
                    <h5 class="font-semibold text-white mb-4">Contact Us</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Support Center</a></li>
                        <li><a href="#" class="hover:text-white transition">Partnership Inquiry</a></li>
                        <li><a href="#" class="hover:text-white transition">Media Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm">
                <p>&copy; {{ date('Y') }} DonGiv — Making a Difference Together ❤️</p>
            </div>
        </div>
    </footer>
</body>
</html>