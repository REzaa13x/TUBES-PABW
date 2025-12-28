<x-app title="{{ $campaign->title ?? 'Donation Detail' }} - DonGiv">

        <!-- Hero Banner -->
        <section class="relative h-96 md:h-[500px] overflow-hidden">
            @if($campaign)
            <div class="absolute inset-0">
                @if($campaign->image)
                    <img src="{{ $campaign->image }}"
                         alt="{{ $campaign->title }}"
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null; this.src='https://placehold.co/1200x500/1CABE2/FFFFFF?text=Donation+Campaign';">
                @else
                    <img src="https://placehold.co/1200x500/1CABE2/FFFFFF?text=Donation+Campaign"
                         alt="{{ $campaign->title }}"
                         class="w-full h-full object-cover">
                @endif
            </div>
            @else
            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-600">
                <img src="https://placehold.co/1200x500/1CABE2/FFFFFF?text=Donation+Campaign" class="w-full h-full object-cover" alt="Donation Campaign">
            </div>
            @endif

            <!-- Light overlay for better image visibility while maintaining text readability -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-black/50 backdrop-blur-sm"></div>

            <!-- Content over image -->
            <div class="relative z-20 h-full flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl mx-auto text-center">
                        <nav class="flex justify-center mb-6" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('home') }}" class="text-white hover:text-blue-200">Home</a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <span class="text-white mx-2">/</span>
                                        <a href="{{ route('donation.details') }}" class="text-white hover:text-blue-200">Donations</a>
                                    </div>
                                </li>
                                <li aria-current="page">
                                    <div class="flex items-center">
                                        <span class="text-white mx-2">/</span>
                                        <span class="text-white">{{ $campaign->title ?? 'Campaign' }}</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>

                        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
                            @if($campaign)
                                {{ $campaign->title }}
                            @else
                                Make a Difference Today
                            @endif
                        </h1>

                        <p class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                            @if($campaign)
                                {{ \Illuminate\Support\Str::limit($campaign->description, 200) }}
                            @else
                                Help us create positive change in communities around the world
                            @endif
                        </p>

                        @auth
                            <a href="{{ $campaign ? route('donation.checkout', ['campaign' => $campaign->id]) : route('donation.checkout') }}" class="bg-primary hover:bg-blue-800 text-white font-bold py-3 px-8 rounded-full text-lg transition-all duration-300 transform hover:scale-105 shadow-lg mx-auto inline-block">
                                <i class="fas fa-hand-holding-heart mr-2"></i>Donate Now
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="bg-primary hover:bg-blue-800 text-white font-bold py-3 px-8 rounded-full text-lg transition-all duration-300 transform hover:scale-105 shadow-lg mx-auto inline-block">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login to Donate
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-12 mx-auto w-full">
                    <!-- Donation Overview Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 text-center transition-all duration-300 hover:shadow-xl">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Dukung Misi Kami</h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl mx-auto w-full max-w-xs border border-blue-100 transition-all duration-300 hover:scale-105">
                                <div class="text-3xl font-bold text-primary">Rp {{ number_format($campaign->target_amount ?? 50000000, 0, ',', '.') }}</div>
                                <div class="text-gray-600 mt-2">Target Kampanye</div>
                            </div>
                            <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl mx-auto w-full max-w-xs border border-green-100 transition-all duration-300 hover:scale-105">
                                <div class="text-3xl font-bold text-green-600">Rp {{ number_format($campaign->current_amount ?? 20000000, 0, ',', '.') }}</div>
                                <div class="text-gray-600 mt-2">Terkumpul</div>
                            </div>
                            <div class="text-center p-6 bg-gradient-to-br from-amber-50 to-yellow-50 rounded-xl mx-auto w-full max-w-xs border border-amber-100 transition-all duration-300 hover:scale-105">
                                <div class="text-3xl font-bold text-amber-600">{{ number_format(($campaign->current_amount ?? 20000000) / ($campaign->target_amount ?? 50000000) * 100, 1) }}%</div>
                                <div class="text-gray-600 mt-2">Terkapai</div>
                            </div>
                        </div>

                        <!-- Enhanced Progress Bar -->
                        <div class="mb-8 max-w-2xl mx-auto">
                            <div class="flex justify-between text-sm text-gray-600 mb-3">
                                <span class="font-medium">Rp {{ number_format($campaign->current_amount ?? 0, 0, ',', '.') }}</span>
                                <span class="font-medium">Rp {{ number_format($campaign->target_amount ?? 50000000, 0, ',', '.') }}</span>
                            </div>

                            <!-- Animated Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                                <?php
                                    $currentAmount = $campaign->current_amount ?? 0;
                                    $targetAmount = $campaign->target_amount ?? 1;
                                    $percentage = $targetAmount > 0 ? min(100, ($currentAmount / $targetAmount) * 100) : 0;
                                ?>
                                <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-1000 ease-out flex items-center justify-end pr-2"
                                     style="width: {{ $percentage }}%;">
                                    <span class="text-white text-xs font-bold"
                                          style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                                        {{ number_format($percentage, 1) }}%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-center max-w-xs mx-auto">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl border border-gray-200 transition-all duration-300 hover:scale-105">
                                <div class="text-xl font-bold text-gray-800">{{ $donaturCount ?? 0 }}</div>
                                <div class="text-gray-600">Donatur</div>
                            </div>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl border border-gray-200 transition-all duration-300 hover:scale-105">
                                <div class="text-xl font-bold text-gray-800">
                                    <?php
                                        $endDate = isset($campaign->end_date) ? \Carbon\Carbon::parse($campaign->end_date) : null;
                                        $daysLeft = 0;
                                        if ($endDate) {
                                            $today = \Carbon\Carbon::today();
                                            $daysLeft = max(0, $today->diffInDays($endDate, false));
                                            $daysLeft = $daysLeft >= 0 ? $daysLeft : 0;
                                        }
                                    ?>
                                    <span class="days-remaining" data-end-date="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">{{ $daysLeft }}</span> Hari
                                </div>
                                <div class="text-gray-600">Tersisa</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Impact Icons Section -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 text-center">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Our Impact Areas</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="text-center p-6 rounded-xl hover:shadow-md transition-all duration-300 max-w-xs mx-auto">
                                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-tint text-blue-600 text-2xl"></i>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Clean Water</h3>
                                <p class="text-gray-600 text-sm">Providing access to safe drinking water</p>
                            </div>
                            <div class="text-center p-6 rounded-xl hover:shadow-md transition-all duration-300 max-w-xs mx-auto">
                                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-graduation-cap text-green-600 text-2xl"></i>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Education</h3>
                                <p class="text-gray-600 text-sm">Supporting children's learning opportunities</p>
                            </div>
                            <div class="text-center p-6 rounded-xl hover:shadow-md transition-all duration-300 max-w-xs mx-auto">
                                <div class="bg-amber-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-utensils text-amber-600 text-2xl"></i>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Food & Shelter</h3>
                                <p class="text-gray-600 text-sm">Ensuring basic needs are met</p>
                            </div>
                            <div class="text-center p-6 rounded-xl hover:shadow-md transition-all duration-300 max-w-xs mx-auto">
                                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-medkit text-red-600 text-2xl"></i>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Medical Help</h3>
                                <p class="text-gray-600 text-sm">Emergency medical and health support</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Program Description -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Program Details</h2>

                        <div class="prose max-w-none mx-auto">
                            <div class="mb-6">
                                <h3 class="text-xl font-semibold text-primary mb-3 flex items-center">
                                    <span class="bg-blue-100 w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    </span>
                                    Background
                                </h3>
                                <p class="text-gray-700 leading-relaxed">
                                    @if($campaign)
                                        {{ $campaign->description }}
                                    @else
                                        This program aims to address the urgent need for humanitarian aid in underserved communities.
                                        Through your generous donations, we'll provide essential supplies, education, and healthcare services
                                        to families in need.
                                    @endif
                                </p>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-xl font-semibold text-primary mb-3 flex items-center">
                                    <span class="bg-green-100 w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-bullseye text-green-600"></i>
                                    </span>
                                    Our Goals
                                </h3>
                                <p class="text-gray-700 leading-relaxed">
                                    Our mission is to create sustainable impact by supporting local communities, building infrastructure,
                                    and empowering individuals with the resources they need to thrive. With your help, we aim to reach
                                    over 10,000 people in the next year.
                                </p>
                            </div>

                            <div>
                                <h3 class="text-xl font-semibold text-primary mb-3 flex items-center">
                                    <span class="bg-amber-100 w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-hand-holding-heart text-amber-600"></i>
                                    </span>
                                    Fund Distribution
                                </h3>
                                <p class="text-gray-700 leading-relaxed">
                                    100% of your donation will go directly towards program implementation. DonGiv ensures transparency
                                    and accountability in all our efforts, with regular updates on how your contributions make a difference.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Latest Updates -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Latest Updates</h2>

                        <div class="space-y-6">
                            <div class="flex border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="bg-blue-100 w-16 h-16 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-box text-blue-600 text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">Distribution Day Success</h3>
                                    <div class="flex items-center text-sm text-gray-500 mt-1">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        <span>March 15, 2024</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-users mr-1"></i>
                                        <span>500 families served</span>
                                    </div>
                                    <p class="text-gray-700 mt-3">Today marked a significant milestone as we successfully distributed essential supplies to 500 families.</p>
                                </div>
                            </div>

                            <div class="flex border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="bg-green-100 w-16 h-16 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-book text-green-600 text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">School Supplies Delivered</h3>
                                    <div class="flex items-center text-sm text-gray-500 mt-1">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        <span>February 28, 2024</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-child mr-1"></i>
                                        <span>200 children supported</span>
                                    </div>
                                    <p class="text-gray-700 mt-3">Thanks to your support, we were able to provide school supplies to 200 children in rural areas.</p>
                                </div>
                            </div>

                            <div class="flex border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="bg-amber-100 w-16 h-16 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-tint text-amber-600 text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">Water Project Completed</h3>
                                    <div class="flex items-center text-sm text-gray-500 mt-1">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        <span>January 12, 2024</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-globe-americas mr-1"></i>
                                        <span>300 people daily</span>
                                    </div>
                                    <p class="text-gray-700 mt-3">A new water source has been established, providing clean water to 300 people daily.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Donor Messages -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Messages of Hope</h2>

                        <div class="space-y-6">
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl p-6 border border-blue-100">
                                <div class="flex items-start">
                                    <div class="mr-4">
                                        <div class="bg-blue-200 w-12 h-12 rounded-full flex items-center justify-center">
                                            <i class="fas fa-comment-dots text-blue-700 text-xl"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-gray-700 italic text-lg leading-relaxed">"Every small donation makes a huge difference. Thank you everyone for your generosity! Your support has provided us with hope and the resources we desperately needed."</p>
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600 font-medium">— Maria Rodriguez, Community Leader</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                                <div class="flex items-start">
                                    <div class="mr-4">
                                        <div class="bg-green-200 w-12 h-12 rounded-full flex items-center justify-center">
                                            <i class="fas fa-heart text-green-700 text-xl"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-gray-700 italic text-lg leading-relaxed">"Your kindness restores my faith in humanity. God bless all the donors who have made this possible. This support comes at a time when we needed it most."</p>
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600 font-medium">— James Wilson, Local Beneficiary</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-2xl p-6 border border-amber-100">
                                <div class="flex items-start">
                                    <div class="mr-4">
                                        <div class="bg-amber-200 w-12 h-12 rounded-full flex items-center justify-center">
                                            <i class="fas fa-pray text-amber-700 text-xl"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-gray-700 italic text-lg leading-relaxed">"With your help, we can dream again. My children now have access to education and proper nutrition. Thank you from the bottom of our hearts."</p>
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600 font-medium">— Sarah Johnson, Mother of Three</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Tentang DonGiv -->
                <div class="lg:col-span-1 mx-auto w-full max-w-md">
                    
                    <!-- Tentang DonGiv -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">Tentang DonGiv</h3>
                        <p class="text-gray-600 mb-4 text-center">
                            DonGiv berkomitmen untuk menciptakan perubahan positif melalui pemberian amal yang transparan dan efektif.
                            Kami memverifikasi semua tujuan dan memberikan pembaruan rutin tentang bagaimana donasi memberikan dampak.
                        </p>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center p-3 bg-green-50 rounded-lg mx-auto">
                                <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                                <p class="text-sm font-medium mt-2">Terverifikasi</p>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg mx-auto">
                                <i class="fas fa-file-contract text-blue-600 text-xl"></i>
                                <p class="text-sm font-medium mt-2">Transparan</p>
                            </div>
                        </div>

                        <div class="space-y-2 text-center">
                            <div class="flex items-center justify-center text-sm text-gray-600">
                                <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                <span>contact@dongiv.org</span>
                            </div>
                            <div class="flex items-center justify-center text-sm text-gray-600">
                                <i class="fas fa-phone mr-2 text-blue-600"></i>
                                <span>+62 123 456 789</span>
                            </div>
                            <div class="flex items-center justify-center text-sm text-gray-600">
                                <i class="fas fa-globe mr-2 text-blue-600"></i>
                                <span>www.dongiv.org</span>
                            </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Amount selection
            const amountButtons = document.querySelectorAll('.amount-btn');
            const customAmountInput = document.getElementById('customAmount');
            const amountHiddenInput = document.getElementById('amount');

            amountButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove selected class from all buttons
                    amountButtons.forEach(btn => {
                        btn.classList.remove('bg-blue-50', 'border-blue-500');
                        btn.classList.add('bg-white', 'border-gray-300');
                    });

                    // Add selected class to clicked button
                    this.classList.add('bg-blue-50', 'border-blue-500');
                    this.classList.remove('bg-white', 'border-gray-300');

                    // Set amount to hidden input
                    const amount = this.textContent.replace(/[Rp,.\s]/g, '');
                    amountHiddenInput.value = amount;

                    // Clear custom amount input
                    if (customAmountInput.value) {
                        customAmountInput.value = '';
                    }
                });
            });

            // Custom amount input
            customAmountInput.addEventListener('input', function() {
                if (this.value) {
                    amountHiddenInput.value = this.value;

                    // Remove selected class from all buttons
                    amountButtons.forEach(btn => {
                        btn.classList.remove('bg-blue-50', 'border-blue-500');
                        btn.classList.add('bg-white', 'border-gray-300');
                    });
                }
            });
        });
    </script>

    <script>
        function redirectToCheckout() {
            const campaignId = {{ $campaign ? $campaign->id : 'null' }};
            let url = '/donation-checkout';

            if (campaignId) {
                url += '/' + campaignId;
            }

            window.location.href = url;
        }
    </script>
</x-app>