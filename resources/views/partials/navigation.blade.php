<!-- Navigation - Clean Modern Design -->
<nav class="bg-slate-900/95 backdrop-blur-md sticky top-0 z-50 border-b border-slate-800">
    <div class="container mx-auto px-6">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2">
                <span class="text-xl font-bold text-white">Carpathian</span>
                <span class="text-xl font-bold text-purple-400">CMS</span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="/" class="text-gray-300 hover:text-white transition-colors font-medium">Home</a>
                <a href="/shop" class="text-gray-300 hover:text-white transition-colors font-medium">Shop</a>
                <a href="/blog" class="text-gray-300 hover:text-white transition-colors font-medium">Blog</a>
                <a href="/contact" class="text-gray-300 hover:text-white transition-colors font-medium">Contact</a>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <a href="/dashboard" class="text-gray-300 hover:text-white transition-colors font-medium">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-white transition-colors font-medium">Logout</button>
                    </form>
                @else
                    <a href="/login" class="text-gray-300 hover:text-white transition-colors font-medium">Login</a>
                    <a href="/admin/login" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-5 py-2 rounded-lg font-semibold hover:opacity-90 transition-all">
                        Admin
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-gray-300 hover:text-white p-2">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <div class="flex flex-col gap-2">
                <a href="/" class="text-gray-300 hover:text-white py-2 font-medium">Home</a>
                <a href="/shop" class="text-gray-300 hover:text-white py-2 font-medium">Shop</a>
                <a href="/blog" class="text-gray-300 hover:text-white py-2 font-medium">Blog</a>
                <a href="/contact" class="text-gray-300 hover:text-white py-2 font-medium">Contact</a>
                <hr class="border-slate-700 my-2">
                @auth
                    <a href="/dashboard" class="text-gray-300 hover:text-white py-2 font-medium">Dashboard</a>
                @else
                    <a href="/login" class="text-gray-300 hover:text-white py-2 font-medium">Login</a>
                    <a href="/admin/login" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-lg font-semibold text-center">Admin Panel</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
    document.getElementById('mobile-menu').classList.toggle('hidden');
});
</script>
