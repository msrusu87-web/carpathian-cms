<?php

namespace App\Console\Commands;

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SiteProbeCommand extends Command
{
    protected $signature = 'site:probe
        {--locale=ro : Locale to set via /lang/{locale}}
        {--base= : Override base URL (defaults to APP_URL)}
        {--paths= : Comma-separated paths to test (defaults to common routes)}';

    protected $description = 'Probe key site routes for HTTP status, locale persistence, and translation-key leaks (messages.*)';

    public function handle(): int
    {
        $locale = (string) $this->option('locale');
        $baseUrl = rtrim((string) ($this->option('base') ?: config('app.url')), '/');

        $pathsOpt = (string) ($this->option('paths') ?? '');
        $paths = $pathsOpt !== ''
            ? array_values(array_filter(array_map('trim', explode(',', $pathsOpt))))
            : [
                '/',
                '/blog',
                '/shop',
                '/shop/categories',
                '/contact',
                '/cart',
                '/checkout',
                '/login',
                '/register',
                '/register?redirect=' . urlencode($baseUrl . '/checkout'),
            ];

        $this->info("🔎 Probing: {$baseUrl} (locale={$locale})");

        $jar = new CookieJar();

        // Set locale using the public switch route.
        $switchUrl = "{$baseUrl}/lang/{$locale}";
        $this->line("Setting locale via {$switchUrl}");

        try {
            Http::withOptions([
                'cookies' => $jar,
                'verify' => false,
                'allow_redirects' => true,
                'timeout' => 20,
            ])->get($switchUrl);
        } catch (\Throwable $e) {
            $this->error('Failed to set locale: ' . $e->getMessage());
        }

        $rows = [];

        foreach ($paths as $path) {
            $url = str_starts_with($path, 'http') ? $path : ($baseUrl . $path);

            try {
                $resp = Http::withOptions([
                    'cookies' => $jar,
                    'verify' => false,
                    'allow_redirects' => true,
                    'timeout' => 20,
                ])->get($url);

                $status = $resp->status();
                $body = (string) $resp->body();

                $htmlLang = $this->extractHtmlLang($body);
                $leaks = substr_count($body, 'messages.');
                $hasLocaleCookie = collect($resp->cookies())->contains(fn ($c) => $c->getName() === 'locale');

                $rows[] = [
                    $path,
                    (string) $status,
                    $htmlLang ?: '-',
                    (string) $leaks,
                    $hasLocaleCookie ? 'cookie-set' : '-',
                ];
            } catch (\Throwable $e) {
                $rows[] = [$path, 'ERR', '-', '-', $e->getMessage()];
            }
        }

        $this->newLine();
        $this->table(['Path', 'HTTP', 'html[lang]', 'messages.*', 'Notes'], $rows);

        $bad = collect($rows)->filter(function ($r) {
            $http = $r[1];
            if ($http === 'ERR') {
                return true;
            }
            $code = (int) $http;
            return $code >= 400 || ((int) $r[3]) > 0;
        })->count();

        if ($bad > 0) {
            $this->warn("⚠️  {$bad} route(s) have issues (>=400 or messages.* leaks)");
            return 1;
        }

        $this->info('✅ All probed routes look healthy');
        return 0;
    }

    private function extractHtmlLang(string $html): ?string
    {
        if ($html === '') {
            return null;
        }
        if (preg_match('/<html[^>]*\blang\s*=\s*"([^"]+)"/i', $html, $m)) {
            return $m[1];
        }
        return null;
    }
}
