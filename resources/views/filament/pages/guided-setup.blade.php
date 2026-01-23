<x-filament-panels::page>
    <div class="max-w-4xl mx-auto">
        @if($setupComplete)
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-success-100 dark:bg-success-900 rounded-full flex items-center justify-center mx-auto mb-6">
                    <x-heroicon-o-check-circle class="w-12 h-12 text-success-500" />
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ __('Setup Already Complete') }}
                </h2>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('Your site has been configured. You can access this wizard again from Settings if needed.') }}
                </p>
                <x-filament::button
                    tag="a"
                    href="{{ route('filament.admin.pages.dashboard') }}"
                    icon="heroicon-o-home"
                >
                    {{ __('Go to Dashboard') }}
                </x-filament::button>
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                {{-- Header --}}
                <div class="text-center mb-8">
                    <img src="{{ asset('images/carphatian-logo-transparent.png') }}" alt="Carpathian CMS" class="h-12 mx-auto mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Welcome to Carpathian CMS') }}
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">
                        {{ __('Let\'s set up your site in just a few steps') }}
                    </p>
                </div>

                {{-- Wizard Form --}}
                <form wire:submit="completeSetup">
                    {{ $this->form }}
                </form>

                {{-- Skip Link --}}
                <div class="text-center mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button
                        type="button"
                        wire:click="skipSetup"
                        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    >
                        {{ __('Skip setup and configure later') }}
                    </button>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
