<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_authentication(): void
    {
        $response = $this->getJson('/api/posts');
        $response->assertStatus(401);
    }

    public function test_api_posts_list(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $response = $this->withHeader('Authorization', "Bearer {$token}")->getJson('/api/posts');
        $response->assertStatus(200);
    }
}
