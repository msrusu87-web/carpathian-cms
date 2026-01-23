<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Theme Gallery --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Available Themes') }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($availableThemes as $theme)
                    <div class="border rounded-lg overflow-hidden {{ $activeTheme === $theme['id'] ? 'ring-2 ring-primary-500 border-primary-500' : 'border-gray-200 dark:border-gray-700' }}">
                        {{-- Theme Thumbnail --}}
                        <div class="aspect-video bg-gray-100 dark:bg-gray-800 relative">
                            @if(isset($theme['thumbnail']) && file_exists(public_path($theme['thumbnail'])))
                                <img src="{{ asset($theme['thumbnail']) }}" alt="{{ $theme['name'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-heroicon-o-photo class="w-12 h-12 text-gray-400" />
                                </div>
                            @endif
                            
                            @if($activeTheme === $theme['id'])
                                <div class="absolute top-2 right-2 bg-primary-500 text-white text-xs px-2 py-1 rounded">
                                    {{ __('Active') }}
                                </div>
                            @endif
                        </div>

                        {{-- Theme Info --}}
                        <div class="p-4">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $theme['name'] }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $theme['description'] ?? '' }}</p>
                            
                            <div class="flex items-center gap-2 mt-3">
                                @if($activeTheme !== $theme['id'])
                                    <x-filament::button
                                        size="sm"
                                        color="primary"
                                        wire:click="activateTheme('{{ $theme['id'] }}')"
                                    >
                                        {{ __('Activate') }}
                                    </x-filament::button>
                                @endif
                                <x-filament::button
                                    size="sm"
                                    color="gray"
                                    wire:click="previewTheme('{{ $theme['id'] }}')"
                                >
                                    {{ __('Preview') }}
                                </x-filament::button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Theme Customization --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Theme Customization') }}
            </h3>

            <form wire:submit="saveSettings">
                {{ $this->form }}
            </form>
        </div>

        {{-- Preview Modal --}}
        @if($previewTheme)
            <div class="fixed inset-0 bg-black/75 flex items-center justify-center z-50 p-4" wire:click="closePreview">
                <div class="bg-white dark:bg-gray-900 rounded-xl max-w-6xl w-full max-h-[90vh] overflow-hidden" wire:click.stop>
                    <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ __('Theme Preview') }}: {{ collect($availableThemes)->firstWhere('id', $previewTheme)['name'] ?? $previewTheme }}
                        </h3>
                        <div class="flex items-center gap-2">
                            @if($activeTheme !== $previewTheme)
                                <x-filament::button
                                    size="sm"
                                    color="primary"
                                    wire:click="activateTheme('{{ $previewTheme }}')"
                                >
                                    {{ __('Activate This Theme') }}
                                </x-filament::button>
                            @endif
                            <x-filament::icon-button
                                icon="heroicon-o-x-mark"
                                wire:click="closePreview"
                            />
                        </div>
                    </div>
                    <div class="h-[70vh] overflow-auto">
                        <iframe src="{{ url('/') }}?preview_theme={{ $previewTheme }}" class="w-full h-full border-0"></iframe>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
