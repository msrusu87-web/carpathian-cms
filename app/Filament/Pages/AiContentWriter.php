<?php

namespace App\Filament\Pages;
use App\Filament\Clusters\AI;

use Filament\Pages\Page;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Post;
use App\Models\Page as PageModel;
use App\Models\Category;
use Illuminate\Support\Str;

class AiContentWriter extends Page implements HasForms
{
    protected static ?string $cluster = AI::class;

    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.ai-content-writer';

    protected string $sidecarUrl = 'http://127.0.0.1:3001';


    public static function getNavigationLabel(): string
    {
        return __('AI Content Writer');
    }

    public function getTitle(): string
    {
        return __('AI Content Writer');
    }

    public ?array $data = [];
    public ?string $generatedContent = null;
    public bool $isGenerating = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('content_type')
                    ->label(__('Content Type'))
                    ->options([
                        'blog_post' => __('Blog Post'),
                        'page' => __('Page'),
                        'product_description' => __('Product Description'),
                    ])
                    ->required()
                    ->default('blog_post')
                    ->live(),

                TextInput::make('title')
                    ->label(__('Content Title'))
                    ->required()
                    ->placeholder(__('e.g., "10 Tips for Better SEO"'))
                    ->maxLength(255),

                Textarea::make('description')
                    ->label(__('Content Description / Instructions'))
                    ->placeholder(__('Describe what you want the AI to write about...'))
                    ->rows(4)
                    ->required(),

                Select::make('tone')
                    ->label(__('Tone'))
                    ->options([
                        'professional' => __('Professional'),
                        'casual' => __('Casual'),
                        'friendly' => __('Friendly'),
                        'formal' => __('Formal'),
                        'humorous' => __('Humorous'),
                    ])
                    ->default('professional'),

                Select::make('length')
                    ->label(__('Content Length'))
                    ->options([
                        'short' => __('Short (300-500 words)'),
                        'medium' => __('Medium (500-1000 words)'),
                        'long' => __('Long (1000-2000 words)'),
                    ])
                    ->default('medium'),

                Select::make('category_id')
                    ->label(__('Category (for Blog Posts)'))
                    ->options(Category::pluck('name', 'id'))
                    ->visible(fn ($get) => $get('content_type') === 'blog_post'),
            ])
            ->statePath('data');
    }

    public function generate()
    {
        $this->validate();
        
        $this->isGenerating = true;
        $this->generatedContent = null;

        try {
            $data = $this->form->getState();
            
            // Build the prompt
            $prompt = $this->buildPrompt($data);
            
            // Generate content using Copilot sidecar
            $response = Http::timeout(120)->post("{$this->sidecarUrl}/chat", [
                'message' => $prompt,
                'sessionId' => 'content-writer-' . auth()->id() . '-' . time(),
                'context' => [
                    'task' => 'content_generation',
                    'user_id' => auth()->id(),
                    'content_type' => $data['content_type'],
                ],
            ]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                $this->generatedContent = $responseData['response']['content'] ?? '';
                
                // Clean up the content - remove markdown code blocks if present
                $this->generatedContent = preg_replace('/^```html?\s*/i', '', $this->generatedContent);
                $this->generatedContent = preg_replace('/```\s*$/', '', $this->generatedContent);
                $this->generatedContent = trim($this->generatedContent);
                
                Notification::make()
                    ->success()
                    ->title(__('Content Generated!'))
                    ->body(__('Your content has been generated successfully.'))
                    ->send();
            } else {
                Notification::make()
                    ->danger()
                    ->title(__('Generation Failed'))
                    ->body('Failed to generate content: ' . ($response->json()['error'] ?? 'Unknown error'))
                    ->send();
            }
            
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('Error'))
                ->body($e->getMessage())
                ->send();
        } finally {
            $this->isGenerating = false;
        }
    }

    public function saveAsPost()
    {
        if (!$this->generatedContent) {
            Notification::make()
                ->warning()
                ->title(__('No Content'))
                ->body(__('Please generate content first'))
                ->send();
            return;
        }

        $data = $this->form->getState();

        $post = Post::create([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'content' => $this->generatedContent,
            'excerpt' => Str::limit(strip_tags($this->generatedContent), 200),
            'status' => 'draft',
            'category_id' => $data['category_id'] ?? null,
            'user_id' => auth()->id(),
            'meta_title' => $data['title'],
            'meta_description' => Str::limit(strip_tags($this->generatedContent), 160),
        ]);

        Notification::make()
            ->success()
            ->title(__('Post Created!'))
            ->body(__('Your post has been saved as a draft.'))
            ->send();

        $this->redirect(route('filament.admin.resources.posts.edit', ['record' => $post->id]));
    }

    public function saveAsPage()
    {
        if (!$this->generatedContent) {
            Notification::make()
                ->warning()
                ->title(__('No Content'))
                ->body(__('Please generate content first'))
                ->send();
            return;
        }

        $data = $this->form->getState();

        $page = PageModel::create([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'content' => $this->generatedContent,
            'status' => 'draft',
            'user_id' => auth()->id(),
            'meta_title' => $data['title'],
            'meta_description' => Str::limit(strip_tags($this->generatedContent), 160),
        ]);

        Notification::make()
            ->success()
            ->title(__('Page Created!'))
            ->body(__('Your page has been saved as a draft.'))
            ->send();

        $this->redirect(route('filament.admin.resources.pages.edit', ['record' => $page->id]));
    }

    protected function buildPrompt(array $data): string
    {
        $lengthGuide = [
            'short' => '300-500 words',
            'medium' => '500-1000 words',
            'long' => '1000-2000 words',
        ];

        $prompt = "You are a professional content writer. ";
        $prompt .= "Write a {$data['tone']} {$data['content_type']} about: {$data['title']}.\n\n";
        $prompt .= "Instructions: {$data['description']}\n\n";
        $prompt .= "Length: {$lengthGuide[$data['length']]}\n\n";
        $prompt .= "Format: Use HTML tags for formatting (h2, h3, p, ul, li, strong, em). ";
        $prompt .= "Make it SEO-friendly, engaging, and well-structured with clear headings.\n\n";
        $prompt .= "Return ONLY the HTML content, no markdown, no explanations, no meta information.";

        return $prompt;
    }
}
