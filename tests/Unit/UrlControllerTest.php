<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_shortened_url()
    {
        // Simula una solicitud de URL válida
        $response = $this->post('/api/urls', [
            'originalUrl' => 'https://example.com'
        ]);

        // Verifica que el código de respuesta sea 201
        $response->assertStatus(201);

        // Verifica que se haya guardado la URL en la base de datos
        $this->assertDatabaseHas('urls', [
            'original_url' => 'https://example.com'
        ]);
    }
}
