# 🔧 AI Content Generator - Technical Documentation

## Architecture Overview

The AI Content Generator is integrated directly into Filament edit pages using Header Actions. It uses Groq AI (Llama 3.3 70B) for fast, high-quality content generation.

## Components

### 1. Core Service: `GroqAiService`
**Location**: `app/Services/GroqAiService.php`

```php
// Generate content with Groq AI
$groqService = new GroqAiService();
$response = $groqService->generateContent($prompt);

// Response format:
[
    'success' => true,
    'content' => 'Generated HTML content...',
    'tokens_used' => 1500,
]
```

**Key Methods**:
- `generateContent($prompt)`: Main content generation
- `generateTemplate($description, $options)`: Template generation
- `generatePlugin($description, $requirements)`: Plugin generation
- `makeRequest($prompt)`: Low-level API call

**Configuration**:
```env
GROQ_API_KEY=your_groq_api_key_here
```

### 2. Integration Points

#### Products: `EditProduct.php`
**Location**: `app/Filament/Resources/ProductResource/Pages/EditProduct.php`

**Fields Supported**:
- `name` (translatable)
- `description` (translatable, HTML)
- `content` (translatable, HTML)
- `meta_title`
- `meta_description`
- `meta_keywords`

**Translation Support**: Yes (Spatie Translatable)

#### Pages: `EditPage.php`
**Location**: `app/Filament/Resources/PageResource/Pages/EditPage.php`

**Fields Supported**:
- `title` (translatable)
- `content` (translatable, HTML)
- `meta_title`
- `meta_description`
- `meta_keywords`

#### Posts: `EditPost.php`
**Location**: `app/Filament/Resources/PostResource/Pages/EditPost.php`

**Fields Supported**:
- `title` (translatable)
- `excerpt` (translatable)
- `content` (translatable, HTML)
- `meta_title`
- `meta_description`
- `meta_keywords`

#### Widgets: `EditWidget.php`
**Location**: `app/Filament/Resources/WidgetResource/Pages/EditWidget.php`

**Fields Supported**:
- `title` (translatable)
- `content` (translatable, HTML)
- `button1_text`
- `button2_text`

### 3. Standalone Page: `AiContentWriter`
**Location**: `app/Filament/Pages/AiContentWriter.php`

Separate page for generating complete content that can be saved as new posts/pages.

## Implementation Details

### Header Action Structure

```php
Actions\Action::make('generate_with_ai')
    ->label(__('Generate with AI'))
    ->icon('heroicon-o-sparkles')
    ->color('primary')
    ->form([
        // Form fields for user input
    ])
    ->action(function (array $data) {
        // Generation logic
    })
    ->modalWidth('lg')
```

### Form Schema

```php
Select::make('target_fields')
    ->label(__('Fields to Generate'))
    ->options([...])
    ->required()
    ->multiple()
    ->default(['description']),

Textarea::make('instructions')
    ->label(__('AI Instructions'))
    ->rows(3)
    ->required(),

Select::make('tone')
    ->options([
        'professional' => 'Professional',
        'persuasive' => 'Persuasive',
        // ...
    ])
    ->default('professional'),

Select::make('length')
    ->options([
        'short' => 'Short (1-2 paragraphs)',
        'medium' => 'Medium (3-5 paragraphs)',
        'long' => 'Long (6+ paragraphs)',
    ])
    ->default('medium'),

Toggle::make('use_existing_data')
    ->default(true),
```

### Generation Flow

```php
protected function action(array $data) 
{
    $groqService = new GroqAiService();
    $record = $this->record;
    $targetFields = $data['target_fields'];
    $locale = app()->getLocale();
    
    foreach ($targetFields as $field) {
        // 1. Build field-specific prompt
        $prompt = $this->buildFieldPrompt($field, $data, $record, $locale);
        
        // 2. Call AI service
        $response = $groqService->generateContent($prompt);
        
        if ($response['success']) {
            // 3. Process response
            $content = $this->processFieldContent($field, $response['content']);
            
            // 4. Save to record
            if (in_array($field, ['name', 'description', 'content'])) {
                $record->setTranslation($field, $locale, $content);
            } else {
                $record->{$field} = $content;
            }
        }
    }
    
    $record->save();
    
    // 5. Refresh page
    redirect()->to(static::getUrl(['record' => $record]));
}
```

### Prompt Building

