<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Url;

class UrlIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_retrieve_a_shortened_url()
    {
        // Crea una URL acortada en la base de datos
        $url = Url::create([
            'code' => '123abc',
            'original_url' => 'https://example.com'
        ]);

        // Simula una solicitud GET para obtener la URL acortada
        $response = $this->get('/api/urls/123abc');

        // Verifica que el código de respuesta sea 200 y que se obtenga la URL correcta
        $response->assertStatus(200);
        $response->assertJson([
            'original_url' => 'https://example.com'
        ]);
    }
}
