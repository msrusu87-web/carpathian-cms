<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

$template = App\Models\Template::find(2);
echo "Template Name: " . $template->name . "\n";
echo "Template Type: " . $template->type . "\n\n";

$aboutPage = App\Models\Page::where("slug", "about-us")->first();
echo "=== ABOUT US PAGE ===\n";
echo "Content length: " . strlen($aboutPage->content) . " chars\n";
echo substr($aboutPage->content, 0, 500) . "...\n\n";

$servicesPage = App\Models\Page::where("slug", "services")->first();
echo "=== SERVICES PAGE ===\n";
echo "Content length: " . strlen($servicesPage->content) . " chars\n";
echo substr($servicesPage->content, 0, 500) . "...\n";
