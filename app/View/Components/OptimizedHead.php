<?php

namespace App\View\Components;

use Illuminate\View\Component;

class OptimizedHead extends Component
{
    public $title;
    public $description;
    public $keywords;
    public $image;
    public $canonicalUrl;
    public $locale;
    public $noIndex;

    public function __construct(
        $title = null,
        $description = null,
        $keywords = [],
        $image = null,
        $canonicalUrl = null,
        $locale = null,
        $noIndex = false
    ) {
        $this->title = $title ?? config('app.name');
        $this->description = $description;
        $this->keywords = is_array($keywords) ? $keywords : [];
        $this->image = $image ?? asset('images/carphatian-og-image.jpg');
        $this->canonicalUrl = $canonicalUrl ?? url()->current();
        $this->locale = $locale ?? app()->getLocale();
        $this->noIndex = $noIndex;
    }

    public function render()
    {
        return view('components.optimized-head');
    }
}
