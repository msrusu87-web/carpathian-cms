@php
    $settings = is_array($widget->settings) ? $widget->settings : json_decode($widget->settings ?? '{}', true);
@endphp

<!-- Hero Section Widget - Viral Pro Design -->
<section class="relative min-h-screen flex items-center overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-700 to-pink-600">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 bg-grid-white/10"></div>

    <!-- Content -->
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center bg-white/20 backdrop-blur-md text-white px-6 py-3 rounded-full mb-8 border border-white/30 shadow-xl" data-aos="fade-down">
                <span class="w-3 h-3 bg-green-400 rounded-full mr-3 animate-pulse"></span>
                <span class="font-semibold">🚀 Platformă CMS cu AI Integrată</span>
            </div>

            <!-- Main Title -->
            <h1 class="text-6xl md:text-7xl lg:text-8xl font-black text-white mb-8 leading-tight" data-aos="fade-up">
                {{ __('messages.hero_title') }}
            </h1>

            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-3xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                {{ __('messages.hero_subtitle') }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-16" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('shop.index') }}" 
                   class="group bg-white text-blue-600 px-10 py-5 rounded-xl font-bold text-lg shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 flex items-center">
                    <i class="fas fa-shopping-bag mr-3 text-2xl"></i>
                    Explorează Magazinul
                    <i class="fas fa-arrow-right ml-3 transform group-hover:translate-x-2 transition-transform"></i>
                </a>
                <a href="{{ route('blog') }}" 
                   class="group bg-transparent border-3 border-white text-white px-10 py-5 rounded-xl font-bold text-lg hover:bg-white hover:text-blue-600 transition-all duration-300 flex items-center">
                    <i class="fas fa-book-open mr-3 text-2xl"></i>
                    Citește Blogul
                    <i class="fas fa-arrow-right ml-3 transform group-hover:translate-x-2 transition-transform"></i>
                </a>
            </div>

            <!-- Trust Indicators -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">500+</div>
                    <div class="text-white/80 text-sm">Clienți Activi</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">99.9%</div>
                    <div class="text-white/80 text-sm">Uptime</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">24/7</div>
                    <div class="text-white/80 text-sm">Suport Tehnic</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">5.0⭐</div>
                    <div class="text-white/80 text-sm">Rating Mediu</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
        <div class="w-8 h-12 border-3 border-white/50 rounded-full flex items-start justify-center p-2">
            <div class="w-2 h-3 bg-white rounded-full animate-pulse"></div>
        </div>
    </div>

    <!-- Decorative Shapes -->
    <div class="absolute top-20 left-10 w-20 h-20 border-4 border-white/20 rounded-lg rotate-45 animate-spin-slow hidden lg:block"></div>
    <div class="absolute bottom-20 right-10 w-16 h-16 border-4 border-white/20 rounded-full animate-pulse hidden lg:block"></div>
</section>

<style>
@keyframes blob {
    0%, 100% { transform: translate(0, 0) scale(1); }
    25% { transform: translate(20px, -30px) scale(1.1); }
    50% { transform: translate(-20px, 20px) scale(0.9); }
    75% { transform: translate(30px, 10px) scale(1.05); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
.animate-spin-slow {
    animation: spin 20s linear infinite;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.bg-grid-white\/10 {
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 50px 50px;
}
</style>
