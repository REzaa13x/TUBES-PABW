@extends('admin.layouts.master')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Header & Navigasi --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.withdrawals.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard Keuangan
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Buku Kas: {{ $campaign->title }}</h1>
            <p class="text-gray-500 text-sm mt-1">Laporan transparansi penggunaan dana kampanye.</p>
        </div>
    </div>

    {{-- Kartu Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-xs font-bold text-gray-400 uppercase">Total Dana Masuk</p>
            <p class="text-2xl font-bold text-emerald-600">+ Rp {{ number_format($totalIn, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-xs font-bold text-gray-400 uppercase">Total Dana Keluar</p>
            <p class="text-2xl font-bold text-red-600">- Rp {{ number_format($totalOut, 0, ',', '.') }}</p>
        </div>
        <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100">
            <p class="text-xs font-bold text-blue-400 uppercase">Sisa Saldo Saat Ini</p>
            <p class="text-2xl font-bold text-blue-700">Rp {{ number_format($balance, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Tabel Riwayat Transaksi --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Detail Pengeluaran</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kategori & Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nominal</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Bukti Nota</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Admin Pencatat</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        // Gabungkan dua koleksi: Withdrawals dan DistributionReports
                        $manualLogs = $campaign->withdrawals->map(function($item) {
                            $item->log_type = 'manual';
                            $item->log_date = $item->transferred_at;
                            return $item;
                        });

                        $validatorLogs = $campaign->distributionReports->map(function($item) {
                            $item->log_type = 'validator';
                            $item->log_date = $item->created_at;
                            return $item;
                        });

                        $combinedLogs = $manualLogs->concat($validatorLogs)->sortByDesc('log_date');
                    @endphp

                    @forelse($combinedLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($log->log_date)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($log->log_type == 'manual')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-700 uppercase tracking-widest mb-1">
                                        <i class="fas fa-user-tie mr-1"></i> Manual Admin
                                    </span>
                                    <p class="text-sm text-gray-900 font-bold">{{ $log->account_holder_name }}</p>
                                    <p class="text-[10px] text-gray-500 italic">{{ $log->account_number }}</p>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-700 uppercase tracking-widest mb-1">
                                        <i class="fas fa-user-shield mr-1"></i> Laporan Validator
                                    </span>
                                    <p class="text-sm text-gray-900 font-bold">{{ $log->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-red-600">
                                - Rp {{ number_format($log->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php 
                                    $imagePath = $log->log_type == 'manual' ? $log->proof_file : $log->proof_image; 
                                @endphp
                                @if($imagePath)
                                    <a href="{{ asset('storage/' . $imagePath) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-bold underline flex justify-center items-center gap-1">
                                        <i class="fas fa-image"></i>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-gray-400 text-[10px] font-bold uppercase">No Proof</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-500">
                                @if($log->log_type == 'manual')
                                    {{ $log->user ? $log->user->name : 'Admin' }}
                                @else
                                    {{ $campaign->validator_name ?: 'Validator Lapangan' }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center opacity-30">
                                    <i class="fas fa-receipt text-5xl mb-4"></i>
                                    <p class="font-bold text-sm">Belum ada riwayat pengeluaran dana.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection