<?php

namespace Tests\Integration;

use Tests\TestCase;

class UfControllerTest extends TestCase
{
    public function testListCitiesSuccess(): void
    {
        $response = $this->get('/api/uf/RN/listar-municipios');
        $response->assertStatus(200);
    }

    public function testListCitiesReturnsPaginatedResults()
    {
        $response = $this->get('/api/uf/RN/listar-municipios?per_page=5&page=2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'current_page',
                'per_page',
                'total',
                'last_page',
            ]);
    }

    public function testListCitiesBadRequest()
    {
        $response = $this->get('/api/uf/InvalidUF/listar-municipios');
        $response->assertStatus(400);
    }
}
