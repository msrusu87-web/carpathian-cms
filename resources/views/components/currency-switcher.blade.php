<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
        @php
            $currencyService = app(\App\Services\CurrencyService::class);
            $currentCurrency = $currencyService->getCurrentCurrency();
            $currencies = $currencyService->getCurrencies();
        @endphp
        <i class="fas fa-money-bill-wave text-gray-600"></i>
        <span class="font-semibold">{{ $currentCurrency }}</span>
        <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
    </button>

    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50"
         style="display: none;">
        @foreach($currencies as $code => $currency)
            <form action="{{ route('currency.switch') }}" method="POST" class="block">
                @csrf
                <input type="hidden" name="currency" value="{{ $code }}">
                <button type="submit" 
                        class="w-full text-left px-4 py-2 hover:bg-gray-100 transition flex items-center gap-3 {{ $currentCurrency === $code ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700' }}">
                    <span class="text-lg">{{ $currency['symbol'] }}</span>
                    <span>{{ $code }} - {{ $currency['name'] }}</span>
                    @if($currentCurrency === $code)
                        <i class="fas fa-check ml-auto text-blue-600"></i>
                    @endif
                </button>
            </form>
        @endforeach
    </div>
</div>
