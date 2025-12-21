<?php

namespace App\Filament\Actions;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use App\Services\GroqAiService;
use Illuminate\Support\Str;

class GenerateWithAiAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'generate_with_ai';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('Generate with AI'))
            ->icon('heroicon-o-sparkles')
            ->color('primary')
            ->form([
                Select::make('target_field')
                    ->label(__('Field to Generate'))
                    ->options([
                        'title' => __('Title'),
                        'description' => __('Description'),
                        'content' => __('Full Content'),
                        'meta_title' => __('SEO Title'),
                        'meta_description' => __('SEO Description'),
                        'meta_keywords' => __('SEO Keywords'),
                        'tags' => __('Tags'),
                    ])
                    ->required()
                    ->multiple()
                    ->default(['description']),

                Textarea::make('instructions')
                    ->label(__('AI Instructions'))
                    ->placeholder(__('What should the AI generate? (e.g., "Write a compelling product description focusing on benefits")'))
                    ->rows(3)
                    ->required(),

                Select::make('tone')
                    ->label(__('Tone'))
                    ->options([
                        'professional' => __('Professional'),
                        'casual' => __('Casual'),
                        'friendly' => __('Friendly'),
                        'formal' => __('Formal'),
                        'persuasive' => __('Persuasive'),
                        'technical' => __('Technical'),
                    ])
                    ->default('professional'),

                Select::make('length')
                    ->label(__('Length'))
                    ->options([
                        'short' => __('Short (1-2 paragraphs)'),
                        'medium' => __('Medium (3-5 paragraphs)'),
                        'long' => __('Long (6+ paragraphs)'),
                    ])
                    ->default('medium'),

                Toggle::make('use_existing_data')
                    ->label(__('Use Existing Data as Context'))
                    ->helperText(__('Include current title/description to regenerate/improve'))
                    ->default(true),
            ])
            ->action(function (array $data, $record) {
                try {
                    $groqService = new GroqAiService();
                    
                    $targetFields = $data['target_field'];
                    $results = [];

                    foreach ($targetFields as $field) {
                        $prompt = $this->buildFieldPrompt($field, $data, $record);
                        $response = $groqService->generateContent($prompt);

                        if ($response['success']) {
                            $results[$field] = $this->processFieldContent($field, $response['content']);
                        }
                    }

                    // Update record with generated content
                    foreach ($results as $field => $content) {
                        $record->{$field} = $content;
                    }
                    $record->save();

                    Notification::make()
                        ->success()
                        ->title(__('AI Generation Complete'))
                        ->body(sprintf(__('Generated %d field(s) successfully'), count($results)))
                        ->send();

                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('Generation Failed'))
                        ->body($e->getMessage())
                        ->send();
                }
            })
            ->modalWidth('lg')
            ->modalHeading(__('Generate Content with AI'))
            ->modalSubmitActionLabel(__('Generate'));
    }

    protected function buildFieldPrompt(string $field, array $data, $record): string
    {
        $context = "";
        
        if ($data['use_existing_data']) {
            $context .= "Current information:\n";
            if ($record->title) {
                $context .= "Title: {$record->title}\n";
            }
            if ($record->description) {
                $context .= "Description: " . strip_tags($record->description) . "\n";
            }
            if ($record->content) {
                $context .= "Content: " . Str::limit(strip_tags($record->content), 500) . "\n";
            }
            if (method_exists($record, 'getTranslations')) {
                $context .= "Language: " . app()->getLocale() . "\n";
            }
        }

        $prompts = [
            'title' => "Generate a compelling, SEO-friendly title. Maximum 60 characters. Return ONLY the title text.",
            'description' => "Generate a detailed description using HTML formatting (p, strong, em, ul, li). Be engaging and informative.",
            'content' => "Generate comprehensive content using HTML formatting (h2, h3, p, strong, em, ul, ol, li). Structure it well with clear sections.",
            'meta_title' => "Generate an SEO-optimized meta title. Maximum 60 characters. Return ONLY the title text.",
            'meta_description' => "Generate an SEO-optimized meta description. Maximum 160 characters. Include a call-to-action. Return ONLY the description text.",
            'meta_keywords' => "Generate 5-10 relevant SEO keywords, comma-separated. Return ONLY the keywords.",
            'tags' => "Generate 3-7 relevant tags, comma-separated. Return ONLY the tags.",
        ];

        $prompt = "You are a professional content writer and SEO expert.\n\n";
        $prompt .= "Task: {$prompts[$field]}\n\n";
        $prompt .= "Instructions: {$data['instructions']}\n\n";
        $prompt .= "Tone: {$data['tone']}\n";
        $prompt .= "Length: {$data['length']}\n\n";
        
        if ($context) {
            $prompt .= "{$context}\n\n";
        }

        $prompt .= "Generate the content for the '{$field}' field now:";

        return $prompt;
    }

    protected function processFieldContent(string $field, string $content): string
    {
        // Clean up the content
        $content = trim($content);
        
        // Remove markdown code blocks if present
        $content = preg_replace('/```html?\n?/', '', $content);
        $content = preg_replace('/```\n?/', '', $content);
        
        // For non-HTML fields, strip tags
        if (in_array($field, ['title', 'meta_title', 'meta_description', 'meta_keywords', 'tags'])) {
            $content = strip_tags($content);
        }

        return $content;
    }
}
