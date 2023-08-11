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
        $apiResponse = [
            ['nome' => 'City1', 'codigo_ibge' => '123'],
            ['nome' => 'City2', 'codigo_ibge' => '456'],
        ];

        Http::fake([
            '*' => Http::response(json_encode($apiResponse), 200)
        ]);

        $service = new CitiesService();
        $result = $service->listCitiesOfUf('RN');

        $expectedResult = [
            ['name' => 'City1', 'ibge_code' => '123'],
            ['name' => 'City2', 'ibge_code' => '456'],
        ];

        $this->assertEquals($expectedResult, $result);
        $this->assertTrue(Cache::has('cities_RN'));
        $this->assertEquals($expectedResult, Cache::get('cities_RN'));
    }


    public function testListCitiesOfUfApiError()
    {
        Http::fake([
            '*' => Http::response([], 500)
        ]);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Ocorreu um erro na chamada às APIs');

        $service = new CitiesService();
        $service->listCitiesOfUf('RN');
    }
}
