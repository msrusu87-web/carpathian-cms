<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuStyle extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'html_template',
        'css_template', 'config', 'preview_image', 'is_active'
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Render menu with data
    public function render(array $menuItems): string
    {
        $html = $this->html_template;
        
        // Replace variables
        $html = str_replace('{{ menu_items }}', $this->renderMenuItems($menuItems), $html);
        
        return $html;
    }

    protected function renderMenuItems(array $items): string
    {
        $html = '';
        foreach ($items as $item) {
            $html .= sprintf(
                '<li><a href="%s" class="menu-item">%s</a></li>',
                $item['url'],
                $item['label']
            );
        }
        return $html;
    }
}
