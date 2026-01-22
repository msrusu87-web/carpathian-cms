<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <div class="p-2 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="text-lg font-semibold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Visitor Analytics</span>
            </div>
        </x-slot>
        
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Browser Stats -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border-2 border-blue-200 dark:border-blue-800">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd" />
                    </svg>
                    Browser Distribution (7 days)
                </h3>
                <div class="space-y-3">
                    @foreach($this->getBrowserStats() as $browser => $count)
                        @php
                            $total = array_sum($this->getBrowserStats());
                            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                            $colors = [
                                'Chrome' => 'bg-blue-500',
                                'Firefox' => 'bg-orange-500',
                                'Safari' => 'bg-cyan-500',
                                'Edge' => 'bg-green-500',
                                'Other' => 'bg-gray-500'
                            ];
                            $color = $colors[$browser] ?? 'bg-gray-500';
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $browser }}</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                <div class="{{ $color }} h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Device Stats -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 border-2 border-green-200 dark:border-green-800">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                    </svg>
                    Device Types (7 days)
                </h3>
                <div class="space-y-3">
                    @foreach($this->getDeviceStats() as $device => $count)
                        @php
                            $total = array_sum($this->getDeviceStats());
                            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                            $colors = [
                                'Desktop' => 'bg-green-500',
                                'Mobile' => 'bg-blue-500',
                                'Tablet' => 'bg-purple-500'
                            ];
                            $color = $colors[$device] ?? 'bg-gray-500';
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $device }}</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                <div class="{{ $color }} h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Recent Visitors Table -->
        <div class="mt-6 bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-900/50 dark:to-slate-900/50 rounded-xl p-6 border-2 border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
                Recent Visitors
            </h3>
            
            @php
                $visitors = $this->getVisitorData();
            @endphp
            
            @if(count($visitors) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-300 dark:border-gray-600">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Location</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">IP Address</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Browser</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Device</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visitors as $visitor)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-white dark:hover:bg-gray-800 transition-colors">
                                    <td class="py-3 px-4">
                                        <span class="text-xl mr-2">{{ $visitor['flag'] }}</span>
                                        <span class="text-gray-900 dark:text-white">{{ $visitor['country'] }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <code class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded text-xs font-mono text-gray-800 dark:text-gray-200">
                                            {{ $visitor['ip'] }}
                                        </code>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                            {{ $visitor['browser'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                            {{ $visitor['device'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 dark:text-gray-400">
                                        {{ $visitor['time'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-500 dark:text-gray-400 py-8">No visitor data available</p>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
