@php
    $settings = is_array($widget->settings) ? $widget->settings : json_decode($widget->settings ?? '{}', true);
@endphp

<!-- Hero Section Widget - Compact Posh Design -->
<section class="relative py-16 md:py-20 overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-700 to-pink-600">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-64 md:w-96 h-64 md:h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
            <div class="absolute top-0 right-0 w-64 md:w-96 h-64 md:h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
        </div>
    </div>

    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 bg-grid-white/10"></div>

    <!-- Content -->
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center bg-white/20 backdrop-blur-md text-white px-4 py-2 rounded-full mb-4 border border-white/30 shadow-lg text-sm" data-aos="fade-down">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                <span class="font-semibold">🚀 {{ __('messages.hero_badge') }}</span>
            </div>

            <!-- Main Title -->
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-4 leading-tight" data-aos="fade-up">
                {{ __('messages.hero_title') }}
            </h1>

            <!-- Subtitle -->
            <p class="text-base md:text-lg text-white/90 mb-6 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                {{ __('messages.hero_subtitle') }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center mb-8" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('shop.index') }}" 
                   class="group bg-white text-blue-600 px-6 py-3 rounded-lg font-bold text-sm shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    {{ __('messages.explore_shop') }}
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform text-xs"></i>
                </a>
                <a href="{{ route('blog') }}" 
                   class="group bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-bold text-sm hover:bg-white hover:text-blue-600 transition-all duration-300 flex items-center">
                    <i class="fas fa-book-open mr-2"></i>
                    {{ __('messages.read_blog') }}
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform text-xs"></i>
                </a>
            </div>

            <!-- Trust Indicators - Compact Horizontal -->
            <div class="flex flex-wrap justify-center gap-4 md:gap-8" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                    <span class="text-xl font-black text-white">500+</span>
                    <span class="text-white/80 text-xs">{{ __('messages.active_clients') }}</span>
                </div>
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                    <span class="text-xl font-black text-white">99.9%</span>
                    <span class="text-white/80 text-xs">{{ __('messages.uptime') }}</span>
                </div>
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                    <span class="text-xl font-black text-white">24/7</span>
                    <span class="text-white/80 text-xs">{{ __('messages.tech_support') }}</span>
                </div>
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                    <span class="text-xl font-black text-white">5.0⭐</span>
                    <span class="text-white/80 text-xs">{{ __('messages.avg_rating') }}</span>
                </div>
            </div>
        </div>
    </div>
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
.bg-grid-white\/10 {
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 50px 50px;
}
</style>
