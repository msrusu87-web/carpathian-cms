<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Form Section --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form wire:submit="generateArticles">
                {{ $this->form }}
            </form>
        </div>

        {{-- Generated Articles --}}
        @if(count($generatedArticles) > 0)
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('Generated Articles') }} ({{ count($generatedArticles) }})
                </h3>

                <div class="space-y-4">
                    @foreach($generatedArticles as $index => $article)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $article['status'] === 'published' ? 'bg-success-50 dark:bg-success-950' : '' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 dark:text-white">
                                        {{ $article['title'] }}
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $article['excerpt'] }}
                                    </p>
                                    @if($article['status'] === 'published')
                                        <span class="inline-flex items-center px-2 py-1 mt-2 text-xs font-medium rounded-full bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300">
                                            <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
                                            {{ __('Published') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <x-filament::button
                                        size="sm"
                                        color="gray"
                                        wire:click="previewArticle({{ $index }})"
                                        icon="heroicon-o-eye"
                                    >
                                        {{ __('Preview') }}
                                    </x-filament::button>
                                    @if($article['status'] !== 'published')
                                        <x-filament::button
                                            size="sm"
                                            color="primary"
                                            wire:click="publishArticle({{ $index }})"
                                            icon="heroicon-o-paper-airplane"
                                        >
                                            {{ __('Publish') }}
                                        </x-filament::button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Loading State --}}
        @if($isGenerating)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-8 text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500 mx-auto mb-4"></div>
                    <p class="text-gray-900 dark:text-white font-medium">{{ __('Generating articles...') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ __('This may take a minute') }}</p>
                </div>
            </div>
        @endif

        {{-- Preview Modal --}}
        @if($previewContent)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" wire:click="closePreview">
                <div class="bg-white dark:bg-gray-900 rounded-xl max-w-4xl w-full max-h-[80vh] overflow-y-auto p-6" wire:click.stop>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Article Preview') }}</h3>
                        <x-filament::icon-button
                            icon="heroicon-o-x-mark"
                            wire:click="closePreview"
                        />
                    </div>
                    <div class="prose dark:prose-invert max-w-none">
                        {!! $previewContent !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
