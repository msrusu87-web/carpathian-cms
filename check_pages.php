<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

$pages = App\Models\Page::select("id", "title", "slug", "status", "template_id")->get();
echo "Total pages: " . $pages->count() . "\n\n";
foreach($pages as $page) {
    echo "ID: {$page->id} | Title: {$page->title} | Slug: {$page->slug} | Status: {$page->status} | Template: {$page->template_id}\n";
}
