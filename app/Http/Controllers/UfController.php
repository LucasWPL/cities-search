<?php

namespace App\Http\Controllers;

use App\Http\Constants\Ufs;
use App\Http\Services\CitiesService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UfController extends Controller
{
    public function listCities(string $uf, CitiesService $citiesService)
    {
        if (!in_array(strtoupper($uf), Ufs::POSSIBLES)) {
            throw new HttpException(400, 'UF inválida');
        }
        $cities = $citiesService->listCitiesOfUf($uf);

        return response()->json($cities);
    }
}
