<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;

class WebDesignServicesSeeder extends Seeder
{
    public function run(): void
    {
        $webDesign = ProductCategory::create([
            'name' => ['en' => 'Web Design Services', 'ro' => 'Servicii Web Design'],
            'slug' => 'web-design',
            'description' => ['en' => 'Professional web design services', 'ro' => 'Servicii profesionale web design'],
            'parent_id' => null,
            'order' => 1,
            'is_active' => true
        ]);

        $websiteCat = ProductCategory::create([
            'name' => ['en' => 'Website Design', 'ro' => 'Design Website'],
            'slug' => 'website-design',
            'parent_id' => $webDesign->id,
            'order' => 1,
            'is_active' => true
        ]);

        $ecommerceCat = ProductCategory::create([
            'name' => ['en' => 'E-commerce', 'ro' => 'Magazine Online'],
            'slug' => 'ecommerce',
            'parent_id' => $webDesign->id,
            'order' => 2,
            'is_active' => true
        ]);

        $webAppCat = ProductCategory::create([
            'name' => ['en' => 'Web Applications', 'ro' => 'Aplicații Web'],
            'slug' => 'web-applications',
            'parent_id' => $webDesign->id,
            'order' => 3,
            'is_active' => true
        ]);

        Product::create([
            'name' => ['en' => 'Landing Page', 'ro' => 'Pagină de Aterizare'],
            'slug' => 'landing-page',
            'description' => ['en' => 'Professional single-page website', 'ro' => 'Website profesional de o singură pagină cu design responsiv, formular contact și optimizare SEO.'],
            'price' => 1500,
            'category_id' => $websiteCat->id,
            'sku' => 'WD-LP-001',
            'stock' => 999,
            'is_active' => true,
            'is_featured' => true
        ]);

        Product::create([
            'name' => ['en' => 'Business Website', 'ro' => 'Website Prezentare Firmă'],
            'slug' => 'business-website',
            'description' => ['en' => 'Complete business website', 'ro' => 'Website complet cu până la 10 pagini, secțiune blog, formular contact și integrare Google Maps.'],
            'price' => 3500,
            'sale_price' => 2999,
            'category_id' => $websiteCat->id,
            'sku' => 'WD-BW-001',
            'stock' => 999,
            'is_active' => true,
            'is_featured' => true
        ]);

        Product::create([
            'name' => ['en' => 'Premium Website', 'ro' => 'Website Premium'],
            'slug' => 'premium-website',
            'description' => ['en' => 'Advanced website with custom features', 'ro' => 'Website avansat cu funcționalități personalizate, conturi utilizatori și suport prioritar 3 luni.'],
            'price' => 6500,
            'category_id' => $websiteCat->id,
            'sku' => 'WD-PW-001',
            'stock' => 999,
            'is_active' => true
        ]);

        Product::create([
            'name' => ['en' => 'Starter Online Store', 'ro' => 'Magazin Online Starter'],
            'slug' => 'starter-store',
            'description' => ['en' => 'E-commerce with up to 50 products', 'ro' => 'Magazin online cu până la 50 produse, coș cumpărături și integrare plăți.'],
            'price' => 4500,
            'sale_price' => 3999,
            'category_id' => $ecommerceCat->id,
            'sku' => 'EC-SS-001',
            'stock' => 999,
            'is_active' => true,
            'is_featured' => true
        ]);

        Product::create([
            'name' => ['en' => 'Professional Store', 'ro' => 'Magazin Online Profesional'],
            'slug' => 'professional-store',
            'description' => ['en' => 'Complete e-commerce platform', 'ro' => 'Platformă completă cu produse nelimitate, conturi clienți, recenzii și sistem cupoane.'],
            'price' => 8500,
            'category_id' => $ecommerceCat->id,
            'sku' => 'EC-PS-001',
            'stock' => 999,
            'is_active' => true,
            'is_featured' => true
        ]);

        Product::create([
            'name' => ['en' => 'Enterprise E-commerce', 'ro' => 'E-commerce Enterprise'],
            'slug' => 'enterprise-ecommerce',
            'description' => ['en' => 'Advanced e-commerce solution', 'ro' => 'Soluție avansată cu multi-vendor, analize avansate și integrare CRM.'],
            'price' => 15000,
            'category_id' => $ecommerceCat->id,
            'sku' => 'EC-ENT-001',
            'stock' => 999,
            'is_active' => true
        ]);

        Product::create([
            'name' => ['en' => 'Custom CRM', 'ro' => 'Sistem CRM Personalizat'],
            'slug' => 'custom-crm',
            'description' => ['en' => 'Tailored CRM system', 'ro' => 'Sistem CRM personalizat cu management contacte, pipeline vânzări și dashboard raportare.'],
            'price' => 12000,
            'category_id' => $webAppCat->id,
            'sku' => 'WA-CRM-001',
            'stock' => 999,
            'is_active' => true
        ]);

        Product::create([
            'name' => ['en' => 'Booking System', 'ro' => 'Sistem Rezervări'],
            'slug' => 'booking-system',
            'description' => ['en' => 'Online booking platform', 'ro' => 'Platformă rezervări online cu calendar, notificări automate și integrare plăți.'],
            'price' => 7500,
            'sale_price' => 6999,
            'category_id' => $webAppCat->id,
            'sku' => 'WA-BS-001',
            'stock' => 999,
            'is_active' => true
        ]);

        $this->command->info('✅ Created categories and products');
    }
}
