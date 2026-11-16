@props(['title' => 'DonasiKita - Platform Kebaikan'])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    {{-- CDN Tailwind & FontAwesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- 3D Libraries: Three.js & VanillaTilt for Interactive 3D visuals --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>

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
                        '3d': '0 20px 40px -15px rgba(37, 99, 235, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.8) inset',
                        '3d-hover': '0 30px 60px -20px rgba(37, 99, 235, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.9) inset',
                    },
                    animation: {
                        'slide-up': 'slideUp 0.4s ease-out forwards',
                        'shake': 'shake 0.5s cubic-bezier(.36,.07,.19,.97) both',
                        'float-3d': 'float3D 6s ease-in-out infinite',
                        'float-3d-slow': 'float3D 8s ease-in-out infinite',
                        'float-3d-reverse': 'float3DReverse 7s ease-in-out infinite',
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
                        },
                        float3D: {
                            '0%, 100%': { transform: 'translate3d(0, 0, 0) rotateX(4deg) rotateY(-4deg)' },
                            '50%': { transform: 'translate3d(0, -12px, 20px) rotateX(-4deg) rotateY(6deg)' }
                        },
                        float3DReverse: {
                            '0%, 100%': { transform: 'translate3d(0, 0, 0) rotateX(-4deg) rotateY(5deg)' },
                            '50%': { transform: 'translate3d(0, -14px, 15px) rotateX(5deg) rotateY(-4deg)' }
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

        /* 3D Transform & Perspective Utilities */
        .preserve-3d { transform-style: preserve-3d; }
        .perspective-800 { perspective: 800px; }
        .perspective-1000 { perspective: 1000px; }
        .perspective-1500 { perspective: 1500px; }

        .card-3d-tilt {
            transform-style: preserve-3d;
            transition: transform 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94), box-shadow 0.25s ease;
            will-change: transform;
        }
        .card-3d-tilt .pop-3d {
            transition: transform 0.3s ease;
            transform: translateZ(24px);
        }
        .card-3d-tilt:hover .pop-3d {
            transform: translateZ(36px);
        }
        .card-3d-tilt .pop-3d-lg {
            transition: transform 0.3s ease;
            transform: translateZ(36px);
        }
        .card-3d-tilt:hover .pop-3d-lg {
            transform: translateZ(52px);
        }

        /* Glassmorphism 3D */
        .glass-card-3d {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 20px 40px -10px rgba(30, 58, 138, 0.08), 0 1px 3px rgba(0, 0, 0, 0.02), inset 0 1px 0 rgba(255, 255, 255, 0.95);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card-3d:hover {
            box-shadow: 0 30px 60px -12px rgba(37, 99, 235, 0.16), 0 0 0 1px rgba(99, 102, 241, 0.2), inset 0 1px 0 rgba(255, 255, 255, 1);
            transform: translateY(-4px) translateZ(10px);
        }

        /* Global 3D Interactive Card Lift for Cards across all pages */
        .card-interactive-3d {
            transition: transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.35s ease, border-color 0.3s ease;
            transform-style: preserve-3d;
            will-change: transform;
        }
        .card-interactive-3d:hover {
            transform: translateY(-6px) scale(1.012) rotateX(1.5deg);
            box-shadow: 0 24px 48px -12px rgba(37, 99, 235, 0.14), 0 0 0 1px rgba(99, 102, 241, 0.15);
        }

        /* 3D Dynamic Buttons with Depth */
        .btn-3d {
            transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
            transform-style: preserve-3d;
            will-change: transform;
            position: relative;
        }
        .btn-3d:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 14px 28px -6px rgba(37, 99, 235, 0.35);
        }
        .btn-3d:active {
            transform: translateY(1px) scale(0.98);
            box-shadow: 0 6px 14px -3px rgba(37, 99, 235, 0.25);
        }

        /* 3D Hero Canvas Container - strictly non-blocking */
        .hero-3d-canvas-wrap {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 1;
        }

        /* Subtle 3D Depth Shimmer */
        .shimmer-3d {
            position: relative;
            overflow: hidden;
        }
        .shimmer-3d::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                60deg,
                transparent 30%,
                rgba(255, 255, 255, 0.35) 50%,
                transparent 70%
            );
            transform: rotate(25deg);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .shimmer-3d:hover::after {
            opacity: 1;
            animation: shimmerMove 1.4s forwards;
        }
        @keyframes shimmerMove {
            0% { transform: translate(-35%, -35%) rotate(25deg); }
            100% { transform: translate(35%, 35%) rotate(25deg); }
        }

        /* Ambient Cosmic Pulse for Glow Accents */
        .glow-cosmic {
            position: relative;
        }
        .glow-cosmic::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(217, 70, 239, 0.2), rgba(14, 165, 233, 0.3));
            z-index: -1;
            opacity: 0;
            filter: blur(8px);
            transition: opacity 0.35s ease;
        }
        .glow-cosmic:hover::before {
            opacity: 1;
        }

        /* ===================================================================
           GALAXY COSMIC GRADIENT & FLOATING DONGIV — Semua Halaman
        =================================================================== */

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

        /* Fixed Galaxy Nebula & Stardust Overlay — di belakang konten */
        .galaxy-overlay {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
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

        /* Floating 3D Layer — MURNI SEBAGAI BACKGROUND, DI BAWAH KONTEN */
        #floating3DLayer {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            overflow: hidden;
            z-index: 1;
        }

        .obj3d {
            position: absolute;
            will-change: transform;
            --p-x: 0px;
            --p-y: 0px;
        }

        /* Teks DonGiv — HANYA 2 TITIK TEPI DI BACKGROUND (Bersih, Elegan & Tidak Ramai) */
        .obj-dongiv-text {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-weight: 950;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.45) 0%, rgba(99, 102, 241, 0.5) 50%, rgba(147, 51, 234, 0.45) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            -webkit-text-stroke: 1.5px rgba(37, 99, 235, 0.35);
            white-space: nowrap;
            user-select: none;
            pointer-events: none;
            filter: drop-shadow(0 4px 16px rgba(37, 99, 235, 0.18)) drop-shadow(0 2px 4px rgba(99, 102, 241, 0.12));
            transition: all 0.3s ease;
        }

        /* Tepi Kanan Atas (Di bawah navbar agar tidak bentrok dengan logo navbar di kiri) */
        .obj-dongiv-edge-top-right {
            top: 100px;
            right: 32px;
            animation: driftTopRight 20s ease-in-out infinite;
        }

        /* Tepi Kiri Bawah */
        .obj-dongiv-edge-bottom-left {
            bottom: 36px;
            left: 32px;
            animation: driftBottomLeft 22s ease-in-out infinite;
        }

        @keyframes driftTopRight {
            0%   { transform: translate(var(--p-x), calc(0px + var(--p-y))) rotate(2deg); }
            50%  { transform: translate(calc(-12px + var(--p-x)), calc(-10px + var(--p-y))) rotate(-2deg); }
            100% { transform: translate(var(--p-x), calc(0px + var(--p-y))) rotate(2deg); }
        }

        @keyframes driftBottomLeft {
            0%   { transform: translate(var(--p-x), calc(0px + var(--p-y))) rotate(-2deg); }
            50%  { transform: translate(calc(12px + var(--p-x)), calc(10px + var(--p-y))) rotate(2deg); }
            100% { transform: translate(var(--p-x), calc(0px + var(--p-y))) rotate(-2deg); }
        }

        /* Parallax support */
        .obj3d[data-speed] { transition: transform 0.08s linear; }
    </style>
