<?php

namespace Plugins\Marketing\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use App\Filament\Clusters\Marketing;
use Plugins\Marketing\Models\MarketingCampaign;
use Plugins\Marketing\Models\MarketingList;
use Plugins\Marketing\Models\MarketingTemplate;
use Plugins\Marketing\Models\MarketingRateLimit;
use Plugins\Marketing\Services\EmailCampaignService;

class MarketingCampaignResource extends Resource
{
    protected static ?string $model = MarketingCampaign::class;
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $cluster = Marketing::class;
    protected static ?string $navigationLabel = 'Campaigns';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Campaign Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('list_id')
                        ->label('Target List')
                        ->options(MarketingList::where('is_active', true)->pluck('name', 'id'))
                        ->required()
                        ->searchable(),
                    Forms\Components\Select::make('template_id')
                        ->label('Use Template (optional)')
                        ->options(MarketingTemplate::where('is_active', true)->pluck('name', 'id'))
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $template = MarketingTemplate::find($state);
                                if ($template) {
                                    $set('subject', $template->subject);
                                    $set('body_html', $template->body_html);
                                }
                            }
                        }),
                ])->columns(2),

            Forms\Components\Section::make('Email Content')
                ->schema([
                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Available variables: {{company_name}}, {{contact_name}}, {{unsubscribe_url}}'),
                    Forms\Components\RichEditor::make('body_html')
                        ->label('Email Body')
                        ->required()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike',
                            'link', 'bulletList', 'orderedList',
                            'h2', 'h3', 'blockquote',
                            'redo', 'undo',
                        ])
                        ->helperText('Include {{unsubscribe_url}} for compliance. Variables: {{company_name}}, {{contact_name}}, {{email}}')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('body_text')
                        ->label('Plain Text Version (optional)')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Sender Settings')
                ->schema([
                    Forms\Components\TextInput::make('from_name')
                        ->label('From Name')
                        ->default(config('app.name'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('from_email')
                        ->label('From Email')
                        ->email()
                        ->default(config('mail.from.address'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('reply_to')
                        ->label('Reply To')
                        ->email()
                        ->maxLength(255),
                ])->columns(3),

            Forms\Components\Section::make('Scheduling')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'scheduled' => 'Scheduled',
                        ])
                        ->default('draft')
                        ->required(),
                    Forms\Components\DateTimePicker::make('scheduled_at')
                        ->label('Schedule For')
                        ->minDate(now())
                        ->visible(fn (callable $get) => $get('status') === 'scheduled'),
                ])->columns(2),

            Forms\Components\Section::make('Statistics')
                ->schema([
                    Forms\Components\Placeholder::make('total_recipients')
                        ->content(fn (?MarketingCampaign $record) => $record?->total_recipients ?? 0),
                    Forms\Components\Placeholder::make('emails_sent')
                        ->content(fn (?MarketingCampaign $record) => $record?->emails_sent ?? 0),
                    Forms\Components\Placeholder::make('open_rate')
                        ->content(fn (?MarketingCampaign $record) => ($record?->open_rate ?? 0) . '%'),
                    Forms\Components\Placeholder::make('click_rate')
                        ->content(fn (?MarketingCampaign $record) => ($record?->click_rate ?? 0) . '%'),
                ])
                ->columns(4)
                ->visible(fn (?MarketingCampaign $record) => $record !== null && $record->exists),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('list.name')
                    ->label('Target List')
                    ->badge(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'scheduled',
                        'info' => 'sending',
                        'success' => 'sent',
                        'danger' => ['paused', 'cancelled'],
                    ]),
                Tables\Columns\TextColumn::make('total_recipients')
                    ->label('Recipients')
                    ->sortable(),
                Tables\Columns\TextColumn::make('emails_sent')
                    ->label('Sent')
                    ->sortable(),
                Tables\Columns\TextColumn::make('open_rate')
                    ->label('Open %')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('click_rate')
                    ->label('Click %')
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'sending' => 'Sending',
                        'sent' => 'Sent',
                        'paused' => 'Paused',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('send')
                    ->label('Send Now')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Send Campaign')
                    ->modalDescription(fn (MarketingCampaign $record) => 
                        "This will send emails to {$record->list?->activeContacts()->count()} contacts. Rate limits apply.")
                    ->visible(fn (MarketingCampaign $record) => $record->canSend())
                    ->action(function (MarketingCampaign $record) {
                        $remaining = MarketingRateLimit::getRemainingEmails();
                        
                        if ($remaining['hourly'] <= 0 || $remaining['daily'] <= 0) {
                            Notification::make()
                                ->title('Rate limit reached')
                                ->body("Hourly: {$remaining['hourly']} remaining, Daily: {$remaining['daily']} remaining")
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        $service = new EmailCampaignService();
                        $result = $service->sendCampaign($record);
                        
                        Notification::make()
                            ->title($result['success'] ? 'Campaign started' : 'Campaign failed')
                            ->body($result['message'])
                            ->color($result['success'] ? 'success' : 'danger')
                            ->send();
                    }),
                Tables\Actions\Action::make('pause')
                    ->icon('heroicon-o-pause')
                    ->color('warning')
                    ->visible(fn (MarketingCampaign $record) => $record->status === 'sending')
                    ->action(fn (MarketingCampaign $record) => $record->pause()),
                Tables\Actions\Action::make('sync_brevo')
                    ->label('Sync Stats')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn (MarketingCampaign $record) => in_array($record->status, ['sending', 'sent']))
                    ->requiresConfirmation()
                    ->modalHeading('Sync Stats from Brevo')
                    ->modalDescription('This will update campaign statistics with data from Brevo. Make sure webhooks are configured for automatic updates.')
                    ->modalSubmitActionLabel('Sync Now')
                    ->action(function (MarketingCampaign $record) {
                        try {
                            // For now, show webhook setup info
                            $webhookUrl = url('/api/marketing/webhook/brevo');
                            Notification::make()
                                ->title('Webhook Setup Required')
                                ->body("Configure webhook in Brevo dashboard: {$webhookUrl}")
                                ->info()
                                ->duration(10000)
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Sync failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('view_stats')
                    ->label('Detailed Stats')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->visible(fn (MarketingCampaign $record) => $record->emails_sent > 0)
                    ->modalHeading(fn (MarketingCampaign $record) => "Campaign Statistics: {$record->name}")
                    ->modalDescription(function (MarketingCampaign $record) {
                        $openRate = $record->open_rate ?? 0;
                        $clickRate = $record->click_rate ?? 0;
                        $bounceRate = $record->bounce_rate ?? 0;
                        $clickToOpenRate = $record->emails_opened > 0 
                            ? round(($record->emails_clicked / $record->emails_opened) * 100, 2)
                            : 0;

                        $stats = [
                            "📊 **Campaign Performance**",
                            "",
                            "📧 **Recipients:** {$record->total_recipients}",
                            "✉️ **Sent:** {$record->emails_sent}",
                            "👁️ **Opened:** {$record->emails_opened} ({$openRate}%)",
                            "🖱️ **Clicked:** {$record->emails_clicked} ({$clickRate}%)",
                            "⚠️ **Bounced:** {$record->emails_bounced} ({$bounceRate}%)",
                            "🚫 **Unsubscribed:** {$record->emails_unsubscribed}",
                            "📈 **Click-to-Open Rate:** {$clickToOpenRate}%",
                            "",
                            "**Industry Benchmarks:**",
                            "• Open Rate: 15-25% (You: {$openRate}%)",
                            "• Click Rate: 2-5% (You: {$clickRate}%)",
                            "• Bounce Rate: <2% (You: {$bounceRate}%)",
                            "",
                            $openRate > 30 ? "🌟 **Outstanding** open rate!" : ($openRate > 20 ? "✅ **Great** open rate!" : "⚠️ Consider improving subject lines"),
                            $clickRate > 5 ? "✅ **Excellent** engagement!" : ($clickRate > 2 ? "✅ **Good** click rate" : "⚠️ Improve your CTAs"),
                            $bounceRate < 2 ? "✅ **Perfect** list health!" : "⚠️ Clean your email list",
                            "",
                            "**Webhook URL for real-time stats:**",
                            url('/api/marketing/webhook/brevo'),
                        ];

                        return implode("\n", $stats);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                Tables\Actions\Action::make('duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (MarketingCampaign $record) {
                        $new = $record->replicate();
                        $new->name = $record->name . ' (Copy)';
                        $new->status = 'draft';
                        $new->emails_sent = 0;
                        $new->emails_opened = 0;
                        $new->emails_clicked = 0;
                        $new->emails_bounced = 0;
                        $new->emails_unsubscribed = 0;
                        $new->started_at = null;
                        $new->completed_at = null;
                        $new->save();
                        
                        Notification::make()->title('Campaign duplicated')->success()->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (MarketingCampaign $record) => $record->isDraft()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Plugins\Marketing\Filament\Resources\MarketingCampaignResource\Pages\ListMarketingCampaigns::route('/'),
            'create' => \Plugins\Marketing\Filament\Resources\MarketingCampaignResource\Pages\CreateMarketingCampaign::route('/create'),
            'edit' => \Plugins\Marketing\Filament\Resources\MarketingCampaignResource\Pages\EditMarketingCampaign::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'marketing')->where('is_active', true)->exists();
    }
}
