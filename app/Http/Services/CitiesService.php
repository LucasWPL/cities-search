<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class CitiesService
{
    public function listCitiesOfUf(string $uf)
    {
        $url = $this->getServiceUrl($uf);
        $response = Http::get($url);

        return json_decode($response->body(), true);
    }

    private function getServiceUrl(string $uf): string
    {
        return str_replace(':uf', $uf, env('CITIES_SERVICE_URL'));
    }
}
