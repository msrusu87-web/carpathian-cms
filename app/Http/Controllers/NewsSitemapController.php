<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class NewsSitemapController extends Controller
{
    /**
     * Generate Google News Sitemap
     * Only includes posts from the last 2 days (Google News requirement)
     */
    public function index()
    {
        if (!Schema::hasTable('posts')) {
            return response('Posts table not found', 404);
        }

        $posts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '>=', now()->subDays(2))
            ->orderBy('published_at', 'desc')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";        $xml .= '<?xml-stylesheet type="text/xsl" href="/sitemap-news.xsl"?>' . "\n";        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";

        foreach ($posts as $post) {
            // Get title in all available languages
            $titles = is_array($post->title) ? $post->title : ['ro' => $post->title];
            $defaultTitle = $titles[app()->getLocale()] ?? $titles['ro'] ?? $titles['en'] ?? reset($titles) ?? $post->title;

            $xml .= "    <url>\n";
            $xml .= "        <loc>" . htmlspecialchars(url("/blog/{$post->slug}"), ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "        <news:news>\n";
            $xml .= "            <news:publication>\n";
            $xml .= "                <news:name>Carphatian CMS</news:name>\n";
            $xml .= "                <news:language>ro</news:language>\n";
            $xml .= "            </news:publication>\n";
            $xml .= "            <news:publication_date>" . $post->published_at->toAtomString() . "</news:publication_date>\n";
            $xml .= "            <news:title>" . htmlspecialchars($defaultTitle, ENT_XML1, 'UTF-8') . "</news:title>\n";
            
            // Add keywords if available
            if (!empty($post->meta_keywords)) {
                $keywords = is_array($post->meta_keywords) ? implode(', ', $post->meta_keywords) : $post->meta_keywords;
                if (!empty($keywords)) {
                    $xml .= "            <news:keywords>" . htmlspecialchars($keywords, ENT_XML1, 'UTF-8') . "</news:keywords>\n";
                }
            }
            
            $xml .= "        </news:news>\n";
            $xml .= "    </url>\n";
        }

        $xml .= "</urlset>";

        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }

    /**
     * Generate and save news sitemap to file
     */
    public function generate()
    {
        if (!Schema::hasTable('posts')) {
            return response()->json([
                'success' => false,
                'message' => 'Posts table not found',
            ], 404);
        }

        $posts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '>=', now()->subDays(2))
            ->orderBy('published_at', 'desc')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?xml-stylesheet type="text/xsl" href="/sitemap-news.xsl"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";

        foreach ($posts as $post) {
            $titles = is_array($post->title) ? $post->title : ['ro' => $post->title];
            $defaultTitle = $titles[app()->getLocale()] ?? $titles['ro'] ?? $titles['en'] ?? reset($titles) ?? $post->title;

            $xml .= "    <url>\n";
            $xml .= "        <loc>" . htmlspecialchars(url("/blog/{$post->slug}"), ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "        <news:news>\n";
            $xml .= "            <news:publication>\n";
            $xml .= "                <news:name>Carphatian CMS</news:name>\n";
            $xml .= "                <news:language>ro</news:language>\n";
            $xml .= "            </news:publication>\n";
            $xml .= "            <news:publication_date>" . $post->published_at->toAtomString() . "</news:publication_date>\n";
            $xml .= "            <news:title>" . htmlspecialchars($defaultTitle, ENT_XML1, 'UTF-8') . "</news:title>\n";
            
            if (!empty($post->meta_keywords)) {
                $keywords = is_array($post->meta_keywords) ? implode(', ', $post->meta_keywords) : $post->meta_keywords;
                if (!empty($keywords)) {
                    $xml .= "            <news:keywords>" . htmlspecialchars($keywords, ENT_XML1, 'UTF-8') . "</news:keywords>\n";
                }
            }
            
            $xml .= "        </news:news>\n";
            $xml .= "    </url>\n";
        }

        $xml .= "</urlset>";

        file_put_contents(public_path('sitemap-news.xml'), $xml);

        return response()->json([
            'success' => true,
            'message' => 'News sitemap generated successfully',
            'path' => public_path('sitemap-news.xml'),
            'posts_count' => $posts->count(),
        ]);
    }
}
