@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
{{-- Header Section --}}
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h2>
        <p class="text-slate-500 text-sm mt-1">Pantau performa donasi dan relawan secara real-time.</p>
    </div>

    {{-- Date Filter (Mockup) --}}
    <div class="flex bg-white rounded-lg p-1 shadow-sm border border-slate-200">
        <button class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-md transition-colors">Minggu Ini</button>
        <button class="px-3 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 rounded-md shadow-sm transition-colors">Bulan Ini</button>
        <button class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-md transition-colors">Tahun Ini</button>
    </div>
</div>

{{-- STATS CARDS (Modern & Clean) --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- Card 1: Total Donasi --}}
    <div class="bg-white p-6 rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:-translate-y-1 transition-all duration-300 border border-slate-100">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fas fa-wallet text-lg"></i>
            </div>
            <span class="flex items-center text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                <i class="fas fa-arrow-up mr-1"></i> 12.5%
            </span>
        </div>
        <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Donasi</h3>
        <p class="text-2xl font-extrabold text-slate-800">Rp 42.500.000</p>
        <div class="mt-4 w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
            <div class="bg-blue-500 h-1.5 rounded-full" style="width: 75%"></div>
        </div>
        <p class="text-[10px] text-slate-400 mt-2 text-right">75% dari target bulan ini</p>
    </div>

    {{-- Card 2: Kampanye Aktif --}}
    <div class="bg-white p-6 rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:-translate-y-1 transition-all duration-300 border border-slate-100">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i class="fas fa-bullhorn text-lg"></i>
            </div>
        </div>
        <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Kampanye Aktif</h3>
        <p class="text-2xl font-extrabold text-slate-800">18 <span class="text-sm font-medium text-slate-400">/ 24</span></p>
        <div class="flex items-center gap-2 mt-4 text-xs text-slate-500">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> 6 Selesai minggu ini
        </div>
    </div>

    {{-- Card 3: Relawan --}}
    <div class="bg-white p-6 rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:-translate-y-1 transition-all duration-300 border border-slate-100">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                <i class="fas fa-users text-lg"></i>
            </div>
            <span class="flex items-center text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                +8
            </span>
        </div>
        <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Relawan Baru</h3>
        <p class="text-2xl font-extrabold text-slate-800">124</p>
        <p class="text-xs text-slate-400 mt-1">Total Relawan Aktif: 1,205</p>
    </div>

    {{-- Card 4: Menunggu Verifikasi (Actionable) --}}
    <div class="bg-gradient-to-br from-orange-500 to-red-500 p-6 rounded-2xl shadow-lg shadow-orange-200 text-white relative overflow-hidden group cursor-pointer hover:shadow-orange-300 transition-all">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>

        <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            <h3 class="text-orange-100 text-xs font-bold uppercase tracking-wider mb-1">Perlu Tindakan</h3>
            <p class="text-2xl font-extrabold text-white">5 Transaksi</p>
            <a href="{{ route('admin.donations.index') }}" class="inline-flex items-center gap-2 mt-4 text-xs font-bold bg-white/20 px-3 py-2 rounded-lg hover:bg-white/30 transition-colors">
                Verifikasi Sekarang <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- CHART & ACTIVITY SECTION --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Main Chart (Donasi) --}}
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-800">Tren Donasi</h3>
            <button class="text-slate-400 hover:text-blue-600 transition-colors"><i class="fas fa-ellipsis-h"></i></button>
        </div>
        <div class="h-80 w-full relative">
            <canvas id="donationsChart"></canvas>
        </div>
    </div>

    {{-- Side: Quick Activity --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-slate-800 mb-6">Aktivitas Terbaru</h3>

        <div class="space-y-6 relative">
            {{-- Garis Vertikal --}}
            <div class="absolute left-3.5 top-2 bottom-2 w-0.5 bg-slate-100"></div>

            {{-- Activity Item 1 --}}
            <div class="flex gap-4 relative">
                <div class="w-8 h-8 rounded-full bg-green-100 border-2 border-white shadow-sm flex items-center justify-center shrink-0 z-10">
                    <i class="fas fa-check text-green-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Donasi #INV-001 Diverifikasi</p>
                    <p class="text-xs text-slate-500 mt-0.5">Oleh Admin Budi • 5 menit lalu</p>
                </div>
            </div>

            {{-- Activity Item 2 --}}
            <div class="flex gap-4 relative">
                <div class="w-8 h-8 rounded-full bg-blue-100 border-2 border-white shadow-sm flex items-center justify-center shrink-0 z-10">
                    <i class="fas fa-user-plus text-blue-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Susi Kurnia mendaftar</p>
                    <p class="text-xs text-slate-500 mt-0.5">Sebagai Relawan Pengajar</p>
                </div>
            </div>

            {{-- Activity Item 3 --}}
            <div class="flex gap-4 relative">
                <div class="w-8 h-8 rounded-full bg-purple-100 border-2 border-white shadow-sm flex items-center justify-center shrink-0 z-10">
                    <i class="fas fa-plus text-purple-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Kampanye Baru Dibuat</p>
                    <p class="text-xs text-slate-500 mt-0.5">"Bantu Korban Banjir Demak"</p>
                </div>
            </div>
        </div>

        {{-- Quick Action Button --}}
        <a href="{{ route('admin.notifications.index') }}" class="block w-full text-center mt-8 py-3 text-xs font-bold text-slate-500 hover:text-blue-600 hover:bg-slate-50 rounded-xl transition-all border border-slate-100 hover:border-blue-100">
            Lihat Semua Aktivitas
        </a>
    </div>
</div>

{{-- CHART SCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gradient Fill untuk Chart
        const ctx = document.getElementById('donationsChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)'); // Blue with opacity
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)'); // Transparent

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Donasi (Juta Rp)',
                    data: [12, 19, 15, 22, 18, 25, 20, 28, 24, 30, 27, 32],
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: {
                            size: 13
                        },
                        bodyFont: {
                            size: 12
                        },
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [4, 4],
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#94a3b8'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection