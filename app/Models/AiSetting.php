<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    protected $fillable = [
        'provider',
        'name',
        'api_key',
        'model',
        'config',
        'is_active',
        'is_default',
        'order'
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'order' => 'integer'
    ];

    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('order')->get();
    }

    public static function getDefault()
    {
        return self::where('is_default', true)->where('is_active', true)->first();
    }

    public static function getProviders(): array
    {
        return [
            'groq' => 'Groq',
            'chatgpt' => 'ChatGPT (OpenAI)',
            'gemini' => 'Google Gemini',
            'claude' => 'Anthropic Claude',
        ];
    }
}
