@extends('admin.layouts.master')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center my-6">
        <h2 class="text-2xl font-semibold text-gray-700">Verifikasi Laporan Penyaluran</h2>
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
                        <th class="px-4 py-3">Kampanye</th>
                        <th class="px-4 py-3">Jumlah Disalurkan</th>
                        <th class="px-4 py-3">Tanggal Laporan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($reports as $report)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-sm">
                            <p class="font-semibold">{{ $report->campaign->title }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600">
                            Rp {{ number_format($report->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $report->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full
                                @if($report->status == 'verified') text-green-700 bg-green-100
                                @elseif($report->status == 'rejected') text-red-700 bg-red-100
                                @else text-yellow-700 bg-yellow-100 @endif">
                                {{ $report->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.distribution.show', $report->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs hover:bg-blue-700 transition">
                                <i class="fas fa-eye mr-1"></i> Periksa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Belum ada laporan penyaluran untuk diverifikasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
