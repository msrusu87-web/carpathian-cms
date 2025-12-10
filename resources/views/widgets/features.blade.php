@php
    $content = is_string($widget->content) ? json_decode($widget->content, true) : $widget->content;
    $heading = $content['heading'] ?? 'Funcționalități Puternice';
    $features = $content['features'] ?? [];
    
    // Default features if none configured
    if(empty($features)) {
        $features = [
            ['icon' => '🎨', 'title' => 'Design Modern', 'description' => 'Interfață intuitivă și atrăgătoare cu design responsive', 'link' => '/posts/design-modern-ghid-complet-interfete-intuitive'],
            ['icon' => '⚡', 'title' => 'Performanță Ridicată', 'description' => 'Platformă optimizată pentru viteză și performanță excepțională', 'link' => '/posts/performanta-ridicata-optimizare-viteza-maxima'],
            ['icon' => '🤖', 'title' => 'Integrare AI', 'description' => 'Inteligență artificială pentru generare conținut și automatizare', 'link' => '/posts/integrare-ai-inteligenta-artificiala-cms'],
            ['icon' => '🔒', 'title' => 'Securitate', 'description' => 'Protecție completă cu SSL, firewall, backup automat 24/7', 'link' => '/posts/securitate-avansata-protectie-date-cms'],
            ['icon' => '📱', 'title' => 'Multi-Platform', 'description' => 'Perfect pe orice dispozitiv - desktop, tabletă sau mobil', 'link' => '/posts/multi-platform-functionare-orice-dispozitiv'],
            ['icon' => '🔧', 'title' => 'Personalizabil', 'description' => 'Configurare completă adaptată nevoilor tale specifice', 'link' => '/posts/personalizare-completa-configurare-cms'],
        ];
    }
@endphp

<!-- Features Section Widget - Viral Pro Design -->
<section class="py-24 bg-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-purple-600"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-20" data-aos="fade-up">
            <span class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-bold px-4 py-2 rounded-full mb-4">
                <i class="fas fa-star mr-2"></i>FEATURES
            </span>
            <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-6">
                {{ $heading }}
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Descoperă funcționalitățile care fac platforma noastră unică și puternică
            </p>
        </div>
        
        @if(count($features) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $index => $feature)
                    <a href="{{ $feature['link'] ?? '#' }}" class="group relative block" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <!-- Card -->
                        <div class="relative bg-gradient-to-br from-white to-gray-50 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden cursor-pointer">
                            <!-- Decorative Corner -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-150 transition-transform duration-500"></div>
                            
                            <!-- Icon -->
                            <div class="relative z-10 mb-6">
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-4xl transform group-hover:rotate-12 group-hover:scale-110 transition-all duration-500 shadow-xl">
                                    {{ $feature['icon'] ?? '⭐' }}
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors relative z-10">
                                {{ $feature['title'] }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed relative z-10">
                                {{ $feature['description'] }}
                            </p>

                            <!-- Learn More Indicator -->
                            <div class="mt-6 inline-flex items-center text-blue-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity relative z-10">
                                <span class="mr-2">Citește Articolul</span>
                                <i class="fas fa-arrow-right transform group-hover:translate-x-2 transition-transform"></i>
                            </div>

                            <!-- Animated Border -->
                            <div class="absolute inset-0 rounded-2xl border-2 border-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>

                        <!-- Floating Number Badge -->
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-black text-xl shadow-xl z-20">
                            {{ $index + 1 }}
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Bottom CTA -->
            <div class="text-center mt-16" data-aos="fade-up" data-aos-delay="400">
                <div class="inline-flex flex-col sm:flex-row items-center gap-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-6 rounded-2xl shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-rocket text-4xl mr-4"></i>
                        <div class="text-left">
                            <p class="font-bold text-xl">Gata să începi?</p>
                            <p class="text-white/80 text-sm">Explorează toate funcționalitățile acum</p>
                        </div>
                    </div>
                    <a href="{{ route('shop.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-xl font-bold hover:bg-gray-100 transition-colors whitespace-nowrap">
                        Vezi Produsele
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-16 bg-gray-50 rounded-2xl">
                <i class="fas fa-star text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Nu există funcționalități configurate încă.</p>
            </div>
        @endif
    </div>
</section>
