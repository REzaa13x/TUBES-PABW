<x-app title="Daftar Kampanye Donasi - DonasiKita">
    
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight md:text-5xl">
                    Kampanye Donasi Terbaru
                </h1>
                <p class="mt-4 text-xl text-blue-100 max-w-3xl mx-auto">
                    Berikan kebaikan untuk sesama melalui kampanye-kampanye yang penuh harapan
                </p>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="py-8 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('campaigns.all') }}" class="flex-1">
                        <div class="flex rounded-lg border border-slate-300 overflow-hidden">
                            <div class="flex items-center pl-3 bg-slate-50">
                                <i class="fas fa-search text-slate-400"></i>
                            </div>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="flex-1 p-3 text-sm text-slate-900 bg-slate-50 focus:outline-none"
                                placeholder="Cari kampanye...">
                            <button type="submit" class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors">
                                Cari
                            </button>
                        </div>
                    </form>

                    <!-- Category Filter -->
                    <form method="GET" action="{{ route('campaigns.all') }}" class="flex-1" id="categoryForm">
                        <!-- Hidden input untuk search tetap aktif -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="flex rounded-lg border border-slate-300 overflow-hidden">
                            <select name="kategori" onchange="document.getElementById('categoryForm').submit();"
                                class="flex-1 p-3 text-sm text-slate-900 bg-slate-50 focus:outline-none">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriOptions as $kategori)
                                    <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                        {{ $kategori }}
                                    </option>
                                @endforeach
                            </select>

                            @if(request('kategori') || request('search'))
                                <a href="{{ route('campaigns.all') }}"
                                   class="px-4 py-3 bg-red-500 hover:bg-red-600 text-white font-medium transition-colors flex items-center justify-center">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Campaigns List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($campaigns as $campaign)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-1 border border-slate-100">
                        <!-- Campaign Image -->
                        <div class="h-56 overflow-hidden">
                            @if($campaign->image)
                                <img src="{{ $campaign->image }}"
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                    alt="{{ $campaign->title }}">
                            @else
                                <img src="https://placehold.co/600x400?text=Campaign+Image"
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                    alt="{{ $campaign->title }}">
                            @endif
                        </div>

                        <!-- Campaign Content -->
                        <div class="p-6">
                            <!-- Category Badge - Updated to show actual category -->
                            <div class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full mb-3">
                                {{ $campaign->kategori ?? 'Umum' }}
                            </div>

                            <!-- Title -->
                            <h3 class="font-bold text-xl text-slate-800 mb-3 line-clamp-2 hover:text-blue-600 transition-colors">
                                <a href="{{ route('donations.details', ['slug' => $campaign->slug]) }}">
                                    {{ $campaign->title }}
                                </a>
                            </h3>

                            <!-- Description -->
                            <p class="text-slate-600 text-sm mb-4 line-clamp-3">
                                {{ \Illuminate\Support\Str::limit($campaign->description, 100) }}
                            </p>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-blue-600">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</span>
                                    <span class="text-slate-500">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full"
                                         style="width: {{ min(100, ($campaign->current_amount / $campaign->target_amount) * 100) }}%">
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="flex justify-between items-center text-sm">
                                <div>
                                    <p class="text-slate-400 text-xs">Terkumpul</p>
                                    <p class="font-bold text-blue-600">{{ number_format(($campaign->current_amount / $campaign->target_amount) * 100, 1) }}%</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-slate-400 text-xs">Sisa Hari</p>
                                    <p class="font-bold text-slate-700">
                                        <span class="days-remaining" data-end-date="{{ $campaign->end_date }}">
                                            {{ \Carbon\Carbon::parse($campaign->end_date)->diffInDays() > 0 ? \Carbon\Carbon::parse($campaign->end_date)->diffInDays() : 0 }}
                                        </span>
                                        <span class="text-xs font-normal">Hari</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-6">
                                <a href="{{ route('donations.details', ['slug' => $campaign->slug]) }}"
                                    class="block w-full text-center py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold transition-all duration-300 shadow-md hover:shadow-lg">
                                    Donasi Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="bg-white rounded-2xl p-8 max-w-md mx-auto border border-slate-200">
                            <i class="fas fa-heart text-5xl text-blue-100 mb-4"></i>
                            <h3 class="text-xl font-bold text-slate-700 mb-2">Kampanye Tidak Ditemukan</h3>
                            <p class="text-slate-500 mb-6">Tidak ada kampanye yang sesuai dengan kriteria pencarian Anda.</p>
                            <a href="{{ route('campaigns.all') }}"
                                class="inline-block px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold hover:from-blue-700 hover:to-indigo-700 transition-all">
                                Lihat Semua Kampanye
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($campaigns->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </section>
</x-app>