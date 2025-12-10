<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Form -->
        <x-filament::card>
            <form wire:submit="generate">
                {{ $this->form }}
                
                <div class="mt-6 flex gap-4">
                    <x-filament::button 
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="generate"
                        icon="heroicon-o-sparkles"
                        color="primary"
                        size="lg">
                        <span wire:loading.remove wire:target="generate">Generate Content</span>
                        <span wire:loading wire:target="generate">Generating...</span>
                    </x-filament::button>
                </div>
            </form>
        </x-filament::card>

        <!-- Loading State -->
        <div wire:loading wire:target="generate" class="flex items-center justify-center py-12">
            <div class="text-center">
                <x-filament::loading-indicator class="h-12 w-12 mx-auto mb-4" />
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Generating your content...</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">This may take a few moments</p>
            </div>
        </div>

        <!-- Generated Content -->
        @if($generatedContent)
            <x-filament::card>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold">Generated Content</h2>
                        <div class="flex gap-2">
                            @if(isset($data['content_type']) && $data['content_type'] === 'blog_post')
                                <x-filament::button 
                                    wire:click="saveAsPost"
                                    icon="heroicon-o-document-text"
                                    color="success">
                                    Save as Blog Post
                                </x-filament::button>
                            @endif
                            
                            @if(isset($data['content_type']) && $data['content_type'] === 'page')
                                <x-filament::button 
                                    wire:click="saveAsPage"
                                    icon="heroicon-o-document"
                                    color="success">
                                    Save as Page
                                </x-filament::button>
                            @endif
                            
                            <x-filament::button 
                                x-on:click="navigator.clipboard.writeText($refs.content.innerText)"
                                icon="heroicon-o-clipboard-document"
                                color="gray">
                                Copy HTML
                            </x-filament::button>
                        </div>
                    </div>
                    
                    <hr class="my-4 border-gray-200 dark:border-gray-700" />
                    
                    <!-- Preview -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Preview</span>
                        </div>
                        <div class="p-6 prose dark:prose-invert max-w-none">
                            {!! $generatedContent !!}
                        </div>
                    </div>
                    
                    <!-- Raw HTML -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-2 bg-gray-100 dark:bg-gray-950 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Raw HTML</span>
                            <x-filament::button 
                                size="xs"
                                color="gray"
                                x-on:click="
                                    navigator.clipboard.writeText($refs.htmlCode.innerText);
                                    $tooltip('Copied!', { timeout: 2000 })
                                "
                                icon="heroicon-o-clipboard">
                                Copy
                            </x-filament::button>
                        </div>
                        <div class="p-4 overflow-x-auto">
                            <pre x-ref="htmlCode" class="text-xs text-gray-800 dark:text-gray-200 font-mono whitespace-pre-wrap">{{ $generatedContent }}</pre>
                        </div>
                    </div>
                </div>
            </x-filament::card>
        @endif

        <!-- Instructions -->
        @if(!$generatedContent)
            <x-filament::card>
                <div class="prose dark:prose-invert max-w-none">
                    <h3>🤖 How to Use AI Content Writer</h3>
                    <ol>
                        <li>Select the type of content you want to create</li>
                        <li>Enter a title for your content</li>
                        <li>Provide detailed instructions about what you want</li>
                        <li>Choose the tone and length</li>
                        <li>Click "Generate Content" and wait for AI to create it</li>
                        <li>Review the generated content and save it</li>
                    </ol>
                    
                    <h4>💡 Tips for Better Results</h4>
                    <ul>
                        <li>Be specific in your description</li>
                        <li>Mention key points you want covered</li>
                        <li>Specify target audience if relevant</li>
                        <li>Include keywords for SEO if needed</li>
                    </ul>
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament-panels::page>