```php
protected function buildFieldPrompt(string $field, array $data, $record, string $locale): string
{
    $context = "";
    
    // Include existing data if requested
    if ($data['use_existing_data']) {
        $context .= "Current information:\n";
        $context .= "Title: {$record->getTranslation('title', $locale)}\n";
        // ... more context
    }
    
    // Field-specific prompts
    $prompts = [
        'description' => "Generate a detailed description using HTML...",
        'meta_title' => "Generate SEO-optimized meta title. Max 60 chars...",
        // ... more prompts
    ];
    
    // Build final prompt
    $prompt = "You are a professional content writer.\n\n";
    $prompt .= "Task: {$prompts[$field]}\n\n";
    $prompt .= "Instructions: {$data['instructions']}\n\n";
    $prompt .= "Tone: {$data['tone']}\n";
    $prompt .= "Length: {$data['length']}\n\n";
    $prompt .= $context . "\n\n";
    $prompt .= "Generate the content now:";
    
    return $prompt;
}
```

### Content Processing

```php
protected function processFieldContent(string $field, string $content): string
{
    // Trim whitespace
    $content = trim($content);
    
    // Remove markdown code blocks
    $content = preg_replace('/```html?\n?/', '', $content);
    $content = preg_replace('/```\n?/', '', $content);
    
    // Strip HTML for non-HTML fields
    $plainTextFields = ['title', 'meta_title', 'meta_description', 'meta_keywords'];
    if (in_array($field, $plainTextFields)) {
        $content = strip_tags($content);
    }
    
    return $content;
}
```

## Database Schema

### `ai_generations` Table
Tracks all AI generation requests for monitoring and debugging.

```sql
CREATE TABLE ai_generations (
    id BIGINT PRIMARY KEY,
    type VARCHAR(255),          -- 'content', 'template', 'plugin'
    prompt TEXT,                 -- Full prompt sent to AI
    response TEXT,               -- AI response
    parameters JSON,             -- Request parameters
    model VARCHAR(255),          -- AI model used
    user_id BIGINT,             -- User who requested
    status VARCHAR(255),         -- 'pending', 'completed', 'failed'
    error_message TEXT,          -- Error if failed
    tokens_used INT,             -- Tokens consumed
    generation_time INT,         -- Time in milliseconds
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## API Integration

### Groq AI API

**Endpoint**: `https://api.groq.com/openai/v1/chat/completions`

**Request Format**:
```php
[
    'model' => 'llama-3.3-70b-versatile',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'You are a professional content writer.'
        ],
        [
            'role' => 'user',
            'content' => $prompt
        ]
    ],
    'temperature' => 0.7,
    'max_tokens' => 8000,
    'top_p' => 1,
    'stream' => false
]
```

**Response Format**:
```json
{
  "id": "chatcmpl-xxx",
  "object": "chat.completion",
  "created": 1234567890,
  "model": "llama-3.3-70b-versatile",
  "choices": [
    {
      "index": 0,
      "message": {
        "role": "assistant",
        "content": "Generated content here..."
      },
      "finish_reason": "stop"
    }
  ],
  "usage": {
    "prompt_tokens": 50,
    "completion_tokens": 200,
    "total_tokens": 250
  }
}
```

## Translation Support

### Spatie Translatable Integration

```php
// Save to specific locale
$record->setTranslation($field, $locale, $content);

// Get from specific locale
$record->getTranslation($field, $locale);

// Available locales
['en', 'ro', 'de', 'fr', 'es', 'it']
```

### Multi-language Generation
1. User selects language in Filament LocaleSwitcher
2. `app()->getLocale()` returns active locale
3. AI generates content in that language
4. Content saved to translatable field for that locale

## Security

### Input Sanitization
- User instructions are escaped in prompts
- HTML output is validated
- Only allowed HTML tags in TinyEditor fields

### API Key Protection
```php
// .env
GROQ_API_KEY=gsk_xxx

// Never expose in frontend
protected string $apiKey;

public function __construct()
{
    $this->apiKey = env('GROQ_API_KEY', '');
}
```

### Rate Limiting
- Groq API: ~60 requests/minute
- Laravel rate limiting can be added:

```php
RateLimiter::for('ai-generation', function (Request $request) {
    return Limit::perMinute(10)->by($request->user()->id);
});
```

## Error Handling

```php
try {
    $response = $groqService->generateContent($prompt);
    
    if ($response['success']) {
        // Success logic
    } else {
        throw new \Exception($response['error']);
    }
} catch (\Exception $e) {
    Notification::make()
        ->danger()
        ->title(__('Generation Failed'))
        ->body($e->getMessage())
        ->send();
        
    Log::error('AI Generation Failed', [
        'error' => $e->getMessage(),
        'user_id' => auth()->id(),
        'field' => $field,
    ]);
}
```

## Performance Optimization

### Async Processing (Optional)
```php
use Illuminate\Support\Facades\Queue;

// Dispatch job
GenerateContentJob::dispatch($field, $data, $record->id);

// In job
public function handle()
{
    $groqService = new GroqAiService();
    $response = $groqService->generateContent($prompt);
    // ... update record
}
```

### Caching (Optional)
```php
$cacheKey = "ai_gen_{$field}_{$locale}_" . md5($prompt);

$content = Cache::remember($cacheKey, 3600, function () use ($groqService, $prompt) {
    return $groqService->generateContent($prompt);
});
```

## Testing

### Unit Tests
```php
public function test_ai_generation_for_product()
{
    $product = Product::factory()->create();
    
    $data = [
        'target_fields' => ['description'],
        'instructions' => 'Test description',
        'tone' => 'professional',
        'length' => 'short',
        'use_existing_data' => false,
    ];
    
    $this->actingAs($user)
        ->post(route('filament.admin.resources.products.edit', $product), $data);
        
    $this->assertNotNull($product->fresh()->description);
}
```

### Manual Testing Checklist
- [ ] Generate single field
- [ ] Generate multiple fields
- [ ] Test each resource (Product, Page, Post, Widget)
- [ ] Test translations (all 6 languages)
- [ ] Test with existing data ON/OFF
- [ ] Test different tones
- [ ] Test different lengths
- [ ] Verify HTML output is valid
- [ ] Verify SEO fields are plain text
- [ ] Test error handling (invalid API key)

## Extending the System

### Adding New Resource

1. **Edit the Resource's Edit page**:

```php
// app/Filament/Resources/YourResource/Pages/EditYourResource.php

protected function getHeaderActions(): array
{
    return [
        Actions\Action::make('generate_with_ai')
            ->label(__('Generate with AI'))
            ->icon('heroicon-o-sparkles')
            ->color('primary')
            ->form([
                // Your form fields
            ])
            ->action(function (array $data) {
                // Your generation logic
            }),
        // ... other actions
    ];
}
```

2. **Add field-specific prompts**:

```php
protected function buildFieldPrompt(string $field, array $data, $record, string $locale): string
{
    $prompts = [
        'your_field' => "Generate content for your_field...",
    ];
    // ... rest of logic
}
```

3. **Handle translations if needed**:

```php
if (in_array($field, ['translatable_field'])) {
    $record->setTranslation($field, $locale, $content);
} else {
    $record->{$field} = $content;
}
```

### Adding New AI Model

```php
// In GroqAiService
protected string $model = 'llama-3.3-70b-versatile';

// Change to:
protected string $model = 'mixtral-8x7b-32768'; // or other Groq models
```

### Custom Prompts

Create prompt templates in config:

```php
// config/ai.php
return [
    'prompts' => [
        'ecommerce_product' => "You are an e-commerce copywriter...",
        'blog_seo' => "You are an SEO expert...",
        'technical_docs' => "You are a technical writer...",
    ],
];

// Usage
$basePrompt = config('ai.prompts.ecommerce_product');
```

## Monitoring & Analytics

### Check AI Generations

```php
// In Filament
Admin → AI → AI Generations

// Or query directly
$generations = AiGeneration::where('user_id', auth()->id())
    ->where('status', 'completed')
    ->latest()
    ->get();
```

### Metrics to Track
- Total generations per day
- Success/failure rate
- Average generation time
- Tokens used (cost tracking)
- Most generated fields
- User adoption rate

### Dashboard Widget

```php
// app/Filament/Widgets/AiGenerationStats.php
protected function getStats(): array
{
    return [
        Stat::make('Total Generations', AiGeneration::count()),
        Stat::make('Success Rate', $successRate . '%'),
        Stat::make('Avg Time', $avgTime . 'ms'),
    ];
}
```

## Troubleshooting

### Issue: API Key Not Working
```bash
# Check .env
cat .env | grep GROQ_API_KEY

# Clear config cache
php artisan config:clear
```

### Issue: Timeout Errors
```php
// Increase timeout in GroqAiService
'timeout' => 180, // 3 minutes
```

### Issue: HTML Not Rendering
```php
// Check TinyEditor is used for field
TinyEditor::make('description')
    ->profile('full')
```

### Issue: Translations Not Saving
```php
// Check model has Translatable trait
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;
    
    public $translatable = ['name', 'description', 'content'];
}
```

## Best Practices

1. **Always validate AI output** before displaying to users
2. **Set reasonable timeouts** (120s recommended)
3. **Log all generations** for debugging and improvement
4. **Use specific prompts** for better results
5. **Handle errors gracefully** with user-friendly messages
6. **Cache repetitive requests** to save API calls
7. **Monitor token usage** for cost management
8. **Test across all languages** before deploying

## Future Enhancements

- [ ] Add image generation (DALL-E integration)
- [ ] Batch generation for multiple products
- [ ] AI-powered SEO analysis
- [ ] Content improvement suggestions
- [ ] A/B testing for AI-generated content
- [ ] Custom fine-tuned models for specific industries
- [ ] Voice-to-text for instructions
- [ ] Real-time preview during generation

---

**Technical Support**: dev@carphatian.ro  
**API Documentation**: https://console.groq.com/docs  
**Version**: 1.0.0
