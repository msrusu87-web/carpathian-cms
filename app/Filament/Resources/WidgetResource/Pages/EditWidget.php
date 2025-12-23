<?php

namespace App\Filament\Resources\WidgetResource\Pages;

use App\Filament\Resources\WidgetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use App\Services\GroqAiService;
use Illuminate\Support\Str;

class EditWidget extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = WidgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generate_with_ai')
                ->label(__('Generate with AI'))
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->form([
                    Select::make('target_fields')
                        ->label(__('Fields to Generate'))
                        ->options([
                            'title' => __('Widget Title'),
                            'content' => __('Widget Content'),
                            'button1_text' => __('Button 1 Text'),
                            'button2_text' => __('Button 2 Text'),
                        ])
                        ->required()
                        ->multiple()
                        ->default(['content']),

                    Textarea::make('instructions')
                        ->label(__('AI Instructions'))
                        ->placeholder(__('What should the widget display? (e.g., "Create a welcome message for homepage visitors")'))
                        ->rows(3)
                        ->required(),

                    Select::make('tone')
                        ->label(__('Tone'))
                        ->options([
                            'friendly' => __('Friendly'),
                            'professional' => __('Professional'),
                            'casual' => __('Casual'),
                            'inspiring' => __('Inspiring'),
                            'urgent' => __('Urgent'),
                        ])
                        ->default('friendly'),

                    Select::make('length')
                        ->label(__('Length'))
                        ->options([
                            'short' => __('Short (1-2 sentences)'),
                            'medium' => __('Medium (2-4 sentences)'),
                            'long' => __('Long (paragraph)'),
                        ])
                        ->default('short'),

                    Toggle::make('use_existing_data')
                        ->label(__('Use Existing Data as Context'))
                        ->helperText(__('Include current widget info to regenerate/improve'))
                        ->default(true),
                ])
                ->action(function (array $data) {
                    try {
                        $groqService = new GroqAiService();
                        $record = $this->record;
                        $targetFields = $data['target_fields'];
                        $results = [];
                        $locale = app()->getLocale();

                        foreach ($targetFields as $field) {
                            $prompt = $this->buildFieldPrompt($field, $data, $record, $locale);
                            $response = $groqService->generateContent($prompt);

                            if ($response['success']) {
                                $content = $this->processFieldContent($field, $response['content']);
                                $results[$field] = $content;
                                
                                if (in_array($field, ['title', 'content'])) {
                                    $record->setTranslation($field, $locale, $content);
                                } else {
                                    $record->{$field} = $content;
                                }
                            }
                        }
                        
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title(__('AI Generation Complete'))
                            ->body(sprintf(__('Generated %d field(s) successfully. Page will refresh.'), count($results)))
                            ->send();

                        redirect()->to(static::getUrl(['record' => $record]));

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
                ->modalSubmitActionLabel(__('Generate')),
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function buildFieldPrompt(string $field, array $data, $record, string $locale): string
    {
        $context = "";
        
        if ($data['use_existing_data']) {
            $context .= "Current widget information:\n";
            if ($record->getTranslation('title', $locale)) {
                $context .= "Title: {$record->getTranslation('title', $locale)}\n";
            }
            if ($record->getTranslation('content', $locale)) {
                $context .= "Content: {$record->getTranslation('content', $locale)}\n";
            }
            if ($record->type) {
                $context .= "Widget Type: {$record->type}\n";
            }
            $context .= "Language: {$locale}\n";
        }

        $prompts = [
            'title' => "Generate a compelling widget title. Maximum 50 characters. Make it attention-grabbing. Return ONLY the title text.",
            'content' => "Generate engaging widget content. Keep it concise and actionable. Use HTML if needed for formatting.",
            'button1_text' => "Generate compelling call-to-action button text. 2-4 words. Action-oriented. Return ONLY the button text.",
            'button2_text' => "Generate alternative call-to-action button text. 2-4 words. Action-oriented. Return ONLY the button text.",
        ];

        $prompt = "You are a professional UX copywriter and conversion expert.\n\n";
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
        $content = trim($content);
        $content = preg_replace('/```html?\n?/', '', $content);
        $content = preg_replace('/```\n?/', '', $content);
        
        if (in_array($field, ['title', 'button1_text', 'button2_text'])) {
            $content = strip_tags($content);
        }

        return $content;
    }
}
