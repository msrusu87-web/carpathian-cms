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
                        <span wire:loading.remove wire:target="generate">Generate Plugin</span>
                        <span wire:loading wire:target="generate">Generating...</span>
                    </x-filament::button>
                </div>
            </form>
        </x-filament::card>

        <!-- Loading State -->
        <div wire:loading wire:target="generate" class="flex items-center justify-center py-12">
            <div class="text-center">
                <x-filament::loading-indicator class="h-12 w-12 mx-auto mb-4" />
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Generating your plugin...</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">AI is writing the code for you</p>
            </div>
        </div>

        <!-- Generated Code -->
        @if($generatedCode)
            <x-filament::card>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold">Generated Plugin Code</h2>
                        <div class="flex gap-2">
                            <x-filament::button 
                                wire:click="saveAsPlugin"
                                icon="heroicon-o-archive-box"
                                color="success">
                                Save as Plugin
                            </x-filament::button>
                            
                            <x-filament::button 
                                x-on:click="navigator.clipboard.writeText($refs.pluginCode.innerText)"
                                icon="heroicon-o-clipboard-document"
                                color="gray">
                                Copy Code
                            </x-filament::button>
                        </div>
                    </div>
                    
                    <hr class="my-4 border-gray-200 dark:border-gray-700" />
                    
                    <!-- Plugin Info -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-blue-900 dark:text-blue-100">Plugin Details</h3>
                                <div class="mt-2 text-sm text-blue-800 dark:text-blue-200 space-y-1">
                                    <p><strong>Name:</strong> {{ $data['name'] ?? 'N/A' }}</p>
                                    <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $data['plugin_type'] ?? 'N/A')) }}</p>
                                    <p><strong>Status:</strong> Ready to save and activate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Code Display -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-2 bg-gray-100 dark:bg-gray-950 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Plugin Code (PHP)</span>
                            <x-filament::button 
                                size="xs"
                                color="gray"
                                x-on:click="
                                    navigator.clipboard.writeText($refs.pluginCode.innerText);
                                    $tooltip('Copied!', { timeout: 2000 })
                                "
                                icon="heroicon-o-clipboard">
                                Copy
                            </x-filament::button>
                        </div>
                        <div class="p-4 overflow-x-auto max-h-[600px] overflow-y-auto">
                            <pre x-ref="pluginCode" class="text-xs text-gray-800 dark:text-gray-200 font-mono whitespace-pre-wrap">{{ $generatedCode }}</pre>
                        </div>
                    </div>

                    <!-- README if available -->
                    @if($generatedReadme)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="px-4 py-2 bg-gray-100 dark:bg-gray-950 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">README.md</span>
                            </div>
                            <div class="p-4 prose dark:prose-invert max-w-none">
                                {!! \Illuminate\Support\Str::markdown($generatedReadme) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </x-filament::card>
        @endif

        <!-- Instructions -->
        @if(!$generatedCode)
            <x-filament::card>
                <div class="prose dark:prose-invert max-w-none">
                    <h3>🔌 How to Use AI Plugin Generator</h3>
                    <ol>
                        <li>Enter a descriptive name for your plugin</li>
                        <li>Describe what functionality you want the plugin to provide</li>
                        <li>Select the type of plugin (Widget, Shortcode, Module, etc.)</li>
                        <li>Add any specific requirements or technical details</li>
                        <li>Click "Generate Plugin" and let AI write the code</li>
                        <li>Review the generated code and save it as a plugin</li>
                    </ol>
                    
                    <h4>💡 Tips for Better Results</h4>
                    <ul>
                        <li>Be specific about what features you need</li>
                        <li>Mention any external APIs or libraries to integrate</li>
                        <li>Specify the target audience (admin, frontend users, etc.)</li>
                        <li>Include any UI/UX requirements</li>
                        <li>Mention database requirements if any</li>
                    </ul>

                    <h4>🎯 Example Plugin Ideas</h4>
                    <ul>
                        <li><strong>Contact Form:</strong> "Create a contact form with email notifications and spam protection"</li>
                        <li><strong>Social Share:</strong> "Add social media sharing buttons with counters for posts"</li>
                        <li><strong>Analytics Widget:</strong> "Display Google Analytics data in admin dashboard"</li>
                        <li><strong>Image Gallery:</strong> "Create a responsive image gallery with lightbox"</li>
                    </ul>
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament-panels::page>
