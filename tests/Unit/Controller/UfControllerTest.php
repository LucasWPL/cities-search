<?php

use Tests\TestCase;
use App\Http\Controllers\UfController;
use App\Http\Services\CitiesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UfControllerTest extends TestCase
{
    /** @var MockObject&CitiesService */
    private CitiesService $citiesServiceMock;

    /** @var MockObject&Request */
    private Request $requestMock;

    protected function setUp(): void
    {
        $this->citiesServiceMock = $this->createMock(CitiesService::class);
        $this->requestMock = $this->createMock(Request::class);

        parent::setUp();
    }

    public function testListCitiesWithValidUf()
    {
        $perPage = 10;
        $page = 1;
        $cities = ['city1', 'city2'];

        $this->citiesServiceMock->expects($this->once())
            ->method('listCitiesOfUf')
            ->willReturn($cities);

        $this->requestMock->expects($this->exactly(2))
            ->method('input')
            ->willReturnCallback(
                fn ($input, $value) =>
                match ([$input, $value]) {
                    ['per_page', 100] => $perPage,
                    ['page', 1] => $page
                }
            );

        $ufController = $this->getUfControllerInstance();
        $response = $ufController->listCities('RN', $this->citiesServiceMock, $this->requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertJsonStringEqualsJsonString(json_encode([
            'data' => $cities,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => count($cities),
            'last_page' => 1,
        ]), $response->getContent());
    }

    public function testListCitiesWithPagination()
    {
        $perPage = 50;
        $page = 2;
        $cities = array_fill(0, 150, 'city');

        $this->citiesServiceMock->expects($this->once())
            ->method('listCitiesOfUf')
            ->willReturn($cities);

        $this->requestMock->expects($this->exactly(2))
            ->method('input')
            ->willReturnCallback(
                fn ($input, $value) =>
                match ([$input, $value]) {
                    ['per_page', 100] => $perPage,
                    ['page', 1] => $page
                }
            );

        $ufController = $this->getUfControllerInstance();
        $response = $ufController->listCities('RN', $this->citiesServiceMock, $this->requestMock);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'data' => array_fill(0, 50, 'city'),
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => 150,
            'last_page' => 3,
        ]), $response->getContent());
    }

    public function testListCitiesWithInvalidUf()
    {
        $invalidUf = 'INVALID_UF';

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('UF inválida');

        $ufController = $this->getUfControllerInstance();
        $ufController->listCities($invalidUf, $this->citiesServiceMock, $this->requestMock);
    }

    private function getUfControllerInstance(): UfController
    {
        return new UfController();
    }
}
