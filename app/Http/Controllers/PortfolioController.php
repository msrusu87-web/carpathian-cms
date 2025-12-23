<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::active()->ordered()->get();
        return view('frontend.portfolio.index', compact('portfolios'));
    }

    public function show($slug)
    {
        $portfolio = Portfolio::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $relatedPortfolios = Portfolio::active()
            ->where('id', '!=', $portfolio->id)
            ->where('category', $portfolio->category)
            ->limit(3)
            ->get();
        
        return view('frontend.portfolio.show', compact('portfolio', 'relatedPortfolios'));
    }
}
