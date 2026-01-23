<div class="space-y-4">
    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
        <x-heroicon-o-building-office class="w-6 h-6 text-primary-500" />
        <div>
            <div class="font-medium text-gray-900 dark:text-white">{{ $siteName ?? 'Your Site' }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Site Name') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        @if($features['shop'] ?? false)
            <div class="flex items-center gap-2 p-2 bg-success-50 dark:bg-success-950 rounded text-success-700 dark:text-success-300 text-sm">
                <x-heroicon-o-check class="w-4 h-4" />
                {{ __('E-commerce Shop') }}
            </div>
        @endif
        
        @if($features['blog'] ?? false)
            <div class="flex items-center gap-2 p-2 bg-success-50 dark:bg-success-950 rounded text-success-700 dark:text-success-300 text-sm">
                <x-heroicon-o-check class="w-4 h-4" />
                {{ __('Blog') }}
            </div>
        @endif
        
        @if($features['portfolio'] ?? false)
            <div class="flex items-center gap-2 p-2 bg-success-50 dark:bg-success-950 rounded text-success-700 dark:text-success-300 text-sm">
                <x-heroicon-o-check class="w-4 h-4" />
                {{ __('Portfolio') }}
            </div>
        @endif
        
        @if($features['ai'] ?? false)
            <div class="flex items-center gap-2 p-2 bg-success-50 dark:bg-success-950 rounded text-success-700 dark:text-success-300 text-sm">
                <x-heroicon-o-check class="w-4 h-4" />
                {{ __('AI Features') }}
            </div>
        @endif
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ __('Click "Complete Setup" to finish configuring your site.') }}
    </p>
</div>
