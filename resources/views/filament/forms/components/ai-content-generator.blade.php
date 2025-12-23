<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{
        generating: false,
        instructions: '',
        tone: 'persuasive',
        length: 'medium',
        provider: 'groq',
        autoTranslate: false,
        
        async generate() {
            // Check if basic data exists
            const hasBasicData = $wire.data.name || $wire.data.title || $wire.data.slug;
            console.log('🔍 Checking basic data:', { hasBasicData, wireData: $wire.data });
            
            if (!hasBasicData) {
                $filament.notify('warning', 'Please enter at least a title/name before generating content');
                return;
            }
            
            this.generating = true;
            console.log('🚀 Starting AI generation...', {
                instructions: this.instructions,
                tone: this.tone,
                length: this.length,
                provider: this.provider,
                autoTranslate: this.autoTranslate
            });
            
            try {
                const requestBody = {
                    instructions: this.instructions,
                    tone: this.tone,
                    length: this.length,
                    provider: this.provider,
                    content_type: '{{ $getContentType() }}',
                    target_fields: {{ json_encode($getTargetFields()) }},
                    locale: '{{ app()->getLocale() }}',
                    existing_data: $wire.data,
                    auto_translate: this.autoTranslate
                };
                console.log('📤 Request body:', requestBody);
                
                const response = await fetch('/admin/api/ai-generate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify(requestBody)
                });
                
                console.log('📥 Response status:', response.status);
                const data = await response.json();
                console.log('📦 Response data:', data);
                
                if (data.success) {
                    console.log('✅ Generation successful!', data);
                    
                    // Update form fields with generated content for current language
                    Object.keys(data.content).forEach(field => {
                        console.log(`✍️ Setting ${field}:`, data.content[field].substring(0, 50) + '...');
                        $wire.set(`data.${field}`, data.content[field]);
                    });
                    
                    // Apply translations if auto-translate was enabled
                    if (data.translations && Object.keys(data.translations).length > 0) {
                        console.log('🌍 Applying translations:', Object.keys(data.translations));
                        Object.keys(data.translations).forEach(lang => {
                            Object.keys(data.translations[lang]).forEach(field => {
                                console.log(`🌐 Setting ${field}.${lang}`);
                                // Set translated content for each language
                                // Format: data.field_name.language_code
                                $wire.set(`data.${field}.${lang}`, data.translations[lang][field]);
                            });
                        });
                    }
                    
                    let message = 'Content generated successfully!';
                    if (data.translations && Object.keys(data.translations).length > 0) {
                        message += ` Translated to ${Object.keys(data.translations).length} languages.`;
                    }
                    console.log('🎉', message);
                    $filament.notify('success', message);
                } else {
                    console.error('❌ Generation failed:', data);
                    $filament.notify('danger', data.error || 'Generation failed');
                }
            } catch (error) {
                console.error('💥 AI Generation Error:', error);
                $filament.notify('danger', 'Failed to generate content: ' + error.message);
            } finally {
                this.generating = false;
                console.log('🏁 Generation process completed');
            }
        }
    }" class="space-y-4">
        
        <!-- AI Generator Card -->
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg border-2 border-purple-200 dark:border-purple-800 p-6">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                <div>
                    <h3 class="text-lg font-bold text-purple-900 dark:text-purple-100">AI Content Generator</h3>
                    <p class="text-sm text-purple-700 dark:text-purple-300">Generate professional content instantly with AI</p>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Additional Instructions (Optional)
                </label>
                <textarea 
                    x-model="instructions"
                    rows="2"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                    placeholder="Example: Focus on productivity benefits, include bullet points, mention target audience..."
                ></textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    💡 AI will use the title/name and other fields you've already filled in. Add extra instructions here if needed.
                </p>
            </div>

            <!-- Auto-Translate Option -->
            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input 
                        type="checkbox" 
                        x-model="autoTranslate"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        🌍 Auto-translate to all languages (ro, en, de, fr, es, it)
                    </span>
                </label>
                <p class="mt-1 ml-6 text-xs text-gray-500 dark:text-gray-400">
                    Generate content in current language, then automatically translate to all other available languages
                </p>
            </div>

            <!-- Options Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Tone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tone</label>
                    <select 
                        x-model="tone"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                    >
                        <option value="professional">Professional</option>
                        <option value="persuasive" selected>Persuasive</option>
                        <option value="friendly">Friendly</option>
                        <option value="technical">Technical</option>
                        <option value="casual">Casual</option>
                    </select>
                </div>

                <!-- Length -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Length</label>
                    <select 
                        x-model="length"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                    >
                        <option value="short">Short (1-2 paragraphs)</option>
                        <option value="medium" selected>Medium (3-5 paragraphs)</option>
                        <option value="long">Long (6+ paragraphs)</option>
                    </select>
                </div>

                <!-- Provider -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">AI Provider</label>
                    <select 
                        x-model="provider"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                    >
                        <option value="groq" selected>Groq (Fast & Free)</option>
                        <option value="openai">OpenAI ChatGPT</option>
                    </select>
                </div>
            </div>

            <!-- Generate Button -->
            <button
                type="button"
                @click="generate()"
                :disabled="generating"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
            >
                <svg x-show="!generating" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <svg x-show="generating" class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="generating ? 'Generating...' : 'Generate Content with AI'"></span>
            </button>

            <!-- Info Text -->
            <div class="mt-3 text-xs text-center space-y-1">
                <p class="text-purple-600 dark:text-purple-400">
                    ✨ <strong>Step 1:</strong> Fill in the title/name field below
                </p>
                <p class="text-purple-600 dark:text-purple-400">
                    ✨ <strong>Step 2:</strong> Click "Generate" to create complete content based on your title
                </p>
                <p class="text-gray-500 dark:text-gray-400">
                    Generated content appears in fields below. Review and edit before saving.
                </p>
            </div>
        </div>
    </div>
</x-dynamic-component>