</head>


<body class="galaxy-theme text-slate-600 font-sans antialiased flex flex-col min-h-screen selection:bg-blue-100 selection:text-blue-900 relative">

    {{-- GALAXY COSMIC OVERLAY --}}
    <div class="galaxy-overlay" aria-hidden="true"></div>

    {{-- ================================================================
         FLOATING DONGIV — HANYA 2 TITIK TEPI DI BACKGROUND (TIDAK RAMAI)
    ================================================================ --}}
    <div id="floating3DLayer" aria-hidden="true">

        {{-- 1. Kanan Atas --}}
        <div class="obj3d obj-dongiv-text obj-dongiv-edge-top-right" data-speed="0.04"
             style="font-size: clamp(2rem, 3.5vw, 3.6rem);">
            DonGiv
        </div>

        {{-- 2. Kiri Bawah --}}
        <div class="obj3d obj-dongiv-text obj-dongiv-edge-bottom-left" data-speed="0.03"
             style="font-size: clamp(2rem, 3.6vw, 3.8rem);">
            DonGiv
        </div>

    </div>

    {{-- UNIFIED NAVBAR --}}
    <x-navbar />

    {{-- KONTEN UTAMA (Berada di atas floating background layer) --}}
    <main class="flex-grow pt-20 relative z-10">
        {{ $slot }}
    </main>

    {{-- FOOTER PREMIUM --}}
    <footer class="bg-[#0B1120] text-gray-300 pt-20 pb-10 border-t border-slate-800/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                {{-- Brand Column --}}
                <div class="space-y-6">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        {{-- Filter brightness-0 invert membuat logo hitam menjadi putih --}}
                        <img src="{{ asset('images/dongiv-logo.png') }}" alt="DonasiKita" class="h-9 brightness-0 invert opacity-90">
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

                {{-- Links 1 --}}
                <div>
                    <h3 class="font-bold text-white text-lg mb-6">Jelajahi</h3>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="#donasi" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Donasi Sekarang</a></li>
                        <li><a href="{{ route('volunteer.campaigns.index') }}" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Jadi Relawan</a></li>
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Kisah Sukses</a></li>
                    </ul>
                </div>

                {{-- Links 2 --}}
                <div>
                    <h3 class="font-bold text-white text-lg mb-6">Dukungan</h3>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Kebijakan Privasi</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
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
    
    {{-- 1. CDN SweetAlert2 (WAJIB ADA UNTUK POPUP) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 2. MAIN SCRIPT --}}
    <script>
        // Function to check authentication state (both server-side and API)
        function updateAuthUI() {
            // Check for API token in localStorage
            const token = localStorage.getItem('auth_token');
            const userData = localStorage.getItem('user');

            // Get all UI containers
            const serverAuthElements = document.getElementById('serverAuthElements');
            const serverNotifContainer = document.getElementById('serverNotifContainer');
            const guestElements = document.getElementById('guestElements');
            const apiAuthElements = document.getElementById('apiAuthElements');
            const apiNotificationContainer = document.getElementById('apiNotificationContainer');
            const mobileAuthButtons = document.getElementById('mobileAuthButtons');
            const mobileProfileContainer = document.getElementById('mobileProfileContainer');

            if (token && userData) {
                try {
                    const user = JSON.parse(userData);

                    // Hide server-side auth elements (if they exist) since we're using API auth
                    if (serverAuthElements) serverAuthElements.classList.add('hidden');
                    if (serverNotifContainer) serverNotifContainer.classList.add('hidden');
                    if (guestElements) guestElements.classList.add('hidden');

                    // Show API authenticated UI
                    if (apiAuthElements) {
                        apiAuthElements.classList.remove('hidden');

                        // Update profile info in API elements
                        const profileInitial = document.getElementById('profileInitial');
                        const profileName = document.getElementById('profileName');
                        const dropdownName = document.getElementById('dropdownName');
                        const dropdownEmail = document.getElementById('dropdownEmail');

                        if (profileInitial) profileInitial.textContent = user.name ? user.name.charAt(0).toUpperCase() : 'U';
                        if (profileName) profileName.textContent = user.name || 'User';
                        if (dropdownName) dropdownName.textContent = user.name || 'User';
                        if (dropdownEmail) dropdownEmail.textContent = user.email || 'user@example.com';
                    }

                    if (apiNotificationContainer) apiNotificationContainer.classList.remove('hidden');

                    // Update mobile menu
                    if (mobileAuthButtons) mobileAuthButtons.classList.add('hidden');
                    if (mobileProfileContainer) {
                        mobileProfileContainer.classList.remove('hidden');

                        // Update mobile profile name
                        const mobileProfileName = document.getElementById('mobileProfileName');
                        if (mobileProfileName) mobileProfileName.textContent = (user.name || 'User') + ' - Profil & Riwayat';
                    }

                    return user;
                } catch (e) {
                    console.error('Error parsing user data:', e);
                    // If there's an error, clear the stored data
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('user');
                    return null;
                }
            } else {
                // Default behavior based on Laravel's server-side auth
                // If serverAuthElements exists (user is logged in via Laravel), show it
                // Otherwise, show guest elements
                if (serverAuthElements && !serverAuthElements.classList.contains('hidden')) {
                    // Server-side auth is active, hide API elements
                    if (apiAuthElements) apiAuthElements.classList.add('hidden');
                    if (apiNotificationContainer) apiNotificationContainer.classList.add('hidden');
                    if (mobileAuthButtons) mobileAuthButtons.classList.add('hidden');
                    if (mobileProfileContainer) mobileProfileContainer.classList.add('hidden');
                } else {
                    // No server-side auth, and no API token, show guest UI
                    if (serverAuthElements) serverAuthElements.classList.add('hidden');
                    if (serverNotifContainer) serverNotifContainer.classList.add('hidden');
                    if (guestElements) {
                        guestElements.classList.remove('hidden');
                    }
                    if (apiAuthElements) apiAuthElements.classList.add('hidden');
                    if (apiNotificationContainer) apiNotificationContainer.classList.add('hidden');
                    if (mobileAuthButtons) {
                        mobileAuthButtons.classList.remove('hidden');
                    }
                    if (mobileProfileContainer) mobileProfileContainer.classList.add('hidden');
                }
            }
        }

        // API Logout function
        async function apiLogout() {
            const token = localStorage.getItem('auth_token');

            if (token) {
                try {
                    await fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + token,
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });
                } catch (e) {
                    console.error('Logout error:', e);
                    // Continue with local cleanup even if API call fails
                }
            }

            // Clear local storage
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');

            // Refresh page to update UI
            window.location.reload();
        }

        // Run check immediately and also after DOM loads
        document.addEventListener('DOMContentLoaded', function() {
            // Update authentication UI on page load
            updateAuthUI();

            // Set up API logout buttons
            const apiLogoutBtn = document.getElementById('apiLogoutBtn');
            const mobileApiLogoutBtn = document.getElementById('mobileApiLogoutBtn');

            if (apiLogoutBtn) {
                apiLogoutBtn.addEventListener('click', apiLogout);
            }

            if (mobileApiLogoutBtn) {
                mobileApiLogoutBtn.addEventListener('click', apiLogout);
            }

            // --- C. SWEETALERT POPUP LOGIC ---

            // Popup Sukses
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{!! session('success') !!}",
                    imageUrl: 'https://cdn-icons-png.flaticon.com/512/148/148767.png', // Icon Checklist
                    imageWidth: 100,
                    imageHeight: 100,
                    imageAlt: 'Success Icon',
                    showConfirmButton: true,
                    confirmButtonText: 'Oke, Mengerti',
                    confirmButtonColor: '#4f46e5',
                    allowOutsideClick: false,
                    backdrop: `rgba(0,0,123,0.4)`,
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl',
                        title: 'text-2xl font-bold text-slate-800',
                        confirmButton: 'rounded-xl px-6 py-2.5 font-bold'
                    }
                });
            @endif

            // Popup Error
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Mohon Maaf',
                    text: "{!! session('error') !!}",
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#ef4444',
                    customClass: {
                        popup: 'rounded-3xl shadow-xl',
                        confirmButton: 'rounded-xl px-6 py-2.5 font-bold'
                    }
                });
            @endif

            // Popup Info
            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: "{!! session('info') !!}",
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'rounded-3xl' }
                });
            @endif
        });

        // Also check on window load in case DOM is ready before script executes
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                updateAuthUI();
            });
        } else {
            // DOM is already loaded
            updateAuthUI();
        }

        // Function to calculate and update remaining days in real-time
        function updateRemainingDays() {
            const dayElements = document.querySelectorAll('.days-remaining');
            const now = new Date();

            dayElements.forEach(element => {
                const endDateStr = element.getAttribute('data-end-date');
                if (!endDateStr) return;

                // Parse the end date from the data attribute
                const endDate = new Date(endDateStr + ' 23:59:59'); // Set to end of the day
                const diffTime = endDate - now;
                const diffDays = Math.max(0, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));

                // Update the display
                element.textContent = diffDays;
            });
        }

        // Initial update
        updateRemainingDays();

        // Update every minute
        setInterval(updateRemainingDays, 60000);

        // Inisialisasi Efek 3D Tilt secara aman dan responsif di seluruh halaman
        function init3DTilt() {
            if (typeof VanillaTilt !== 'undefined') {
                // Terapkan ke semua elemen card, campaign card, glass card, dan elemen interaktif
                const tiltElements = document.querySelectorAll("[data-tilt], .card-3d-tilt, .card-interactive-3d, .glass-card-3d, .tilt-card-auto");
                if (tiltElements.length > 0) {
                    VanillaTilt.init(tiltElements, {
                        max: 6,
                        speed: 600,
                        glare: true,
                        "max-glare": 0.15,
                        scale: 1.018,
                        perspective: 1100,
                        gyroscope: false,
                        easing: "cubic-bezier(.03,.98,.52,.99)"
                    });
                }
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init3DTilt);
        } else {
            init3DTilt();
        }

        /* =================================================================
           GLOBAL 3D PARALLAX — Scroll & Mouse
           Objects bergerak mengikuti scroll dan kursor mouse
        ================================================================= */
        (function() {
            const layer = document.getElementById('floating3DLayer');
            if (!layer) return;

            const objects = Array.from(layer.querySelectorAll('.obj3d[data-speed]'));
            let mouseX = 0, mouseY = 0;
            let ticking = false;

            function updateParallax() {
                const scrollY = window.scrollY;
                objects.forEach(obj => {
                    const speed = parseFloat(obj.getAttribute('data-speed') || 0.04);
                    const scrollShift = scrollY * speed * -0.25;
                    const mouseShiftX = mouseX * speed * 25;
                    const mouseShiftY = mouseY * speed * 15;
                    obj.style.setProperty('--p-y', `${scrollShift + mouseShiftY}px`);
                    obj.style.setProperty('--p-x', `${mouseShiftX}px`);
                });
                ticking = false;
            }

            window.addEventListener('scroll', () => {
                if (!ticking) {
                    requestAnimationFrame(updateParallax);
                    ticking = true;
                }
            }, { passive: true });

            window.addEventListener('mousemove', e => {
                mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
                mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
                if (!ticking) {
                    requestAnimationFrame(updateParallax);
                    ticking = true;
                }
            }, { passive: true });
        })();
    </script>
</body>
</html>