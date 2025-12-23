<x-filament-widgets::widget>
    @php
        $admin = $this->getAdminData();
    @endphp
    
    <x-filament::section class="!bg-gradient-to-br from-indigo-900/50 to-purple-800/50 !border-indigo-700/50">
        <x-slot name="heading">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-500/20 rounded-lg">
                    <x-heroicon-o-user-circle class="w-6 h-6 text-indigo-400" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Admin Profile</h3>
                    <p class="text-xs text-indigo-200">Welcome back!</p>
                </div>
            </div>
        </x-slot>

        <div class="mt-6">
            <!-- Avatar and Name -->
            <div class="flex flex-col items-center mb-6">
                <div class="relative mb-4">
                    <img src="{{ $admin['avatar'] }}" alt="{{ $admin['name'] }}" 
                         class="w-24 h-24 rounded-full border-4 border-indigo-500/50 shadow-xl">
                    <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-4 border-indigo-900"></div>
                </div>
                <h4 class="text-xl font-bold text-white">{{ $admin['name'] }}</h4>
                <p class="text-sm text-indigo-300">{{ $admin['email'] }}</p>
                <span class="mt-2 px-3 py-1 text-xs font-semibold bg-indigo-500/20 text-indigo-300 rounded-full">
                    {{ $admin['role'] }}
                </span>
            </div>

            <!-- Info Cards -->
            <div class="space-y-3 mb-6">
                <div class="flex items-center justify-between p-3 rounded-lg bg-indigo-500/10">
                    <div class="flex items-center gap-3">
                        <x-heroicon-o-calendar class="w-4 h-4 text-indigo-400" />
                        <span class="text-sm text-white">Member Since</span>
                    </div>
                    <span class="text-sm font-semibold text-indigo-300">{{ $admin['member_since'] }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 rounded-lg bg-indigo-500/10">
                    <div class="flex items-center gap-3">
                        <x-heroicon-o-clock class="w-4 h-4 text-indigo-400" />
                        <span class="text-sm text-white">Last Login</span>
                    </div>
                    <span class="text-sm font-semibold text-indigo-300">{{ $admin['last_login'] }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2">
                <a href="/admin/users-permissions/users" 
                   class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-lg bg-indigo-500 hover:bg-indigo-600 text-white font-semibold transition-colors">
                    <x-heroicon-o-user class="w-4 h-4" />
                    Edit Profile
                </a>
                
                <a href="/" target="_blank"
                   class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-lg bg-purple-500 hover:bg-purple-600 text-white font-semibold transition-colors">
                    <x-heroicon-o-eye class="w-4 h-4" />
                    View as Customer
                </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
