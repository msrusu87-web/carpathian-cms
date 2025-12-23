<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-envelope class="w-5 h-5 text-primary-500" />
                Recent Contact Messages & Quote Requests
            </div>
        </x-slot>

        <div class="space-y-4">
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-information-circle class="w-6 h-6 text-amber-500 flex-shrink-0 mt-0.5" />
                    <div>
                        <h4 class="font-semibold text-amber-900 dark:text-amber-100 mb-1">Contact Form Integration</h4>
                        <p class="text-sm text-amber-700 dark:text-amber-300 mb-3">
                            To display contact messages here, we need to create a messages table to store form submissions.
                        </p>
                        <div class="flex gap-2">
                            <a href="{{ route('filament.admin.clusters.settings.resources.contact-settings.index') }}" 
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded-md transition-colors">
                                <x-heroicon-o-cog-6-tooth class="w-4 h-4" />
                                Contact Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" />
                    <div>
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-1">Quote Requests</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mb-3">
                            Quote request functionality can be added to the homepage "Get a Quote" button.
                        </p>
                        <p class="text-xs text-blue-600 dark:text-blue-400">
                            💡 This will store customer project details and automatically notify the admin.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Placeholder Messages -->
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <div class="py-3 px-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-gray-900 dark:text-gray-100">Sample Contact Message</span>
                                <span class="text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-0.5 rounded">New</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                Interested in your web development services for my business...
                            </p>
                            <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-500">
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-envelope class="w-3 h-3" />
                                    contact@example.com
                                </span>
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-clock class="w-3 h-3" />
                                    2 hours ago
                                </span>
                            </div>
                        </div>
                        <button class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium">
                            View
                        </button>
                    </div>
                </div>

                <div class="py-3 px-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-gray-900 dark:text-gray-100">Quote Request - E-commerce Project</span>
                                <span class="text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 px-2 py-0.5 rounded">Quote</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                Looking for full e-commerce solution with payment integration...
                            </p>
                            <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-500">
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-envelope class="w-3 h-3" />
                                    business@company.com
                                </span>
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-clock class="w-3 h-3" />
                                    5 hours ago
                                </span>
                            </div>
                        </div>
                        <button class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium">
                            View
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-center pt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                    These are example messages. Integrate your contact form to see real submissions here.
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
