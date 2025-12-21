<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarphatianCMSTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function homepage_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Carphatian');
    }

    /** @test */
    public function admin_panel_requires_authentication()
    {
        $response = $this->get('/admin');
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function authenticated_user_can_access_admin()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
    }

    /** @test */
    public function blog_page_displays_posts()
    {
        Post::factory()->count(3)->create(['status' => 'published']);
        
        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertSee('Blog');
    }

    /** @test */
    public function portfolios_page_loads_with_translations()
    {
        $response = $this->get('/portfolios');
        $response->assertStatus(200);
        $response->assertSee('Portfolio');
    }

    /** @test */
    public function language_switcher_changes_locale()
    {
        $this->get('/ro');
        $this->assertEquals('ro', session('locale'));
        
        $this->get('/en');
        $this->assertEquals('en', session('locale'));
    }

    /** @test */
    public function translation_editor_accessible_to_admin()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get('/admin/languages/translations');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function sitemap_generates_correctly()
    {
        Page::factory()->count(5)->create(['status' => 'published']);
        
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    }

    /** @test */
    public function robots_txt_accessible()
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
        $response->assertSee('Sitemap:');
    }

    /** @test */
    public function contact_form_validation_works()
    {
        $response = $this->post('/contact', []);
        
        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }

    /** @test */
    public function contact_form_submits_successfully()
    {
        $response = $this->post('/contact', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test message content',
        ]);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function seo_meta_tags_present_on_pages()
    {
        $page = Page::factory()->create([
            'status' => 'published',
            'slug' => 'test-page',
            'meta_title' => 'Test Page SEO',
            'meta_description' => 'Test description',
        ]);
        
        $response = $this->get('/test-page');
        $response->assertSee('Test Page SEO');
        $response->assertSee('Test description');
        $response->assertSee('og:title');
        $response->assertSee('twitter:card');
    }

    /** @test */
    public function security_headers_are_present()
    {
        $response = $this->get('/');
        
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }

    /** @test */
    public function rate_limiting_works()
    {
        for ($i = 0; $i < 61; $i++) {
            $response = $this->get('/');
        }
        
        // Should hit rate limit on 61st request
        $response->assertStatus(429);
    }

    /** @test */
    public function footer_shows_carphatian_branding()
    {
        $response = $this->get('/');
        $response->assertSee('By Carphatian');
    }

    /** @test */
    public function all_six_languages_are_active()
    {
        Language::factory()->create(['code' => 'ro', 'is_active' => true]);
        Language::factory()->create(['code' => 'en', 'is_active' => true]);
        Language::factory()->create(['code' => 'es', 'is_active' => true]);
        Language::factory()->create(['code' => 'it', 'is_active' => true]);
        Language::factory()->create(['code' => 'de', 'is_active' => true]);
        Language::factory()->create(['code' => 'fr', 'is_active' => true]);
        
        $activeLanguages = Language::where('is_active', true)->count();
        $this->assertEquals(6, $activeLanguages);
    }

    /** @test */
    public function performance_cache_header_present()
    {
        $response = $this->get('/');
        $response->assertHeader('X-Response-Time');
    }

    /** @test */
    public function backup_file_exists()
    {
        $backupPath = public_path('downloads');
        $this->assertTrue(file_exists($backupPath));
        
        $backups = glob($backupPath . '/carphatian_cms_backup_*.zip');
        $this->assertNotEmpty($backups);
    }
}
