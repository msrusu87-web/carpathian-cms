<x-filament-panels::page>
    <div x-data="{ activeTab: 'dashboard' }" class="space-y-6">
        
        {{-- Status Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @foreach($securityStatus as $key => $status)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 
                {{ $status['status'] === 'active' ? 'border-green-500' : ($status['status'] === 'warning' ? 'border-yellow-500' : 'border-red-500') }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">
                            {{ ucfirst($key) }}
                        </p>
                        <p class="mt-1 text-lg font-semibold 
                            {{ $status['status'] === 'active' ? 'text-green-600' : ($status['status'] === 'warning' ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $status['message'] }}
                        </p>
                    </div>
                    <div class="text-3xl">
                        @if($status['status'] === 'active')
                            <x-heroicon-o-check-circle class="w-10 h-10 text-green-500"/>
                        @elseif($status['status'] === 'warning')
                            <x-heroicon-o-exclamation-triangle class="w-10 h-10 text-yellow-500"/>
                        @else
                            <x-heroicon-o-x-circle class="w-10 h-10 text-red-500"/>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap gap-3 mb-6">
            <x-filament::button wire:click="refreshStatus" color="gray" icon="heroicon-o-arrow-path">
                Refresh Status
            </x-filament::button>
            
            <x-filament::button wire:click="runSecurityAudit" color="success" icon="heroicon-o-shield-check"
                x-on:click="activeTab = 'audit'">
                Run Security Audit
            </x-filament::button>
            
            <x-filament::button wire:click="runPenetrationTest" color="warning" icon="heroicon-o-bug-ant"
                x-on:click="activeTab = 'pentest'">
                Run Penetration Test
            </x-filament::button>
            
            <x-filament::button wire:click="loadBlockedIps" color="danger" icon="heroicon-o-no-symbol"
                x-on:click="activeTab = 'blocked'">
                View Blocked IPs
            </x-filament::button>
            
            <x-filament::button wire:click="generateDailyReport" color="info" icon="heroicon-o-document-text"
                x-on:click="activeTab = 'report'">
                Generate Daily Report
            </x-filament::button>
        </div>

        {{-- Tab Navigation --}}
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'dashboard'" 
                    :class="activeTab === 'dashboard' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Dashboard
                </button>
                <button @click="activeTab = 'audit'"
                    :class="activeTab === 'audit' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Security Audit
                </button>
                <button @click="activeTab = 'pentest'"
                    :class="activeTab === 'pentest' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Penetration Test
                </button>
                <button @click="activeTab = 'blocked'"
                    :class="activeTab === 'blocked' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Blocked IPs
                </button>
                <button @click="activeTab = 'report'"
                    :class="activeTab === 'report' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Daily Report
                </button>
            </nav>
        </div>

        {{-- Tab Contents --}}
        <div class="mt-6">
            {{-- Dashboard Tab --}}
            <div x-show="activeTab === 'dashboard'" class="space-y-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Security Dashboard</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Welcome to the Security Suite. Use the buttons above to run security checks, view blocked IPs, and generate reports.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded p-4">
                            <h4 class="font-medium mb-2">🛡️ Security Audit</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Checks file permissions, monitors failed login attempts, and reviews system security settings.
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded p-4">
                            <h4 class="font-medium mb-2">🔍 Penetration Test</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Tests your website against common attacks: SQL injection, XSS, directory traversal, and more.
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded p-4">
                            <h4 class="font-medium mb-2">🚫 Blocked IPs</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                View all currently blocked IP addresses from both iptables and Fail2Ban.
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded p-4">
                            <h4 class="font-medium mb-2">📊 Daily Report</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Comprehensive daily security report with statistics, top attackers, and anomalies.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Audit Tab --}}
            <div x-show="activeTab === 'audit'">
                <div class="bg-gray-900 rounded-lg shadow p-6 font-mono text-sm">
                    @if($auditOutput)
                        <pre class="text-green-400 whitespace-pre-wrap">{{ $auditOutput }}</pre>
                    @else
                        <p class="text-gray-400 italic">Click "Run Security Audit" to see results here...</p>
                    @endif
                </div>
            </div>

            {{-- Pentest Tab --}}
            <div x-show="activeTab === 'pentest'">
                <div class="bg-gray-900 rounded-lg shadow p-6 font-mono text-sm">
                    @if($pentestOutput)
                        <pre class="text-green-400 whitespace-pre-wrap">{{ $pentestOutput }}</pre>
                    @else
                        <p class="text-gray-400 italic">Click "Run Penetration Test" to see results here...</p>
                    @endif
                </div>
            </div>

            {{-- Blocked IPs Tab --}}
            <div x-show="activeTab === 'blocked'">
                <div class="bg-gray-900 rounded-lg shadow p-6 font-mono text-sm">
                    @if($blockedIpsOutput)
                        <pre class="text-red-400 whitespace-pre-wrap">{{ $blockedIpsOutput }}</pre>
                    @else
                        <p class="text-gray-400 italic">Click "View Blocked IPs" to see results here...</p>
                    @endif
                </div>
            </div>

            {{-- Daily Report Tab --}}
            <div x-show="activeTab === 'report'">
                <div class="bg-gray-900 rounded-lg shadow p-6 font-mono text-sm">
                    @if($reportOutput)
                        <pre class="text-blue-400 whitespace-pre-wrap">{{ $reportOutput }}</pre>
                    @else
                        <p class="text-gray-400 italic">Click "Generate Daily Report" to see results here...</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Back to Admin Link --}}
        <div class="mt-6">
            <a href="{{ route('filament.admin.pages.dashboard') }}" 
               class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                <x-heroicon-o-arrow-left class="w-4 h-4 mr-2"/>
                Back to Dashboard
            </a>
        </div>
    </div>
</x-filament-panels::page>
