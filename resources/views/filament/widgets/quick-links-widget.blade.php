<x-filament-widgets::widget>
    <x-filament::section class="!bg-gradient-to-br from-purple-900 via-purple-800 to-blue-900 !border-purple-700/50">
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                        <x-heroicon-o-bolt class="w-6 h-6 text-yellow-400" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Quick Actions</h3>
                        <p class="text-xs text-purple-200">Fast access to common tasks</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs px-3 py-1 bg-green-500/20 text-green-300 rounded-full border border-green-500/30">
                        ● Online
                    </span>
                </div>
            </div>
        </x-slot>

        <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mt-4">
            <!-- View Website -->
            <a href="/" target="_blank" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 border border-blue-500/30 hover:from-blue-500/30 hover:to-blue-600/30 hover:border-blue-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-blue-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-globe-alt class="w-8 h-8 text-blue-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-blue-200 group-hover:text-blue-100 transition-colors relative z-10">Website</span>
            </a>

            <!-- Support Chat -->
            <a href="/admin-chat" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-green-500/20 to-emerald-600/20 border border-green-500/30 hover:from-green-500/30 hover:to-emerald-600/30 hover:border-green-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-green-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-chat-bubble-left-right class="w-8 h-8 text-green-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-green-200 group-hover:text-green-100 transition-colors relative z-10">Support</span>
            </a>

            <!-- New Post -->
            <a href="{{ route('filament.admin.blog.resources.posts.create') }}" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/20 border border-purple-500/30 hover:from-purple-500/30 hover:to-purple-600/30 hover:border-purple-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-purple-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-document-plus class="w-8 h-8 text-purple-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-purple-200 group-hover:text-purple-100 transition-colors relative z-10">New Post</span>
            </a>

            <!-- New Page -->
            <a href="{{ route('filament.admin.content.resources.pages.create') }}" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-indigo-500/20 to-indigo-600/20 border border-indigo-500/30 hover:from-indigo-500/30 hover:to-indigo-600/30 hover:border-indigo-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-indigo-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-document-text class="w-8 h-8 text-indigo-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-indigo-200 group-hover:text-indigo-100 transition-colors relative z-10">New Page</span>
            </a>

            <!-- New Product -->
            <a href="{{ route('filament.admin.shop.resources.products.create') }}" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-orange-500/20 to-orange-600/20 border border-orange-500/30 hover:from-orange-500/30 hover:to-orange-600/30 hover:border-orange-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-orange-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-shopping-bag class="w-8 h-8 text-orange-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-orange-200 group-hover:text-orange-100 transition-colors relative z-10">Product</span>
            </a>

            <!-- Orders -->
            <a href="{{ route('filament.admin.shop.resources.orders.index') }}" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-amber-500/20 to-yellow-600/20 border border-amber-500/30 hover:from-amber-500/30 hover:to-yellow-600/30 hover:border-amber-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-amber-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-clipboard-document-list class="w-8 h-8 text-amber-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-amber-200 group-hover:text-amber-100 transition-colors relative z-10">Orders</span>
            </a>

            <!-- Media -->
            <a href="{{ route('filament.admin.content.resources.media.index') }}" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-pink-500/20 to-rose-600/20 border border-pink-500/30 hover:from-pink-500/30 hover:to-rose-600/30 hover:border-pink-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-pink-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-photo class="w-8 h-8 text-pink-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-pink-200 group-hover:text-pink-100 transition-colors relative z-10">Media</span>
            </a>

            <!-- Email -->
            <a href="{{ route('filament.admin.communications.resources.email-templates.index') }}" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-cyan-500/20 to-teal-600/20 border border-cyan-500/30 hover:from-cyan-500/30 hover:to-teal-600/30 hover:border-cyan-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-cyan-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-envelope class="w-8 h-8 text-cyan-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-cyan-200 group-hover:text-cyan-100 transition-colors relative z-10">Email</span>
            </a>

            <!-- AI Writer -->
            <a href="/admin/a-i/ai-content-writer" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-yellow-500/20 to-amber-600/20 border border-yellow-500/30 hover:from-yellow-500/30 hover:to-amber-600/30 hover:border-yellow-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-yellow-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-sparkles class="w-8 h-8 text-yellow-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-yellow-200 group-hover:text-yellow-100 transition-colors relative z-10">AI Writer</span>
            </a>

            <!-- Settings -->
            <a href="/admin/settings/settings" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-gray-500/20 to-slate-600/20 border border-gray-500/30 hover:from-gray-500/30 hover:to-slate-600/30 hover:border-gray-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-gray-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-cog-6-tooth class="w-8 h-8 text-gray-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-gray-200 group-hover:text-gray-100 transition-colors relative z-10">Settings</span>
            </a>

            <!-- Security -->
            <a href="{{ route('filament.admin.pages.security-suite') }}" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-red-500/20 to-rose-600/20 border border-red-500/30 hover:from-red-500/30 hover:to-rose-600/30 hover:border-red-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-red-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-shield-check class="w-8 h-8 text-red-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-red-200 group-hover:text-red-100 transition-colors relative z-10">Security</span>
            </a>

            <!-- Users -->
            <a href="{{ route('filament.admin.users-permissions.resources.users.index') }}" 
               class="group relative overflow-hidden flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-teal-500/20 to-emerald-600/20 border border-teal-500/30 hover:from-teal-500/30 hover:to-emerald-600/30 hover:border-teal-400/50 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-teal-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <x-heroicon-o-users class="w-8 h-8 text-al-400 mb-2 group-hover:scale-110 transition-transform relative z-10" />
                <span class="text-xs font-semibold text-teal-200 group-hover:text-teal-100 transition-colors relative z-10">Users</span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
