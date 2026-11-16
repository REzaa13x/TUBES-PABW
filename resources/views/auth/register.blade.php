@props(['title' => 'Register - DonGiv'])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    {{-- CDN Tailwind & FontAwesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: '#2563EB', // Blue 600
                        secondary: '#3B82F6', // Blue 500
                        accent: '#F59E0B', // Amber 500
                        dark: '#0F172A', // Slate 900
                        softblue: '#EFF6FF', // Blue 50
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 15px rgba(37, 99, 235, 0.3)',
                    },
                    animation: {
                        'slide-up': 'slideUp 0.4s ease-out forwards',
                        'shake': 'shake 0.5s cubic-bezier(.36,.07,.19,.97) both',
                    },
                    keyframes: {
                        slideUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        },
                        shake: {
                            '10%, 90%': { transform: 'translate3d(-1px, 0, 0)' },
                            '20%, 80%': { transform: 'translate3d(2px, 0, 0)' },
                            '30%, 50%, 70%': { transform: 'translate3d(-4px, 0, 0)' },
                            '40%, 60%': { transform: 'translate3d(4px, 0, 0)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Staggered Animation Delays */
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Gradasi Galaksi di Tiap Halaman */
        body.galaxy-theme {
            background-color: #f8faff;
            background-image: 
                radial-gradient(ellipse 80% 50% at 10% 5%, rgba(99, 102, 241, 0.18) 0%, transparent 60%),
                radial-gradient(ellipse 70% 50% at 90% 8%, rgba(217, 70, 239, 0.16) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 50% 30%, rgba(14, 165, 233, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 80% 60% at 15% 75%, rgba(139, 92, 246, 0.18) 0%, transparent 60%),
                radial-gradient(ellipse 70% 50% at 85% 85%, rgba(37, 99, 235, 0.18) 0%, transparent 60%),
                radial-gradient(circle at 50% 95%, rgba(168, 85, 247, 0.14) 0%, transparent 50%),
                linear-gradient(180deg, #f0f4ff 0%, #faf5ff 35%, #f0f9ff 70%, #f5f3ff 100%);
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
        }

        .galaxy-overlay {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 1;
            background-image: 
                radial-gradient(1.5px 1.5px at 15% 20%, rgba(99, 102, 241, 0.5) 50%, transparent 100%),
                radial-gradient(1.5px 1.5px at 35% 10%, rgba(168, 85, 247, 0.6) 50%, transparent 100%),
                radial-gradient(2px 2px at 75% 25%, rgba(56, 189, 248, 0.6) 50%, transparent 100%),
                radial-gradient(1.5px 1.5px at 85% 15%, rgba(236, 72, 153, 0.5) 50%, transparent 100%),
                radial-gradient(1.5px 1.5px at 20% 70%, rgba(59, 130, 246, 0.5) 50%, transparent 100%),
                radial-gradient(2px 2px at 80% 80%, rgba(147, 51, 234, 0.6) 50%, transparent 100%),
                radial-gradient(1px 1px at 50% 85%, rgba(14, 165, 233, 0.5) 50%, transparent 100%);
            background-size: 500px 500px;
            opacity: 0.6;
        }

        #floating3DLayer {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .obj3d {
            position: absolute;
            will-change: transform;
        }

        .obj-dongiv-text {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-weight: 950;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.55) 0%, rgba(99, 102, 241, 0.6) 50%, rgba(147, 51, 234, 0.55) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            -webkit-text-stroke: 1.8px rgba(37, 99, 235, 0.45);
            white-space: nowrap;
            user-select: none;
            pointer-events: none;
            filter: drop-shadow(0 4px 16px rgba(37, 99, 235, 0.22)) drop-shadow(0 2px 4px rgba(99, 102, 241, 0.15));
            transition: all 0.3s ease;
        }

        .obj-dongiv-edge-top-right { top: 100px; right: 32px; animation: driftTopRight 20s ease-in-out infinite; }
        .obj-dongiv-edge-bottom-left { bottom: 36px; left: 32px; animation: driftBottomLeft 22s ease-in-out infinite; }

        @keyframes driftTopRight {
            0%, 100% { transform: translate(0, 0) rotate(2deg); }
            50%      { transform: translate(-12px, -10px) rotate(-2deg); }
        }
        @keyframes driftBottomLeft {
            0%, 100% { transform: translate(0, 0) rotate(-2deg); }
            50%      { transform: translate(12px, 10px) rotate(2deg); }
        }
    </style>
</head>

<body class="galaxy-theme text-slate-600 font-sans antialiased flex flex-col min-h-screen selection:bg-blue-100 selection:text-blue-900 relative">

    <div class="galaxy-overlay" aria-hidden="true"></div>

    <div id="floating3DLayer" aria-hidden="true">
        <div class="obj3d obj-dongiv-text obj-dongiv-edge-top-right" style="font-size: clamp(2rem, 3.5vw, 3.6rem);">DonGiv</div>
        <div class="obj3d obj-dongiv-text obj-dongiv-edge-bottom-left" style="font-size: clamp(2rem, 3.6vw, 3.8rem);">DonGiv</div>
    </div>

    {{-- UNIFIED NAVBAR --}}
    <x-navbar />

    {{-- MAIN CONTENT --}}
    <main class="flex-grow pt-20 relative z-10">
        <div class="min-h-[calc(100vh-5rem)] flex items-center justify-center px-4">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-900/10 border border-white/50 p-8 animate-slide-up">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 text-indigo-600 mb-4 shadow-inner ring-1 ring-indigo-100">
                            <i class="fas fa-user-plus text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Buat Akun Baru</h2>
                        <p class="text-slate-500 mt-2">Bergabunglah dengan komunitas kebaikan kami.</p>
                    </div>

                    <form id="registerForm" class="space-y-4">
                        {{-- Hidden Role (Default to User) --}}
                        <input type="hidden" name="role" value="user">

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-user"></i>
                                </div>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    required
                                    class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 placeholder:text-slate-400 font-medium"
                                    placeholder="Nama Lengkap"
                                >
                            </div>
                            <div id="name-error" class="error-message"></div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    required
                                    class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 placeholder:text-slate-400 font-medium"
                                    placeholder="nama@email.com"
                                >
                            </div>
                            <div id="email-error" class="error-message"></div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 placeholder:text-slate-400 font-medium"
                                    placeholder="Min. 8 karakter"
                                >
                            </div>
                            <div id="password-error" class="error-message"></div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-lock-open"></i>
                                </div>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 placeholder:text-slate-400 font-medium"
                                    placeholder="Ulangi password"
                                >
                            </div>
                            <div id="password_confirmation-error" class="error-message"></div>
                        </div>

                        <button
                            type="submit"
                            id="registerButton"
                            class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 mt-2"
                        >
                            Daftar Akun
                        </button>
                    </form>

                    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                        <p class="text-sm text-slate-600">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:text-purple-600 hover:underline focus:outline-none transition-colors">
                                Masuk disini
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-[#0B1120] text-gray-300 pt-20 pb-10 border-t border-slate-800/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="space-y-6">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/dongiv-logo.png') }}" alt="DonGiv" class="h-9 brightness-0 invert opacity-90">
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Platform crowdfunding terpercaya yang menghubungkan kebaikan hati para donatur dengan ribuan cerita yang membutuhkan bantuan nyata.
                    </p>
                    <div class="flex gap-4 pt-2">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:-translate-y-1 transition-all duration-300 ring-1 ring-slate-700 hover:ring-primary"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:-translate-y-1 transition-all duration-300 ring-1 ring-slate-700 hover:ring-primary"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:-translate-y-1 transition-all duration-300 ring-1 ring-slate-700 hover:ring-primary"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-white text-lg mb-6">Jelajahi</h3>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="{{ url('/') }}" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Beranda</a></li>
                        <li><a href="#donasi" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Donasi Sekarang</a></li>
                        <li><a href="{{ route('volunteer.campaigns.index') }}" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Jadi Relawan</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-white text-lg mb-6">Dukungan</h3>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Pusat Bantahan</a></li>
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50">
                    <h3 class="font-bold text-white mb-2">Kabar Kebaikan</h3>
                    <p class="text-xs text-slate-400 mb-4">Dapatkan update penyaluran donasi setiap minggunya.</p>
                    <form action="#" class="space-y-2">
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3 text-slate-500"></i>
                            <input type="email" placeholder="Alamat email..." class="w-full pl-9 pr-4 py-2.5 rounded-xl text-sm bg-slate-900 border border-slate-700 text-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-slate-600">
                        </div>
                        <button class="w-full py-2.5 bg-primary hover:bg-blue-600 text-white text-sm font-bold rounded-xl transition-colors shadow-lg shadow-blue-900/20">Berlangganan</button>
                    </form>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-slate-500">&copy; {{ date('Y') }} DonGiv. Hak Cipta Dilindungi.</p>
                <div class="flex gap-6 text-sm text-slate-500 font-medium">
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition-colors">Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- REGISTER SCRIPT --}}
    <script>
        // Register Form Handler
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Clear previous errors
            document.getElementById('name-error').textContent = '';
            document.getElementById('email-error').textContent = '';
            document.getElementById('password-error').textContent = '';
            document.getElementById('password_confirmation-error').textContent = '';

            const formData = new FormData(this);
            const name = formData.get('name');
            const email = formData.get('email');
            const password = formData.get('password');
            const password_confirmation = formData.get('password_confirmation');

            const registerButton = document.getElementById('registerButton');
            registerButton.disabled = true;
            registerButton.textContent = 'Mendaftar...';

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        role: formData.get('role'),
                        password: password,
                        password_confirmation: password_confirmation
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Save token and user data to localStorage
                    localStorage.setItem('auth_token', data.token);
                    localStorage.setItem('user', JSON.stringify(data.user));

                    // Redirect to homepage
                    window.location.href = '/';
                } else {
                    // Display errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            document.getElementById(`${field}-error`).textContent = data.errors[field][0];
                        });
                    } else if (data.message) {
                        alert(data.message);
                    }
                }
            } catch (error) {
                console.error('Registration error:', error);
                alert('Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
            } finally {
                registerButton.disabled = false;
                registerButton.textContent = 'Daftar Akun';
            }
        });
    </script>
</body>
</html>