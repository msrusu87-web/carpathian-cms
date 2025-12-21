<?php

namespace App\Services;

use Illuminate\Support\Str;

class ContentImprovementService
{
    protected AIService $aiService;

    public function __construct()
    {
        $this->aiService = new AIService();
    }

    /**
     * Analyze content and provide improvement suggestions
     */
    public function analyzeSEO(string $content, ?string $title = null): array
    {
        $systemPrompt = "You are an SEO and content optimization expert. Analyze the content and provide specific, actionable improvement suggestions. Focus on: readability, SEO optimization, structure, and engagement.";
        
        $prompt = "Title: " . ($title ?? 'Untitled') . "\n\nContent:\n" . Str::limit($content, 4000);
        $prompt .= "\n\nProvide 5-7 specific improvement suggestions in a numbered list format.";
        
        try {
            $response = $this->aiService->chat($prompt, [
                'system' => $systemPrompt,
                'max_tokens' => 800,
                'temperature' => 0.6,
            ]);

            return [
                'suggestions' => $this->parseSuggestions($response),
                'raw_analysis' => $response,
            ];

        } catch (\Exception $e) {
            \Log::error('Content analysis error', ['error' => $e->getMessage()]);
            return ['suggestions' => [], 'raw_analysis' => null];
        }
    }

    /**
     * Generate meta description from content
     */
    public function generateMetaDescription(string $content, int $maxLength = 160): string
    {
        $systemPrompt = "You are an SEO expert. Create a compelling meta description that summarizes the content and encourages clicks. Keep it under {$maxLength} characters.";
        
        $prompt = "Content: " . Str::limit($content, 2000) . "\n\nGenerate meta description:";
        
        try {
            $response = $this->aiService->chat($prompt, [
                'system' => $systemPrompt,
                'max_tokens' => 100,
                'temperature' => 0.7,
            ]);

            return Str::limit($response ?? '', $maxLength, '');

        } catch (\Exception $e) {
            \Log::error('Meta description generation error', ['error' => $e->getMessage()]);
            return Str::limit(strip_tags($content), $maxLength, '...');
        }
    }

    /**
     * Improve content readability
     */
    public function improveReadability(string $content): string
    {
        $systemPrompt = "You are a content editor. Improve the readability of the content without changing its core message. Make it more engaging, clearer, and better structured.";
        
        $prompt = Str::limit($content, 3000);
        
        try {
            $response = $this->aiService->chat($prompt, [
                'system' => $systemPrompt,
                'max_tokens' => 2000,
                'temperature' => 0.6,
            ]);

            return $response ?? $content;

        } catch (\Exception $e) {
            \Log::error('Readability improvement error', ['error' => $e->getMessage()]);
            return $content;
        }
    }

    /**
     * Generate content variations
     */
    public function generateVariations(string $content, int $count = 3): array
    {
        $systemPrompt = "You are a creative content writer. Create {$count} different variations of the given content. Each variation should maintain the core message but use different wording and structure.";
        
        $prompt = "Original content: " . Str::limit($content, 1500) . "\n\nGenerate {$count} variations, separated by '---':";
        
        try {
            $response = $this->aiService->chat($prompt, [
                'system' => $systemPrompt,
                'max_tokens' => 2000,
                'temperature' => 0.8,
            ]);

            $variations = explode('---', $response ?? '');
            return array_map('trim', array_filter($variations));

        } catch (\Exception $e) {
            \Log::error('Content variation error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Check content for SEO score
     */
    public function calculateSEOScore(string $content, ?string $title = null, ?string $metaDescription = null): array
    {
        $score = 0;
        $maxScore = 100;
        $issues = [];

        // Title checks (15 points)
        if ($title) {
            $titleLength = Str::length($title);
            if ($titleLength >= 30 && $titleLength <= 60) {
                $score += 15;
            } elseif ($titleLength > 0) {
                $score += 8;
                $issues[] = 'Title should be between 30-60 characters';
            }
        }

        // Meta description checks (15 points)
        if ($metaDescription) {
            $metaLength = Str::length($metaDescription);
            if ($metaLength >= 120 && $metaLength <= 160) {
                $score += 15;
            } elseif ($metaLength > 0) {
                $score += 8;
                $issues[] = 'Meta description should be between 120-160 characters';
            }
        }

        // Content length checks (20 points)
        $contentLength = Str::length(strip_tags($content));
        if ($contentLength >= 300) {
            $score += 20;
        } elseif ($contentLength >= 150) {
            $score += 10;
            $issues[] = 'Content should be at least 300 words for better SEO';
        }

        // Heading structure (15 points)
        $headingCount = substr_count($content, '<h2>') + substr_count($content, '<h3>');
        if ($headingCount >= 3) {
            $score += 15;
        } elseif ($headingCount > 0) {
            $score += 8;
            $issues[] = 'Add more headings to structure your content';
        }

        // Link checks (15 points)
        $linkCount = substr_count($content, '<a ');
        if ($linkCount >= 2) {
            $score += 15;
        } elseif ($linkCount > 0) {
            $score += 8;
            $issues[] = 'Add more internal/external links';
        }

        // Image checks (10 points)
        $imageCount = substr_count($content, '<img ');
        if ($imageCount >= 1) {
            $score += 10;
        } else {
            $issues[] = 'Add images to make content more engaging';
        }

        // Paragraph length (10 points)
        $paragraphs = explode('</p>', $content);
        $avgParLength = $contentLength / (count($paragraphs) ?: 1);
        if ($avgParLength <= 150) {
            $score += 10;
        } else {
            $issues[] = 'Break content into shorter paragraphs';
        }

        return [
            'score' => min($score, $maxScore),
            'max_score' => $maxScore,
            'percentage' => round(($score / $maxScore) * 100),
            'grade' => $this->getGrade($score),
            'issues' => $issues,
        ];
    }

    /**
     * Parse suggestions from AI response
     */
    protected function parseSuggestions(?string $response): array
    {
        if (!$response) {
            return [];
        }

        $lines = explode("\n", $response);
        $suggestions = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^\d+[\.\)]\s*(.+)$/', $line, $matches)) {
                $suggestions[] = trim($matches[1]);
            }
        }

        return $suggestions;
    }

    /**
     * Get letter grade from score
     */
    protected function getGrade(int $score): string
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }
}
