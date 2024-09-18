<?php
// tests/Feature/UrlTest.php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Url;

class UrlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_short_url()
    {
        $response = $this->postJson('/api/urls', [
            'originalUrl' => 'https://example.com'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['shortUrl']);
    }

    /** @test */
    public function it_can_redirect_to_original_url()
    {
        $url = Url::factory()->create([
            'original_url' => 'https://example.com',
            'code' => 'abc12345'
        ]);

        $response = $this->get('/r/abc12345');

        $response->assertRedirect($url->original_url);
    }
}