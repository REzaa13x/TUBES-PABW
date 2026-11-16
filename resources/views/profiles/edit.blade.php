<x-app title="Edit Profil - DonGiv">
    <div class="min-h-screen bg-transparent py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800">Edit Profil</h1>
                <p class="text-slate-500 mt-1">Perbarui informasi akun Anda</p>
            </div>

            <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-xl p-6 md:p-8 border border-slate-100">
                @if(session('success'))
                    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-xl shadow-xs">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <i class="fas fa-check-circle text-2xl mt-1 text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-lg">Sukses!</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('profiles.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Profile Picture Section -->
                    <div class="flex flex-col items-center mb-8">
                        <div class="relative">
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}"
                                     alt="Profile Photo"
                                     class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 shadow-md">
                            @else
                                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-5xl font-bold border-4 border-blue-100 shadow-md">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <label for="photo" class="absolute bottom-1 right-1 bg-white rounded-full p-2.5 shadow-md border border-slate-100 cursor-pointer hover:bg-blue-50 transition-colors">
                                <i class="fas fa-camera text-blue-600 text-sm"></i>
                                <input type="file" name="photo" id="photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <p class="mt-3 text-xs text-slate-500">Klik ikon kamera untuk mengganti foto</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Nama Lengkap</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none font-medium @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Email</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none font-medium @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Nomor Telepon</label>
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none font-medium @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birth_date" class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Tanggal Lahir</label>
                            <input type="date"
                                   name="birth_date"
                                   id="birth_date"
                                   value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none font-medium @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-3 sm:space-y-0 pt-2">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 shadow-md shadow-blue-500/25">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('profiles.index') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-6 rounded-xl transition-all duration-300 text-center">
                            <i class="fas fa-arrow-left mr-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app>
