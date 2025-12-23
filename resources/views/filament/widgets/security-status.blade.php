<x-filament-widgets::widget>
    @php
        $data = $this->getSecurityData();
    @endphp
    
    <x-filament::section class="!bg-gradient-to-br from-{{ $data['status_color'] }}-900/50 to-{{ $data['status_color'] }}-800/50 !border-{{ $data['status_color'] }}-700/50">
        <x-slot name="heading">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-{{ $data['status_color'] }}-500/20 rounded-lg">
                    <x-heroicon-o-shield-check class="w-6 h-6 text-{{ $data['status_color'] }}-400" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Security Status</h3>
                    <p class="text-xs text-{{ $data['status_color'] }}-200">Last scan: {{ $data['last_scan'] }}</p>
                </div>
            </div>
        </x-slot>

        <div class="mt-6">
            <!-- Security Score Circle -->
            <div class="flex justify-center mb-6">
                <div class="relative w-32 h-32">
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="none" class="text-{{ $data['status_color'] }}-900" />
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="none" 
                                stroke-dasharray="{{ 2 * 3.14159 * 56 }}" 
                                stroke-dashoffset="{{ 2 * 3.14159 * 56 * (1 - $data['score'] / 100) }}" 
                                class="text-{{ $data['status_color'] }}-500 transition-all duration-1000" 
                                stroke-linecap="round" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-white">{{ $data['score'] }}</p>
                            <p class="text-xs text-{{ $data['status_color'] }}-300">Score</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-6">
                <span class="px-4 py-2 rounded-full bg-{{ $data['status_color'] }}-500/20 text-{{ $data['status_color'] }}-300 font-semibold text-sm">
                    {{ $data['status_text'] }}
                </span>
            </div>

            <!-- Security Checks -->
            <div class="space-y-2">
                @foreach($data['checks'] as $check)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-{{ $data['status_color'] }}-500/10">
                        <div class="flex items-center gap-3">
                            <x-dynamic-component :component="$check['icon']" class="w-4 h-4 text-{{ $data['status_color'] }}-400" />
                            <span class="text-sm text-white">{{ $check['name'] }}</span>
                        </div>
                        @if($check['status'])
                            <x-heroicon-o-check-circle class="w-5 h-5 text-green-400" />
                        @else
                            <x-heroicon-o-x-circle class="w-5 h-5 text-red-400" />
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Action Button -->
            <div class="mt-6">
                <a href="{{ route('filament.admin.pages.security-suite') }}" 
                   class="block w-full text-center py-3 px-4 rounded-lg bg-{{ $data['status_color'] }}-500 hover:bg-{{ $data['status_color'] }}-600 text-white font-semibold transition-colors">
                    View Security Suite
                </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
