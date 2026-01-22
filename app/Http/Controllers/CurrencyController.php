<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(
        private CurrencyService $currencyService
    ) {}

    /**
     * Switch currency
     */
    public function switch(Request $request)
    {
        $currency = $request->input('currency');
        $this->currencyService->setCurrency($currency);

        return back()->with('success', __('messages.currency_updated'));
    }
}
