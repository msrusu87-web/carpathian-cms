<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plugin extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'version', 'author',
        'author_url', 'code', 'config', 'hooks', 'is_active',
        'ai_generated', 'ai_generation_id', 'dependencies', 'icon'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ai_generated' => 'boolean',
        'config' => 'array',
        'hooks' => 'array',
        'dependencies' => 'array'
    ];

    public function aiGeneration(): BelongsTo
    {
        return $this->belongsTo(AiGeneration::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function execute(string $hook, mixed $content, array $context = []): mixed
    {
        if (!$this->is_active || !isset($this->hooks[$hook])) {
            return $content;
        }

        try {
            eval($this->code);
            if (function_exists($hook)) {
                return call_user_func($hook, $content, $context);
            }
        } catch (\Exception $e) {
            \Log::error("Plugin {$this->name} error: " . $e->getMessage());
        }

        return $content;
    }
}
