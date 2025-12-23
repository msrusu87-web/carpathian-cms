<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Page;
use App\Models\Post;
use App\Models\Category;
use App\Models\ProductCategory;

class TranslateContentSeeder extends Seeder
{
    public function run(): void
    {
        // Translate Products
        $productTranslations = [
            'Custom Web Development' => [
                'name' => 'Dezvoltare Web Personalizată',
                'description' => 'Servicii profesionale de dezvoltare web adaptate nevoilor dumneavoastră de afaceri. Creăm site-uri web responsive, rapide și securizate.',
                'content' => 'Echipa noastră de dezvoltatori experimentați construiește soluții web personalizate folosind cele mai noi tehnologii. De la site-uri web simple până la aplicații web complexe, oferim servicii complete de dezvoltare.'
            ],
            'Mobile App Development' => [
                'name' => 'Dezvoltare Aplicații Mobile',
                'description' => 'Creăm aplicații mobile native și cross-platform pentru iOS și Android cu experiență superioară pentru utilizatori.',
                'content' => 'Serviciile noastre de dezvoltare aplicații mobile acoperă întregul ciclu - de la concept și design până la dezvoltare, testare și lansare pe App Store și Google Play.'
            ],
            'E-commerce Solutions' => [
                'name' => 'Soluții E-commerce',
                'description' => 'Platforme complete de comerț electronic cu procesare securizată a plăților, gestionare inventar și analiză avansată.',
                'content' => 'Construim magazine online puternice care convertesc vizitatori în clienți. Platformele noastre e-commerce includ toate funcționalitățile necesare pentru a vă dezvolta afacerea online.'
            ],
            'UI/UX Design Services' => [
                'name' => 'Servicii Design UI/UX',
                'description' => 'Experiențe digitale frumoase și intuitive care îmbunătățesc satisfacția utilizatorilor și stimulează conversiile.',
                'content' => 'Designerii noștri creează interfețe atractive vizual și ușor de utilizat. Ne concentrăm pe cercetarea utilizatorilor, wireframing, prototipuri și testare pentru a livra cele mai bune experiențe.'
            ],
            'SEO & Digital Marketing' => [
                'name' => 'SEO & Marketing Digital',
                'description' => 'Servicii cuprinzătoare de marketing digital pentru a îmbunătăți vizibilitatea online și a genera mai mult trafic calificat.',
                'content' => 'Strategiile noastre de marketing digital includ optimizare SEO, marketing pe rețele sociale, publicitate PPC și marketing de conținut pentru a vă ajuta să vă atingeți obiectivele de afaceri.'
            ],
            'Website Maintenance & Support' => [
                'name' => 'Mentenanță și Suport Website',
                'description' => 'Servicii continue de mentenanță și suport pentru a menține site-ul dumneavoastră securizat, actualizat și funcționând optim.',
                'content' => 'Oferim pachete complete de mentenanță incluzând actualizări regulate, backup-uri, monitorizare securitate și suport tehnic pentru a asigura funcționarea continuă a site-ului.'
            ],
            'API Development & Integration' => [
                'name' => 'Dezvoltare și Integrare API',
                'description' => 'API-uri RESTful personalizate și integrări cu servicii terțe pentru a extinde funcționalitatea aplicației.',
                'content' => 'Dezvoltăm API-uri robuste și integrăm aplicația dumneavoastră cu diverse servicii externe - sisteme de plată, CRM-uri, platforme de social media și multe altele.'
            ],
            'Cloud Hosting & DevOps' => [
                'name' => 'Hosting Cloud & DevOps',
                'description' => 'Soluții scalabile de hosting cloud cu practici DevOps moderne pentru implementare și monitorizare continuă.',
                'content' => 'Serviciile noastre de infrastructură cloud asigură performanță înaltă, scalabilitate și fiabilitate. Implementăm pipeline-uri CI/CD și practici DevOps pentru livrare rapidă.'
            ]
        ];

        foreach (Product::all() as $product) {
            $nameData = json_decode($product->name, true);
            $enName = $nameData['en'] ?? $product->name;
            
            if (isset($productTranslations[$enName])) {
                $trans = $productTranslations[$enName];
                $product->update([
                    'name' => ['en' => $enName, 'ro' => $trans['name']],
                    'description' => ['en' => $nameData['en'] ?? '', 'ro' => $trans['description']],
                    'content' => ['en' => $nameData['en'] ?? '', 'ro' => $trans['content']]
                ]);
                echo "✓ Translated product: {$enName}\n";
            }
        }

        // Translate Pages
        $pageTranslations = [
            'About Us' => [
                'title' => 'Despre Noi',
                'excerpt' => 'Aflați mai multe despre echipa noastră și misiunea noastră de a livra soluții digitale excepționale.',
                'content' => '<h1>Despre Compania Noastră</h1><p>Suntem o agenție digitală dedicată, pasionată de crearea de experiențe web excepționale. Cu ani de experiență în industrie, echipa noastră de experți se specializează în dezvoltare web, design și marketing digital.</p><p>Misiunea noastră este să ajutăm afacerile să prospere în era digitală prin furnizarea de soluții inovatoare și rezultate măsurabile.</p>'
            ],
            'Contact Us' => [
                'title' => 'Contactați-ne',
                'excerpt' => 'Luați legătura cu noi. Suntem aici pentru a răspunde la întrebările dumneavoastră.',
                'content' => '<h1>Contactați-ne</h1><p>Aveți un proiect în minte sau pur și simplu doriți să discutați? Ne-ar plăcea să auzim de la dumneavoastră!</p><p>Completați formularul de mai jos și vă vom răspunde în cel mai scurt timp posibil.</p>'
            ],
            'Servicii' => [
                'title' => 'Serviciile Noastre',
                'excerpt' => 'Descoperă gama noastră completă de servicii digitale concepute pentru a-ți dezvolta afacerea.',
                'content' => '<h1>Serviciile Noastre</h1><p>Oferim o gamă largă de servicii digitale pentru a vă ajuta afacerea să reușească online. De la dezvoltare web până la marketing digital, avem expertiza necesară pentru a vă aduce viziunea la viață.</p>'
            ],
            'Welcome to Web Agency CMS' => [
                'title' => 'Bine ați venit la Web Agency CMS',
                'excerpt' => 'Platforma dvs. completă pentru gestionarea și dezvoltarea prezenței digitale.',
                'content' => '<h1>Bine ați venit la Web Agency CMS</h1><p>Sistem de management de conținut puternic și flexibil, construit pentru agenții și afaceri moderne. Gestionați-vă cu ușurință conținutul, produsele și serviciile dintr-un singur loc.</p>'
            ]
        ];

        foreach (Page::all() as $page) {
            $titleData = json_decode($page->title, true);
            $enTitle = $titleData['en'] ?? $page->title;
            
            if (isset($pageTranslations[$enTitle])) {
                $trans = $pageTranslations[$enTitle];
                $page->update([
                    'title' => ['en' => $enTitle, 'ro' => $trans['title']],
                    'excerpt' => ['en' => $titleData['en'] ?? '', 'ro' => $trans['excerpt']],
                    'content' => ['en' => $titleData['en'] ?? '', 'ro' => $trans['content']]
                ]);
                echo "✓ Translated page: {$enTitle}\n";
            }
        }

        // Translate Categories
        $categoryTranslations = [
            'Technology' => ['name' => 'Tehnologie', 'description' => 'Articole și știri despre tehnologie'],
            'Business' => ['name' => 'Afaceri', 'description' => 'Sfaturi și strategii de afaceri'],
            'Design' => ['name' => 'Design', 'description' => 'Design și tendințe creative'],
            'Marketing' => ['name' => 'Marketing', 'description' => 'Strategii și tactici de marketing'],
            'Development' => ['name' => 'Dezvoltare', 'description' => 'Dezvoltare web și software']
        ];

        foreach (Category::all() as $category) {
            $nameData = json_decode($category->name, true);
            $enName = $nameData['en'] ?? $category->name;
            
            if (isset($categoryTranslations[$enName])) {
                $trans = $categoryTranslations[$enName];
                $category->update([
                    'name' => ['en' => $enName, 'ro' => $trans['name']],
                    'description' => ['en' => $nameData['en'] ?? '', 'ro' => $trans['description']]
                ]);
                echo "✓ Translated category: {$enName}\n";
            }
        }

        // Translate Product Categories
        foreach (ProductCategory::all() as $category) {
            $nameData = json_decode($category->name, true);
            $enName = $nameData['en'] ?? $category->name;
            
            if (isset($categoryTranslations[$enName])) {
                $trans = $categoryTranslations[$enName];
                $category->update([
                    'name' => ['en' => $enName, 'ro' => $trans['name']],
                    'description' => ['en' => $nameData['en'] ?? '', 'ro' => $trans['description']]
                ]);
                echo "✓ Translated product category: {$enName}\n";
            }
        }

        echo "\n✅ All content translated successfully!\n";
    }
}
