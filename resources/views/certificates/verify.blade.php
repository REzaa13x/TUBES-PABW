<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat — Dongiv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50/50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white rounded-[2rem] border border-slate-100 shadow-2xl shadow-blue-500/5 overflow-hidden">
        
        {{-- Header Status --}}
        <div class="p-8 text-center border-b border-slate-50 relative">
            @if($isValid)
                <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-5 shadow-inner border border-green-100">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Sertifikat Valid</h1>
                <p class="text-slate-400 text-sm mt-2 max-w-sm mx-auto">Sertifikat penghargaan digital ini dikeluarkan resmi oleh Dongiv dan terverifikasi secara publik.</p>
            @else
                <div class="w-20 h-20 {{ $certificate ? 'bg-amber-50 text-amber-500 border-amber-100' : 'bg-red-50 text-red-500 border-red-100' }} rounded-full flex items-center justify-center text-4xl mx-auto mb-5 shadow-inner border">
                    <i class="fas {{ $certificate ? 'fa-exclamation-circle' : 'fa-times-circle' }}"></i>
                </div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">
                    {{ $certificate ? 'Sertifikat Ditangguhkan' : 'Sertifikat Tidak Ditemukan' }}
                </h1>
                <p class="text-slate-400 text-sm mt-2 max-w-sm mx-auto">
                    @if($certificate)
                        Sertifikat ini berstatus <strong class="text-slate-700">{{ $certificate->status_label }}</strong> dan saat ini tidak aktif dalam sistem verifikasi.
                    @else
                        Nomor sertifikat yang Anda masukkan tidak valid atau tidak terdaftar dalam database kami.
                    @endif
                </p>
            @endif
        </div>

        @if($certificate)
            {{-- Certificate Details --}}
            <div class="p-8">
                <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4 text-center mb-6">
                    <span class="text-[10px] text-blue-600 uppercase tracking-widest font-extrabold block mb-1">Nomor Sertifikat</span>
                    <span class="font-mono text-xl font-black text-blue-700 tracking-wider">{{ $certificate->certificate_number }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50/50 border border-slate-100 rounded-xl">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block mb-1">Nama Relawan</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $certificate->volunteer_name }}</span>
                    </div>
                    <div class="p-4 bg-slate-50/50 border border-slate-100 rounded-xl">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block mb-1">Nama Kampanye</span>
                        <span class="font-bold text-slate-800 text-sm line-clamp-1" title="{{ $certificate->campaign_name }}">{{ $certificate->campaign_name }}</span>
                    </div>
                    <div class="p-4 bg-slate-50/50 border border-slate-100 rounded-xl">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block mb-1">Kategori</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $certificate->category ?? '-' }}</span>
                    </div>
                    <div class="p-4 bg-slate-50/50 border border-slate-100 rounded-xl">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block mb-1">Tanggal Rilis</span>
                        <span class="font-bold text-slate-800 text-sm">{{ optional($certificate->issue_date)->format('d M Y') ?? '-' }}</span>
                    </div>
                    <div class="p-4 bg-slate-50/50 border border-slate-100 rounded-xl">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block mb-1">Tingkat Relawan</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $certificate->volunteer_level }}</span>
                    </div>
                    <div class="p-4 bg-slate-50/50 border border-slate-100 rounded-xl">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block mb-1">Total Jam Relawan</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $certificate->total_volunteer_hours }} Jam Kerja</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- Footer --}}
        <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-lg shadow-md">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <div>
                    <h4 class="font-black text-slate-800 text-sm tracking-tight leading-none">Dongiv</h4>
                    <span class="text-[10px] text-slate-400 font-medium">Social Impact Platform</span>
                </div>
            </div>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-blue-500/10">
                <i class="fas fa-home"></i> Ke Beranda
            </a>
        </div>

    </div>
</body>
</html>
