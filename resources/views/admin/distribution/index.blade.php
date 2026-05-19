@extends('admin.layouts.master')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="mb-8 mt-6">
        <h1 class="text-2xl font-bold text-gray-900">Verifikasi Laporan Penyaluran</h1>
        <p class="mt-1 text-sm text-gray-500">Tinjau dan verifikasi laporan penyaluran dana yang diajukan oleh validator.</p>
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
                        <th class="px-6 py-4">Kampanye</th>
                        <th class="px-6 py-4 text-right">Jumlah Disalurkan</th>
                        <th class="px-6 py-4">Tanggal Laporan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        {{-- Kampanye Info with Image --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-gray-100 overflow-hidden border border-gray-200">
                                    @if($report->campaign->image) 
                                        <img class="h-10 w-10 object-cover" src="{{ $report->campaign->image }}" alt=""> 
                                    @else 
                                        <div class="h-full w-full flex items-center justify-center text-gray-400 bg-gray-50">
                                            <i class="fas fa-image"></i>
                                        </div> 
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900 line-clamp-2 max-w-md">{{ $report->campaign->title }}</div>
                                    @if($report->recipient)
                                        <p class="text-[10px] text-emerald-600 font-bold mt-1 flex items-center gap-1">
                                            <i class="fas fa-user-tag text-[9px]"></i> Penerima: {{ $report->recipient }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        {{-- Jumlah Disalurkan --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-black text-blue-600">
                                Rp {{ number_format($report->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        
                        {{-- Tanggal Laporan --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-800 block">{{ $report->created_at->format('d M Y') }}</span>
                            <span class="text-xs text-gray-400 block">{{ $report->created_at->format('H:i') }} WIB</span>
                        </td>
                        
                        {{-- Status Badge --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border
                                @if($report->status == 'verified') text-emerald-700 bg-emerald-50 border-emerald-200
                                @elseif($report->status == 'rejected') text-red-700 bg-red-50 border-red-200
                                @else text-amber-700 bg-amber-50 border-amber-200 @endif">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        
                        {{-- Aksi Button --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('admin.distribution.show', $report->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-md shadow-blue-100 hover:shadow-lg transition-all duration-200 whitespace-nowrap">
                                <i class="fas fa-eye text-xs"></i>
                                <span>Periksa</span>
                            </a>
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
                                <p class="text-xs text-gray-400 mt-1">Belum ada laporan penyaluran untuk diverifikasi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
