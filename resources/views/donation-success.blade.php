<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donasi Berhasil - DonGiv</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-gradient-to-br from-blue-50 to-cyan-50 text-gray-800 min-h-screen">
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

    <!-- Main Content -->
    <main class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Terima Kasih!</h1>
                <p class="text-lg text-gray-600 mb-6">
                    Donasi Anda telah berhasil diproses. Kami sangat menghargai kontribusi Anda untuk membantu mewujudkan perubahan positif di masyarakat.
                </p>

                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <p class="text-blue-800 font-medium">Status Pembayaran: <span class="text-green-600">Berhasil</span></p>
                    @if(request()->query('order_id'))
                    <p class="text-blue-800 text-sm mt-1">ID Transaksi: {{ request()->query('order_id') }}</p>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('home') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    @auth
                    <a href="{{ route('profiles.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-history mr-2"></i>Riwayat Donasi
                    </a>
                    @endauth
                </div>

                <!-- Upload Proof Section for Manual Transfers -->
                @auth
                    @php
                        $orderId = request()->route('order_id') ?? request()->query('order_id');
                    @endphp
                    @if($orderId)
                        <?php
                        $transaction = \App\Models\DonationTransaction::where('order_id', $orderId)->where('user_id', auth()->id())->first();
                        ?>
                        @if($transaction)
                            @if($transaction->status === 'AWAITING_TRANSFER')
                            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                                <h3 class="text-lg font-bold text-yellow-800 mb-4 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Upload Bukti Transfer
                                </h3>
                                <p class="text-yellow-700 mb-4">
                                    Pembayaran Anda masih menunggu konfirmasi. Silakan upload bukti transfer untuk menyelesaikan proses donasi.
                                </p>

                                <form action="{{ route('donation.upload.proof', $transaction->order_id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4 items-center">
                                    @csrf
                                    <div class="flex-1 w-full">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Bukti Transfer</label>
                                        <input type="file" name="proof" accept="image/*" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <button type="submit" class="mt-2 sm:mt-0 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg whitespace-nowrap">
                                        <i class="fas fa-upload mr-2"></i>Upload Bukti
                                    </button>
                                </form>
                            </div>
                            @elseif($transaction->proof_of_transfer_path)
                            <!-- Show uploaded proof if exists -->
                            <div class="mt-8 bg-green-50 border border-green-200 rounded-xl p-6">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                    <div>
                                        <p class="font-medium text-green-800">Bukti Transfer Telah Diupload</p>
                                        @if($transaction->status === 'VERIFIED')
                                            <p class="text-sm text-green-600 flex items-center"><i class="fas fa-badge-check mr-1 text-green-500"></i> Pembayaran Berhasil - Ditambahkan ke Riwayat Transaksi</p>
                                        @elseif($transaction->status === 'PENDING_VERIFICATION')
                                            <p class="text-sm text-amber-600 flex items-center"><i class="fas fa-clock mr-1 text-amber-500"></i> Menunggu Verifikasi Admin - Ditambahkan ke Riwayat Transaksi</p>
                                        @else
                                            <p class="text-sm text-blue-600 flex items-center"><i class="fas fa-history mr-1 text-blue-500"></i> Ditambahkan ke Riwayat Transaksi</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Pratinjau Bukti Transfer:</p>
                                    <div class="flex justify-center">
                                        <img src="{{ asset('storage/' . $transaction->proof_of_transfer_path) }}"
                                             alt="Bukti Transfer"
                                             class="max-w-xs max-h-48 rounded-lg border shadow-sm">
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif
                    @endif
                @endauth
            </div>

            <!-- Additional Info -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 text-center shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-blue-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Aman & Terpercaya</h3>
                    <p class="text-sm text-gray-600">Donasi Anda digunakan sesuai dengan tujuan</p>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-green-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Transparan</h3>
                    <p class="text-sm text-gray-600">Laporan penggunaan dana tersedia untuk Anda</p>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow">
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding-heart text-amber-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Dampak Nyata</h3>
                    <p class="text-sm text-gray-600">Donasi Anda langsung membantu masyarakat</p>
                </div>
            </div>
        </div>
    </main>

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