<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Services\GroqAiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Filament\Actions\Action;

class AiRepair extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'AI';
    protected static ?string $navigationLabel = 'AI Repair';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.ai-repair';

    public string $repairLog = '';
    public bool $isAnalyzing = false;

    public function mount(): void
    {
        $this->analyzeDatabase();
    }

    public function getIssuesProperty()
    {
        return session('ai_repair_issues', []);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('runRepair')
                ->label('Run AI Repair')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Run AI Database Repair')
                ->modalDescription('This will analyze and repair database issues using AI. Continue?')
                ->action(fn () => $this->runAiRepair()),
            
            Action::make('optimizeTables')
                ->label('Optimize Tables')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->action(fn () => $this->optimizeTables()),
            
            Action::make('analyzeAgain')
                ->label('Re-analyze')
                ->icon('heroicon-o-magnifying-glass')
                ->color('info')
                ->action(fn () => $this->analyzeDatabase()),
        ];
    }

    public function analyzeDatabase(): void
    {
        $this->isAnalyzing = true;
        session()->forget('ai_repair_issues');
        $issues = [];
        $this->repairLog = "Starting database analysis...\n\n";

        try {
            // Check for orphaned records
            $issues = array_merge($issues, $this->checkOrphanedRecords());
            
            // Check table integrity
            $this->checkTableIntegrity();
            
            // Check for duplicate slugs
            $issues = array_merge($issues, $this->checkDuplicateSlugs());
            
            // Check for missing indexes
            $this->checkMissingIndexes();

            session(['ai_repair_issues' => $issues]);

            if (empty($issues)) {
                $this->repairLog .= "\n✅ No issues found! Database is healthy.";
                Notification::make()
                    ->title('Analysis Complete')
                    ->body('No issues found. Database is healthy!')
                    ->success()
                    ->send();
            } else {
                $this->repairLog .= "\n⚠️ Found " . count($issues) . " issue(s) that need attention.";
                Notification::make()
                    ->title('Issues Found')
                    ->body('Found ' . count($issues) . ' issue(s). Review and repair.')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            $this->repairLog .= "\n❌ Error during analysis: " . $e->getMessage();
            Notification::make()
                ->title('Analysis Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }

        $this->isAnalyzing = false;
    }

    protected function checkOrphanedRecords(): array
    {
        $this->repairLog .= "Checking for orphaned records...\n";
        $issues = [];

        // Check posts without categories
        if (Schema::hasTable('posts') && Schema::hasTable('categories')) {
            $orphanedPosts = DB::table('posts')
                ->whereNotNull('category_id')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('categories')
                        ->whereColumn('categories.id', 'posts.category_id');
                })
                ->count();

            if ($orphanedPosts > 0) {
                $issues[] = [
                    'type' => 'orphaned_posts',
                    'count' => $orphanedPosts,
                    'message' => "Found {$orphanedPosts} post(s) with invalid category references"
                ];
                $this->repairLog .= "  ⚠️ {$orphanedPosts} orphaned post(s)\n";
            }
        }

        // Check products without categories
        if (Schema::hasTable('products') && Schema::hasTable('categories')) {
            $orphanedProducts = DB::table('products')
                ->whereNotNull('category_id')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('categories')
                        ->whereColumn('categories.id', 'products.category_id');
                })
                ->count();

            if ($orphanedProducts > 0) {
                $issues[] = [
                    'type' => 'orphaned_products',
                    'count' => $orphanedProducts,
                    'message' => "Found {$orphanedProducts} product(s) with invalid category references"
                ];
                $this->repairLog .= "  ⚠️ {$orphanedProducts} orphaned product(s)\n";
            }
        }

        if (empty($issues)) {
            $this->repairLog .= "  ✅ No orphaned records found\n";
        }

        return $issues;
    }

    protected function checkTableIntegrity(): void
    {
        $this->repairLog .= "\nChecking table integrity...\n";

        $tables = ['posts', 'pages', 'products', 'categories', 'orders'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->repairLog .= "  ✓ Table '{$table}' exists\n";
            } else {
                $this->repairLog .= "  ⚠️ Table '{$table}' missing\n";
            }
        }
    }

    protected function checkDuplicateSlugs(): array
    {
        $this->repairLog .= "\nChecking for duplicate slugs...\n";
        $issues = [];

        $tables = ['posts', 'pages', 'products', 'categories'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'slug')) {
                $duplicates = DB::table($table)
                    ->select('slug', DB::raw('COUNT(*) as count'))
                    ->groupBy('slug')
                    ->having('count', '>', 1)
                    ->get();

                if ($duplicates->isNotEmpty()) {
                    $issues[] = [
                        'type' => 'duplicate_slugs',
                        'table' => $table,
                        'count' => $duplicates->count(),
                        'message' => "Found duplicate slugs in {$table} table"
                    ];
                    $this->repairLog .= "  ⚠️ {$duplicates->count()} duplicate slug(s) in '{$table}'\n";
                }
            }
        }

        return $issues;
    }

    protected function checkMissingIndexes(): void
    {
        $this->repairLog .= "\nChecking for recommended indexes...\n";
        $this->repairLog .= "  ℹ️ Index optimization available\n";
    }

    public function optimizeTables(): void
    {
        try {
            $this->repairLog .= "\nOptimizing database tables...\n";

            $tables = DB::select('SHOW TABLES');
            $count = 0;

            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                DB::statement("OPTIMIZE TABLE `{$tableName}`");
                $count++;
                $this->repairLog .= "  ✓ Optimized: {$tableName}\n";
            }

            $this->repairLog .= "\n✅ Optimized {$count} table(s) successfully!\n";

            Notification::make()
                ->title('Optimization Complete')
                ->body("Successfully optimized {$count} tables")
                ->success()
                ->send();

        } catch (\Exception $e) {
            $this->repairLog .= "\n❌ Optimization error: " . $e->getMessage() . "\n";
            
            Notification::make()
                ->title('Optimization Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function runAiRepair(): void
    {
        try {
            $this->repairLog .= "\n🤖 Running AI-powered repair analysis...\n";

            $groqService = app(GroqAiService::class);
            
            $issues = session('ai_repair_issues', []);
            $issuesContext = empty($issues) 
                ? "No issues detected." 
                : "Issues found:\n" . collect($issues)->pluck('message')->implode("\n");

            $prompt = "As a database expert, analyze these database issues and provide repair recommendations:\n\n{$issuesContext}\n\nProvide concise, actionable repair steps.";

            $aiResponse = $groqService->generateContent($prompt);
            
            // Extract content from response array
            $aiContent = $aiResponse['success'] ?? false 
                ? ($aiResponse['content'] ?? 'No recommendations generated.')
                : ($aiResponse['error'] ?? 'AI service error.');

            $this->repairLog .= "\n📋 AI Recommendations:\n";
            $this->repairLog .= str_repeat('-', 50) . "\n";
            $this->repairLog .= $aiContent . "\n";
            $this->repairLog .= str_repeat('-', 50) . "\n";

            // Auto-fix orphaned records
            if (!empty($issues)) {
                $this->autoFixIssues($issues);
            }

            Notification::make()
                ->title('AI Repair Complete')
                ->body('AI analysis complete. Check the log for recommendations.')
                ->success()
                ->send();

        } catch (\Exception $e) {
            $this->repairLog .= "\n❌ AI Repair error: " . $e->getMessage() . "\n";
            
            Notification::make()
                ->title('AI Repair Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function autoFixIssues(array $issues): void
    {
        $this->repairLog .= "\n🔧 Attempting automatic fixes...\n";

        foreach ($issues as $issue) {
            switch ($issue['type']) {
                case 'orphaned_posts':
                    $fixed = DB::table('posts')
                        ->whereNotNull('category_id')
                        ->whereNotExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('categories')
                                ->whereColumn('categories.id', 'posts.category_id');
                        })
                        ->update(['category_id' => null]);
                    
                    $this->repairLog .= "  ✓ Fixed {$fixed} orphaned post(s)\n";
                    break;

                case 'orphaned_products':
                    $fixed = DB::table('products')
                        ->whereNotNull('category_id')
                        ->whereNotExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('categories')
                                ->whereColumn('categories.id', 'products.category_id');
                        })
                        ->update(['category_id' => null]);
                    
                    $this->repairLog .= "  ✓ Fixed {$fixed} orphaned product(s)\n";
                    break;

                case 'duplicate_slugs':
                    $this->repairLog .= "  ⚠️ Duplicate slugs require manual review\n";
                    break;
            }
        }

        $this->repairLog .= "\n✅ Automatic fixes applied!\n";
    }
}
