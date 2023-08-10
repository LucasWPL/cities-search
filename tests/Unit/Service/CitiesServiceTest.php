<?php

use Tests\TestCase;
use App\Http\Services\CitiesService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CitiesServiceTest extends TestCase
{
    public function testListCitiesOfUfSuccess()
    {
        $apiResponse = json_encode(['city1', 'city2']);
        Http::fake([
            '*' => Http::response($apiResponse, 200)
        ]);

        $service = new CitiesService();
        $result = $service->listCitiesOfUf('RN');

        $this->assertEquals(['city1', 'city2'], $result);
        $this->assertTrue(Cache::has('cities_RN'));
        $this->assertEquals(['city1', 'city2'], Cache::get('cities_RN'));
    }

    public function testListCitiesOfUfApiError()
    {
        Http::fake([
            '*' => Http::response([], 500)
        ]);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Ocorreu um erro na chamada à API');

        $service = new CitiesService();
        $service->listCitiesOfUf('RN');
    }
}
