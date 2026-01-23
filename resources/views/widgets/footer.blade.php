<!-- Footer - Clean Modern Design -->
<footer class="bg-slate-900 border-t border-slate-800">
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand Column -->
            <div class="md:col-span-1">
                <h3 class="text-2xl font-bold text-white mb-4">Carpathian <span class="text-purple-400">CMS</span></h3>
                <p class="text-gray-400 mb-4">Modern content management system with AI integration for your business.</p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-purple-600 transition-all">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-purple-600 transition-all">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-purple-600 transition-all">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="/" class="text-gray-400 hover:text-purple-400 transition-colors">Home</a></li>
                    <li><a href="/shop" class="text-gray-400 hover:text-purple-400 transition-colors">Shop</a></li>
                    <li><a href="/blog" class="text-gray-400 hover:text-purple-400 transition-colors">Blog</a></li>
                    <li><a href="/contact" class="text-gray-400 hover:text-purple-400 transition-colors">Contact</a></li>
                </ul>
            </div>

            <!-- Features -->
            <div>
                <h4 class="text-white font-semibold mb-4">Features</h4>
                <ul class="space-y-2">
                    <li><a href="/admin/login" class="text-gray-400 hover:text-purple-400 transition-colors">Admin Panel</a></li>
                    <li><a href="/shop" class="text-gray-400 hover:text-purple-400 transition-colors">E-Commerce</a></li>
                    <li><a href="/blog" class="text-gray-400 hover:text-purple-400 transition-colors">Blog System</a></li>
                    <li><a href="/contact" class="text-gray-400 hover:text-purple-400 transition-colors">Contact Forms</a></li>
                </ul>
            </div>

            <!-- Web Services -->
            <div>
                <h4 class="text-white font-semibold mb-4">Web Services</h4>
                <p class="text-gray-400 mb-4">Need professional web development services?</p>
                <a href="https://qubitpage.com" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:opacity-90 transition-all">
                    <i class="fas fa-external-link-alt"></i> Visit QubitPage.com
                </a>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-slate-800 mt-10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Carpathian CMS Demo. All rights reserved.</p>
            <p class="text-gray-500 text-sm">
                Powered by <a href="https://qubitpage.com" target="_blank" class="text-purple-400 hover:text-purple-300">QubitPage</a>
            </p>
        </div>
    </div>
</footer>
