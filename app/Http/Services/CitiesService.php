<?php

namespace App\Http\Services;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CitiesService
{
    public function listCitiesOfUf(string $uf): array
    {
        $cacheKey = 'cities_' . strtoupper($uf);
        if (Cache::has($cacheKey)) {
            Log::info('Recuperando dados do cache', ['key', $cacheKey]);

            return Cache::get($cacheKey);
        }

        try {
            $response = Http::get($this->getServiceUrl($uf));
            $response->throw();

            $cities = json_decode($response->body(), true);
            $this->saveOnCache($cities, $cacheKey);

            return $cities;
        } catch (RequestException $exception) {
            Log::error('Erro na chamada à API', ['error_message' => $exception->getMessage()]);

            throw new \DomainException('Ocorreu um erro na chamada à API', 500);
        }
    }

    private function saveOnCache(array $data, string $key): void
    {
        Log::info('Inserindo dados no cache', ['key', $key]);
        Cache::put($key, $data, 60 * 60 * 24);
    }

    private function getServiceUrl(string $uf): string
    {
        return str_replace(':uf', $uf, env('CITIES_SERVICE_URL'));
    }
}
