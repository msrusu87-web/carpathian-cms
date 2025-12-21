<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | AI Content Generation Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for AI-powered content generation
    | using Groq AI (Llama 3.3 70B Versatile)
    |
    */

    'enabled' => env('AI_GENERATION_ENABLED', true),

    'provider' => env('AI_PROVIDER', 'groq'), // groq, openai, anthropic

    'groq' => [
        'api_key' => env('GROQ_API_KEY', ''),
        'base_url' => 'https://api.groq.com/openai/v1/',
        'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
        'timeout' => 120, // seconds
        'max_tokens' => 8000,
        'temperature' => 0.7,
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY', ''),
        'model' => env('OPENAI_MODEL', 'gpt-4-turbo-preview'),
        'timeout' => 120,
        'max_tokens' => 4000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Generation Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'tone' => 'professional',
        'length' => 'medium',
        'use_existing_data' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Field-Specific Prompts
    |--------------------------------------------------------------------------
    */

    'prompts' => [
        'product' => [
            'name' => 'Generate a compelling, SEO-friendly product name. Maximum 100 characters. Return ONLY the product name.',
            'description' => 'Generate a detailed product description using HTML formatting (p, strong, em, ul, li). Be persuasive and focus on benefits. Include key features.',
            'content' => 'Generate comprehensive product content using HTML formatting (h2, h3, p, strong, em, ul, ol, li). Include: features, benefits, specifications, usage instructions. Structure it well with clear sections.',
            'meta_title' => 'Generate an SEO-optimized meta title for this product. Maximum 60 characters. Include product name and key benefit. Return ONLY the title text.',
            'meta_description' => 'Generate an SEO-optimized meta description for this product. Maximum 160 characters. Include key benefits and a call-to-action. Return ONLY the description text.',
            'meta_keywords' => 'Generate 5-10 relevant SEO keywords for this product, comma-separated. Focus on product type, features, and benefits. Return ONLY the keywords.',
        ],

        'page' => [
            'title' => 'Generate a clear, compelling page title. Maximum 70 characters. Return ONLY the title text.',
            'content' => 'Generate comprehensive page content using HTML formatting (h2, h3, p, strong, em, ul, ol, li). Structure it well with clear sections and engaging copy.',
            'meta_title' => 'Generate an SEO-optimized meta title. Maximum 60 characters. Return ONLY the title text.',
            'meta_description' => 'Generate an SEO-optimized meta description. Maximum 160 characters. Include a call-to-action. Return ONLY the description text.',
            'meta_keywords' => 'Generate 5-10 relevant SEO keywords, comma-separated. Return ONLY the keywords.',
        ],

        'post' => [
            'title' => 'Generate a compelling, SEO-friendly blog post title. Maximum 70 characters. Make it engaging and clickable. Return ONLY the title text.',
            'excerpt' => 'Generate a compelling excerpt/summary (150-200 characters). Hook the reader to continue reading. Return ONLY the excerpt text.',
            'content' => 'Generate comprehensive blog post content using HTML formatting (h2, h3, p, strong, em, ul, ol, li, blockquote). Include: introduction, main points with examples, actionable tips, and conclusion. Make it engaging and valuable.',
            'meta_title' => 'Generate an SEO-optimized meta title. Maximum 60 characters. Include main keyword. Return ONLY the title text.',
            'meta_description' => 'Generate an SEO-optimized meta description. Maximum 160 characters. Include main keyword and call-to-action. Return ONLY the description text.',
            'meta_keywords' => 'Generate 5-10 relevant SEO keywords for this blog post, comma-separated. Focus on main topics and related terms. Return ONLY the keywords.',
        ],

        'widget' => [
            'title' => 'Generate a compelling widget title. Maximum 50 characters. Make it attention-grabbing. Return ONLY the title text.',
            'content' => 'Generate engaging widget content. Keep it concise and actionable. Use HTML if needed for formatting.',
            'button1_text' => 'Generate compelling call-to-action button text. 2-4 words. Action-oriented. Return ONLY the button text.',
            'button2_text' => 'Generate alternative call-to-action button text. 2-4 words. Action-oriented. Return ONLY the button text.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tone Descriptions
    |--------------------------------------------------------------------------
    */

    'tones' => [
        'professional' => 'Use formal, business-appropriate language with expertise.',
        'persuasive' => 'Focus on benefits, use compelling language to drive action.',
        'friendly' => 'Warm, conversational tone that builds connection.',
        'technical' => 'Precise, detailed with technical terminology.',
        'casual' => 'Relaxed, informal language suitable for everyday conversation.',
        'formal' => 'Very professional, serious tone for official content.',
        'inspiring' => 'Motivational, uplifting language that energizes.',
        'urgent' => 'Create sense of urgency with action-oriented language.',
        'informative' => 'Clear, educational focus on facts and information.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Length Guidelines
    |--------------------------------------------------------------------------
    */

    'lengths' => [
        'short' => [
            'words' => '50-150',
            'sentences' => '1-2 paragraphs',
            'description' => 'Brief, concise content',
        ],
        'medium' => [
            'words' => '300-500',
            'sentences' => '3-5 paragraphs',
            'description' => 'Standard content length',
        ],
        'long' => [
            'words' => '800-1500',
            'sentences' => '6+ paragraphs',
            'description' => 'Comprehensive, detailed content',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */

    'rate_limits' => [
        'per_minute' => 60,
        'per_hour' => 500,
        'per_day' => 5000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    */

    'cache' => [
        'enabled' => env('AI_CACHE_ENABLED', false),
        'ttl' => 3600, // 1 hour
        'prefix' => 'ai_gen_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'enabled' => env('AI_LOGGING_ENABLED', true),
        'channel' => 'single',
        'log_prompts' => true,
        'log_responses' => true,
        'log_errors' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Languages
    |--------------------------------------------------------------------------
    */

    'languages' => [
        'en' => 'English',
        'ro' => 'Romanian (Română)',
        'de' => 'German (Deutsch)',
        'fr' => 'French (Français)',
        'es' => 'Spanish (Español)',
        'it' => 'Italian (Italiano)',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTML Sanitization
    |--------------------------------------------------------------------------
    */

    'sanitization' => [
        'enabled' => true,
        'allowed_tags' => ['p', 'br', 'strong', 'em', 'u', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'li', 'a', 'blockquote', 'code', 'pre'],
        'allowed_attributes' => ['href', 'title', 'class', 'id'],
    ],

];
