<?php

namespace App\Http\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CitiesService
{
    private string $primaryApiUrl;
    private string $fallbackApiUrl;

    public function __construct()
    {
        $this->primaryApiUrl = env('CITIES_SERVICE_URL');
        $this->fallbackApiUrl = env('CITIES_SERVICE_URL_FALLBACK');
    }

    public function listCitiesOfUf(string $uf): array
    {
        $cacheKey = 'cities_' . strtoupper($uf);
        if (Cache::has($cacheKey)) {
            Log::info('Recuperando dados do cache', ['key' => $cacheKey]);
            return Cache::get($cacheKey);
        }

        try {
            $cities = $this->fetchCitiesFromApi($this->getServiceUrl($uf, $this->primaryApiUrl));
            $this->saveOnCache($cities, $cacheKey);

            return $cities;
        } catch (RequestException $primaryException) {
            try {
                $cities = $this->fetchCitiesFromApi($this->getServiceUrl($uf, $this->fallbackApiUrl));
                $this->saveOnCache($cities, $cacheKey);

                return $cities;
            } catch (RequestException $fallbackException) {
                Log::error('Erro na chamada às APIs', [
                    'primary_error_message' => $primaryException->getMessage(),
                    'fallback_error_message' => $fallbackException->getMessage()
                ]);

                throw new HttpException(500, 'Ocorreu um erro na chamada às APIs');
            }
        }
    }

    private function fetchCitiesFromApi(string $url): array
    {
        $response = Http::get($url);
        $response->throw();

        $cities = json_decode($response->body(), true);
        return $this->parseCityData($cities);
    }

    private function parseCityData(array $rawCities): array
    {
        $parsedCities = [];
        foreach ($rawCities as $rawCity) {
            $parsedCities[] = [
                'name' => $rawCity['nome'],
                'ibge_code' => $rawCity['id'] ?? $rawCity['codigo_ibge'],
            ];
        }

        return $parsedCities;
    }

    private function saveOnCache(array $data, string $key): void
    {
        Log::info('Inserindo dados no cache', ['key' => $key]);
        Cache::put($key, $data, 60 * 60 * 24);
    }

    private function getServiceUrl(string $uf, string $apiUrl): string
    {
        return str_replace(':uf', $uf, $apiUrl);
    }
}
