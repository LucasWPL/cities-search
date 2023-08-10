<?php

namespace App\Http\Controllers;

use App\Http\Constants\Ufs;
use App\Http\Services\CitiesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UfController extends Controller
{
    public function listCities(string $uf, CitiesService $citiesService, Request $request): JsonResponse
    {
        if (!in_array(strtoupper($uf), Ufs::POSSIBLES)) {
            throw new HttpException(400, 'UF inválida');
        }
        $cities = $citiesService->listCitiesOfUf($uf);

        $perPage = $request->input('per_page', 100);
        $page = $request->input('page', 1);

        return response()->json($this->paginateResults($perPage, $page, $cities));
    }
}
