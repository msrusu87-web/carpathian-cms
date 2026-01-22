<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Form --}}
        <form wire:submit.prevent="startHarvest">
            {{ $this->form }}
        </form>

        {{-- Stats Cards --}}
        @if(!empty($stats))
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary-500">{{ $stats['places_found'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Places Found</div>
                </div>
            </x-filament::section>
            
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-info-500">{{ $stats['websites_scanned'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Sites Scanned</div>
                </div>
            </x-filament::section>
            
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-success-500">{{ $stats['emails_found'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Emails Found</div>
                </div>
            </x-filament::section>
            
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-success-600">{{ $stats['contacts_created'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">New Contacts</div>
                </div>
            </x-filament::section>
            
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-warning-500">{{ $stats['contacts_updated'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Updated</div>
                </div>
            </x-filament::section>
            
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-400">{{ $stats['skipped_no_email'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Skipped (No Email)</div>
                </div>
            </x-filament::section>
        </div>
        @endif

        {{-- Results Table --}}
        @if(!empty($results))
        <x-filament::section>
            <x-slot name="heading">
                📋 Harvest Results ({{ count($results) }} businesses)
            </x-slot>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium">Status</th>
                            <th class="px-4 py-2 text-left font-medium">Business Name</th>
                            <th class="px-4 py-2 text-left font-medium">Email</th>
                            <th class="px-4 py-2 text-left font-medium">Phone</th>
                            <th class="px-4 py-2 text-left font-medium">Website</th>
                            <th class="px-4 py-2 text-left font-medium">Rating</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($results as $result)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-2">
                                @if($result['status'] === 'created')
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-success-100 text-success-700 dark:bg-success-500/20 dark:text-success-400">
                                        ✓ Created
                                    </span>
                                @elseif($result['status'] === 'updated')
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-warning-100 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400">
                                        ↻ Updated
                                    </span>
                                @elseif($result['status'] === 'skipped_no_email')
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                        ⊘ No Email
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                        {{ $result['status'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 font-medium">{{ $result['name'] }}</td>
                            <td class="px-4 py-2">
                                @if($result['email'])
                                    <a href="mailto:{{ $result['email'] }}" class="text-primary-500 hover:underline">
                                        {{ $result['email'] }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($result['phone'])
                                    <a href="tel:{{ $result['phone'] }}" class="text-gray-600 dark:text-gray-400 hover:underline">
                                        {{ $result['phone'] }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($result['website'])
                                    <a href="{{ $result['website'] }}" target="_blank" class="text-primary-500 hover:underline truncate block max-w-xs" title="{{ $result['website'] }}">
                                        {{ Str::limit(parse_url($result['website'], PHP_URL_HOST), 25) }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($result['rating'])
                                    <span class="text-yellow-500">★</span> {{ number_format($result['rating'], 1) }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
        @else
        <x-filament::section>
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📧</div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Ready to Harvest Emails</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                    Configure your search settings above and click <strong>"Start Harvesting"</strong> to find businesses with emails in Romania.
                    The system will search Google Places and scan websites for contact emails.
                </p>
            </div>
        </x-filament::section>
        @endif

        {{-- Loading indicator --}}
        <div wire:loading wire:target="startHarvest" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-8 shadow-xl text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500 mx-auto mb-4"></div>
                <h3 class="text-lg font-medium">Harvesting in Progress...</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Searching Google Places and scanning websites for emails.</p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
