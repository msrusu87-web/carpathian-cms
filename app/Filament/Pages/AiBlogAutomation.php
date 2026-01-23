<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\CMS;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Post;
use App\Models\Category;

class AiBlogAutomation extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $cluster = CMS::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.pages.ai-blog-automation';

    public static function getNavigationLabel(): string
    {
        return __('AI Blog Automation');
    }

    public function getTitle(): string
    {
        return __('AI Blog Automation');
    }

    public ?array $data = [];
    public array $generatedArticles = [];
    public bool $isGenerating = false;
    public ?string $previewContent = null;

    protected string $sidecarUrl = 'http://127.0.0.1:3001';

    public function mount(): void
    {
        $this->form->fill([
            'source_type' => 'products',
            'article_count' => 3,
            'language' => app()->getLocale(),
            'include_images' => true,
            'publish_immediately' => false,
            'article_length' => 'medium',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Content Source'))
                    ->description(__('Choose what content to base the articles on'))
                    ->schema([
                        Select::make('source_type')
                            ->label(__('Source Type'))
                            ->options([
                                'products' => __('Shop Products'),
                                'categories' => __('Product Categories'),
                                'custom' => __('Custom Topics'),
                            ])
                            ->reactive()
                            ->required(),

                        Select::make('selected_products')
                            ->label(__('Select Products'))
                            ->multiple()
                            ->searchable()
                            ->options(fn () => Product::pluck('name', 'id'))
                            ->visible(fn ($get) => $get('source_type') === 'products')
                            ->helperText(__('Leave empty to use random products')),

                        Select::make('selected_category')
                            ->label(__('Select Category'))
                            ->options(fn () => Category::pluck('name', 'id'))
                            ->visible(fn ($get) => $get('source_type') === 'categories'),

                        Repeater::make('custom_topics')
                            ->label(__('Custom Topics'))
                            ->schema([
                                TextInput::make('topic')
                                    ->label(__('Topic'))
                                    ->required(),
                            ])
                            ->visible(fn ($get) => $get('source_type') === 'custom')
                            ->addActionLabel(__('Add Topic'))
                            ->minItems(1)
                            ->maxItems(10),
                    ])
                    ->columns(2),

                Section::make(__('Article Settings'))
                    ->schema([
                        TextInput::make('article_count')
                            ->label(__('Number of Articles'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->default(3)
                            ->required(),

                        Select::make('article_length')
                            ->label(__('Article Length'))
                            ->options([
                                'short' => __('Short (300-500 words)'),
                                'medium' => __('Medium (500-800 words)'),
                                'long' => __('Long (800-1200 words)'),
                            ])
                            ->default('medium')
                            ->required(),

                        Select::make('language')
                            ->label(__('Language'))
                            ->options([
                                'en' => 'English',
                                'ro' => 'Română',
                            ])
                            ->default(app()->getLocale())
                            ->required(),

                        Select::make('tone')
                            ->label(__('Writing Tone'))
                            ->options([
                                'professional' => __('Professional'),
                                'friendly' => __('Friendly & Casual'),
                                'informative' => __('Informative'),
                                'persuasive' => __('Persuasive/Marketing'),
                            ])
                            ->default('professional'),
                    ])
                    ->columns(2),

                Section::make(__('Publishing Options'))
                    ->schema([
                        Toggle::make('include_images')
                            ->label(__('Include Featured Images'))
                            ->helperText(__('Generate or select images for articles'))
                            ->default(true),

                        Toggle::make('include_product_links')
                            ->label(__('Include Product Links'))
                            ->helperText(__('Add links to related products in the article'))
                            ->default(true),

                        Toggle::make('publish_immediately')
                            ->label(__('Publish Immediately'))
                            ->helperText(__('Publish articles right away or save as drafts'))
                            ->default(false),

                        Select::make('category_id')
                            ->label(__('Blog Category'))
                            ->options(fn () => \App\Models\Category::where('type', 'blog')->pluck('name', 'id'))
                            ->placeholder(__('Select category for articles')),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function generateArticles(): void
    {
        $this->validate();
        $this->isGenerating = true;
        $this->generatedArticles = [];

        try {
            $formData = $this->form->getState();
            
            // Get source content
            $sourceContent = $this->getSourceContent($formData);
            
            foreach ($sourceContent as $index => $source) {
                if ($index >= $formData['article_count']) break;

                $article = $this->generateSingleArticle($source, $formData);
                if ($article) {
                    $this->generatedArticles[] = $article;
                }
            }

            Notification::make()
                ->success()
                ->title(__('Articles Generated'))
                ->body(__(':count articles generated successfully', ['count' => count($this->generatedArticles)]))
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('Generation Failed'))
                ->body($e->getMessage())
                ->send();
        } finally {
            $this->isGenerating = false;
        }
    }

    protected function getSourceContent(array $formData): array
    {
        switch ($formData['source_type']) {
            case 'products':
                if (!empty($formData['selected_products'])) {
                    return Product::whereIn('id', $formData['selected_products'])->get()->toArray();
                }
                return Product::inRandomOrder()->limit($formData['article_count'])->get()->toArray();

            case 'categories':
                if ($formData['selected_category']) {
                    $products = Product::where('category_id', $formData['selected_category'])
                        ->inRandomOrder()
                        ->limit($formData['article_count'])
                        ->get();
                    return $products->toArray();
                }
                return [];

            case 'custom':
                return collect($formData['custom_topics'] ?? [])->map(fn ($t) => ['name' => $t['topic'], 'type' => 'custom'])->toArray();

            default:
                return [];
        }
    }

    protected function generateSingleArticle(array $source, array $formData): ?array
    {
        $prompt = $this->buildPrompt($source, $formData);

        try {
            $response = Http::timeout(120)->post("{$this->sidecarUrl}/chat", [
                'message' => $prompt,
                'sessionId' => 'blog-automation-' . time(),
                'context' => [
                    'task' => 'blog_generation',
                    'format' => 'json',
                ],
            ]);

            if ($response->successful()) {
                $content = $response->json()['response']['content'] ?? '';
                
                // Try to parse JSON from response
                if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
                    $articleData = json_decode($matches[0], true);
                    if ($articleData) {
                        return [
                            'title' => $articleData['title'] ?? $source['name'] . ' - Article',
                            'content' => $articleData['content'] ?? $content,
                            'excerpt' => $articleData['excerpt'] ?? substr(strip_tags($articleData['content'] ?? $content), 0, 200),
                            'meta_description' => $articleData['meta_description'] ?? '',
                            'source' => $source,
                            'status' => 'generated',
                        ];
                    }
                }

                // Fallback - use raw content
                return [
                    'title' => $source['name'] ?? 'New Article',
                    'content' => $content,
                    'excerpt' => substr(strip_tags($content), 0, 200),
                    'source' => $source,
                    'status' => 'generated',
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Blog generation failed: ' . $e->getMessage());
        }

        return null;
    }

    protected function buildPrompt(array $source, array $formData): string
    {
        $length = match ($formData['article_length']) {
            'short' => '300-500',
            'long' => '800-1200',
            default => '500-800',
        };

        $tone = $formData['tone'] ?? 'professional';
        $lang = $formData['language'] === 'ro' ? 'Romanian' : 'English';
        $includeLinks = $formData['include_product_links'] ? 'Include links to the product.' : '';

        $productInfo = '';
        if (isset($source['name'])) {
            $productInfo = "Product: {$source['name']}";
            if (isset($source['description'])) {
                $productInfo .= "\nDescription: {$source['description']}";
            }
            if (isset($source['price'])) {
                $productInfo .= "\nPrice: {$source['price']}";
            }
        }

        return <<<PROMPT
Write a blog article in {$lang} about the following topic/product. 
The article should be {$length} words, written in a {$tone} tone.
{$includeLinks}

{$productInfo}

Return the response as JSON with these fields:
- title: SEO-optimized title
- content: Full HTML article content with proper headings (h2, h3), paragraphs, and bullet points
- excerpt: Short summary (max 200 chars)
- meta_description: SEO meta description (max 160 chars)

Make the article engaging, informative, and naturally promote the product/topic.
PROMPT;
    }

    public function publishArticle(int $index): void
    {
        if (!isset($this->generatedArticles[$index])) {
            return;
        }

        $article = $this->generatedArticles[$index];
        $formData = $this->form->getState();

        try {
            $post = Post::create([
                'title' => $article['title'],
                'slug' => \Str::slug($article['title']),
                'content' => $article['content'],
                'excerpt' => $article['excerpt'],
                'meta_description' => $article['meta_description'] ?? '',
                'category_id' => $formData['category_id'] ?? null,
                'status' => $formData['publish_immediately'] ? 'published' : 'draft',
                'author_id' => auth()->id(),
                'published_at' => $formData['publish_immediately'] ? now() : null,
            ]);

            $this->generatedArticles[$index]['status'] = 'published';
            $this->generatedArticles[$index]['post_id'] = $post->id;

            Notification::make()
                ->success()
                ->title(__('Article Published'))
                ->body(__('Article ":title" has been saved', ['title' => $article['title']]))
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('Publishing Failed'))
                ->body($e->getMessage())
                ->send();
        }
    }

    public function publishAllArticles(): void
    {
        foreach ($this->generatedArticles as $index => $article) {
            if ($article['status'] === 'generated') {
                $this->publishArticle($index);
            }
        }
    }

    public function previewArticle(int $index): void
    {
        if (isset($this->generatedArticles[$index])) {
            $this->previewContent = $this->generatedArticles[$index]['content'];
        }
    }

    public function closePreview(): void
    {
        $this->previewContent = null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label(__('Generate Articles'))
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->action('generateArticles')
                ->disabled(fn () => $this->isGenerating),

            Action::make('publish_all')
                ->label(__('Publish All'))
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->action('publishAllArticles')
                ->visible(fn () => count($this->generatedArticles) > 0)
                ->requiresConfirmation(),
        ];
    }
}
