<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

// Services Page Content
$servicesContent = file_get_contents("https://raw.githubusercontent.com/user/repo/main/services.html");
if (!$servicesContent) {
    $servicesContent = "<div>Services content will be added shortly</div>";
}

$servicesPage = App\Models\Page::where("slug", "services")->first();
$servicesPage->content = $servicesContent;
$servicesPage->meta_description = "Servicii complete de dezvoltare software: aplicații web, e-commerce, platforme AI, mobile apps. PHP, Python, Java, TypeScript, Ruby.";
$servicesPage->save();

echo "✅ Services updated\n";

// About Page Content  
$aboutContent = "<div>About content will be added shortly</div>";

$aboutPage = App\Models\Page::where("slug", "about-us")->first();
$aboutPage->content = $aboutContent;
$aboutPage->meta_description = "Echipă dezvoltatori 10+ ani experiență în web, mobile și AI. 500+ proiecte finalizate.";
$aboutPage->save();

echo "✅ About updated\n";
