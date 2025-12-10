<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Status Card -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-wrench-screwdriver class="w-5 h-5 text-orange-500" />
                    <span>Database Health Status</span>
                </div>
            </x-slot>

            <div class="space-y-4">
                @if(empty($issues))
                    <div class="flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="font-semibold text-green-800 dark:text-green-300">Database is Healthy</h3>
                            <p class="text-sm text-green-600 dark:text-green-400">No issues detected in the database</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3 p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="flex-1">
                            <h3 class="font-semibold text-orange-800 dark:text-orange-300">Issues Found</h3>
                            <p class="text-sm text-orange-600 dark:text-orange-400">{{ count($issues) }} issue(s) detected</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        @foreach($issues as $issue)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <svg class="w-5 h-5 text-orange-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $issue['message'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Type: {{ $issue['type'] }}</p>
                                </div>
                                @if(isset($issue['count']))
                                    <span class="px-2 py-1 text-xs font-semibold bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300 rounded">
                                        {{ $issue['count'] }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-filament::section>

        <!-- Repair Log -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-5 h-5 text-blue-500" />
                    <span>Repair Log</span>
                </div>
            </x-slot>

            <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-auto max-h-96">
                <pre class="whitespace-pre-wrap">{{ $repairLog ?: 'No log entries yet...' }}</pre>
            </div>
        </x-filament::section>

        <!-- Quick Actions -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-bolt class="w-5 h-5 text-purple-500" />
                    <span>Quick Actions</span>
                </div>
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button 
                    wire:click="analyzeDatabase"
                    type="button"
                    class="flex items-center justify-center gap-2 p-4 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="font-semibold text-blue-700 dark:text-blue-300">Analyze Database</span>
                </button>

                <button 
                    wire:click="optimizeTables"
                    type="button"
                    class="flex items-center justify-center gap-2 p-4 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="font-semibold text-green-700 dark:text-green-300">Optimize Tables</span>
                </button>

                <button 
                    wire:click="runAiRepair"
                    type="button"
                    class="flex items-center justify-center gap-2 p-4 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 hover:from-orange-100 hover:to-red-100 dark:hover:from-orange-900/30 dark:hover:to-red-900/30 border border-orange-200 dark:border-orange-800 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-semibold text-orange-700 dark:text-orange-300">AI Repair</span>
                </button>
            </div>
        </x-filament::section>

        <!-- Info Card -->
        <x-filament::section>
            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <h4 class="font-semibold text-gray-900 dark:text-gray-100">About AI Repair</h4>
                <p>AI Repair uses Groq AI to analyze your database and provide intelligent recommendations for:</p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li>Finding and fixing orphaned records</li>
                    <li>Detecting duplicate slugs</li>
                    <li>Optimizing database tables</li>
                    <li>Cleaning up inconsistencies</li>
                    <li>Performance optimization suggestions</li>
                </ul>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
