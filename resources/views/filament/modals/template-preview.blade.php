<div class="space-y-6">
    {{-- Template Info --}}
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $template->name }}</h3>
                <p class="text-sm text-gray-600 mt-1">Version: {{ $template->version }} | Author: {{ $template->author }}</p>
                @if(isset($config['industry']))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                        {{ $config['industry'] }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Color Palette --}}
    @if($colors && isset($colors['primary']))
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-3">🎨 Color Palette</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach(['primary', 'secondary', 'accent', 'dark', 'light'] as $colorKey)
                @if(isset($colors[$colorKey]))
                    <div class="flex flex-col items-center">
                        <div class="w-full h-20 rounded-lg shadow-md border border-gray-200" 
                             style="background-color: {{ $colors[$colorKey] }}"></div>
                        <span class="text-xs font-medium text-gray-600 mt-2">{{ ucfirst($colorKey) }}</span>
                        <span class="text-xs text-gray-400">{{ $colors[$colorKey] }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Typography --}}
    @if($typography && isset($typography['font_family_primary']))
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-3">✍️ Typography</h4>
        <div class="bg-white rounded-lg border border-gray-200 p-4 space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Primary Font:</span>
                <span class="text-sm font-medium text-gray-900">{{ $typography['font_family_primary'] }}</span>
            </div>
            @if(isset($typography['font_family_secondary']))
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Secondary Font:</span>
                <span class="text-sm font-medium text-gray-900">{{ $typography['font_family_secondary'] }}</span>
            </div>
            @endif
            @if(isset($typography['font_size_base']))
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Base Size:</span>
                <span class="text-sm font-medium text-gray-900">{{ $typography['font_size_base'] }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Features --}}
    @if(isset($config['features']) && is_array($config['features']))
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-3">✨ Features</h4>
        <div class="flex flex-wrap gap-2">
            @foreach($config['features'] as $feature)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    ✓ {{ $feature }}
                </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Preview Sample --}}
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-3">👀 Style Preview</h4>
        <div class="bg-white rounded-lg border-2 border-gray-200 p-6 space-y-4" 
             style="font-family: {{ $typography['font_family_primary'] ?? 'inherit' }}">
            
            {{-- Header Sample --}}
            <div class="pb-4 border-b" style="background-color: {{ $colors['nav_bg'] ?? '#ffffff' }}">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold" style="color: {{ $colors['primary'] ?? '#000' }}">
                        Company Name
                    </h2>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 rounded-lg text-sm font-medium text-white shadow-md transition-transform hover:scale-105"
                                style="background-color: {{ $colors['primary'] ?? '#0066cc' }}">
                            Primary Button
                        </button>
                        <button class="px-4 py-2 rounded-lg text-sm font-medium text-white shadow-md"
                                style="background-color: {{ $colors['secondary'] ?? '#666666' }}">
                            Secondary
                        </button>
                    </div>
                </div>
            </div>

            {{-- Content Sample --}}
            <div class="space-y-3">
                <h3 class="text-xl font-semibold" style="color: {{ $colors['text_primary'] ?? '#111827' }}">
                    Welcome to Your Website
                </h3>
                <p class="text-sm leading-relaxed" style="color: {{ $colors['text_secondary'] ?? '#6b7280' }}">
                    This is how your content will look with this template. The typography, colors, and overall design aesthetic are all customized for the {{ $config['industry'] ?? 'your' }} industry.
                </p>
                
                {{-- Card Sample --}}
                <div class="grid grid-cols-3 gap-3 mt-4">
                    @foreach(['Feature 1', 'Feature 2', 'Feature 3'] as $feature)
                        <div class="p-4 rounded-lg border shadow-sm hover:shadow-md transition-shadow"
                             style="border-color: {{ $colors['border'] ?? '#e5e7eb' }}; background-color: {{ $colors['background'] ?? '#ffffff' }}">
                            <div class="w-10 h-10 rounded-full mb-2 flex items-center justify-center text-white font-bold"
                                 style="background-color: {{ $colors['accent'] ?? $colors['primary'] }}">
                                {{ $loop->iteration }}
                            </div>
                            <h4 class="text-sm font-semibold mb-1" style="color: {{ $colors['text_primary'] ?? '#111827' }}">
                                {{ $feature }}
                            </h4>
                            <p class="text-xs" style="color: {{ $colors['text_secondary'] ?? '#6b7280' }}">
                                Description text here
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer Sample --}}
            <div class="pt-4 mt-4 border-t text-center text-xs"
                 style="background-color: {{ $colors['footer_bg'] ?? '#1f2937' }}; color: {{ $colors['footer_text'] ?? '#d1d5db' }}; margin: 0 -1.5rem -1.5rem; padding: 1rem;">
                © 2025 Company Name. All rights reserved.
            </div>
        </div>
    </div>

    {{-- Action Info --}}
    <div class="flex items-center justify-between pt-4 border-t">
        <p class="text-sm text-gray-500">
            Like what you see? Close this preview and click "Activează" to use this template.
        </p>
        @if($template->is_active)
            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-800">
                ✓ Currently Active
            </span>
        @endif
    </div>
</div>
