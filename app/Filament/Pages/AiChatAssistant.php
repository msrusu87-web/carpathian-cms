<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\CMS;
use Filament\Pages\Page;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;

class AiChatAssistant extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $cluster = CMS::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?int $navigationSort = 0;
    protected static string $view = 'filament.pages.ai-chat-assistant';

    public static function getNavigationLabel(): string
    {
        return __('AI Chat Assistant');
    }

    public function getTitle(): string
    {
        return __('AI Chat Assistant');
    }

    public ?string $message = '';
    public array $conversation = [];
    public ?string $sessionId = null;
    public bool $isProcessing = false;
    public ?array $lastToolCalls = null;

    protected string $sidecarUrl = 'http://127.0.0.1:3001';

    public function mount(): void
    {
        $this->sessionId = session()->getId();
        $this->conversation = [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('message')
                    ->label('')
                    ->placeholder(__('Ask me anything about managing your CMS... e.g., "Create a new product called Widget Pro for $99"'))
                    ->rows(3)
                    ->required()
                    ->extraAttributes([
                        'x-on:keydown.enter.prevent' => '$wire.sendMessage()',
                    ]),
            ])
            ->statePath('data');
    }

    public function sendMessage(): void
    {
        if (empty(trim($this->message))) {
            return;
        }

        $userMessage = $this->message;
        $this->message = '';
        $this->isProcessing = true;
        $this->lastToolCalls = null;

        // Add user message to conversation
        $this->conversation[] = [
            'role' => 'user',
            'content' => $userMessage,
            'timestamp' => now()->toIso8601String(),
        ];

        try {
            $response = Http::timeout(60)->post("{$this->sidecarUrl}/chat", [
                'message' => $userMessage,
                'sessionId' => $this->sessionId,
                'context' => [
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'user_roles' => auth()->user()->roles->pluck('name')->toArray(),
                    'locale' => app()->getLocale(),
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Add assistant response to conversation
                $this->conversation[] = [
                    'role' => 'assistant',
                    'content' => $data['response']['content'] ?? 'No response',
                    'toolCalls' => $data['response']['toolCalls'] ?? [],
                    'timestamp' => now()->toIso8601String(),
                ];

                if (!empty($data['response']['toolCalls'])) {
                    $this->lastToolCalls = $data['response']['toolCalls'];
                }

            } else {
                $this->conversation[] = [
                    'role' => 'error',
                    'content' => 'Failed to get response from AI assistant',
                    'timestamp' => now()->toIso8601String(),
                ];

                Notification::make()
                    ->danger()
                    ->title(__('Error'))
                    ->body('Failed to communicate with AI assistant')
                    ->send();
            }
        } catch (\Exception $e) {
            $this->conversation[] = [
                'role' => 'error',
                'content' => 'Error: ' . $e->getMessage(),
                'timestamp' => now()->toIso8601String(),
            ];

            Notification::make()
                ->danger()
                ->title(__('Connection Error'))
                ->body($e->getMessage())
                ->send();
        } finally {
            $this->isProcessing = false;
        }
    }

    public function clearConversation(): void
    {
        $this->conversation = [];
        $this->sessionId = session()->getId();
        $this->lastToolCalls = null;

        Notification::make()
            ->success()
            ->title(__('Conversation Cleared'))
            ->send();
    }

    public function runBackup(): void
    {
        try {
            $response = Http::timeout(120)->post("{$this->sidecarUrl}/tools/run_backup", [
                'only_db' => true,
            ]);

            if ($response->successful()) {
                Notification::make()
                    ->success()
                    ->title(__('Backup Created'))
                    ->body(__('Database backup completed successfully'))
                    ->send();
            } else {
                throw new \Exception('Backup failed');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('Backup Failed'))
                ->body($e->getMessage())
                ->send();
        }
    }

    public function restoreLatest(): void
    {
        try {
            $response = Http::timeout(120)->post("{$this->sidecarUrl}/tools/restore_latest_backup", []);

            if ($response->successful()) {
                Notification::make()
                    ->success()
                    ->title(__('Restore Complete'))
                    ->body(__('Latest backup has been restored'))
                    ->send();
            } else {
                throw new \Exception('Restore failed');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('Restore Failed'))
                ->body($e->getMessage())
                ->send();
        }
    }

    public function getAvailableTools(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->sidecarUrl}/tools");
            if ($response->successful()) {
                return $response->json()['tools'] ?? [];
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return [];
    }

    public function getSidecarStatus(): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->sidecarUrl}/health");
            if ($response->successful()) {
                return ['status' => 'online', 'data' => $response->json()];
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return ['status' => 'offline', 'data' => null];
    }
}
