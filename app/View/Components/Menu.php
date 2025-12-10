<?php

namespace App\View\Components;

use App\Models\Menu as MenuModel;
use Illuminate\View\Component;

class Menu extends Component
{
    public $items;
    public $location;

    public function __construct($location = 'top')
    {
        $this->location = $location;
        
        $menu = MenuModel::with(['items.children', 'items.page'])
            ->where('location', $location)
            ->where('is_active', true)
            ->first();
        
        $this->items = $menu ? $menu->items : collect();
    }

    public function render()
    {
        return view('components.menu');
    }
}
