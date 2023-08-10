<?php

namespace App\Http\Controllers;

use DomainException;
use App\Http\Constants\Ufs;
use App\Http\Services\CitiesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UfController extends Controller
{
    public function listCities(string $uf, CitiesService $citiesService, Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 100);
        $page = $request->input('page', 1);

        if (!in_array(strtoupper($uf), Ufs::POSSIBLES)) {
            throw new DomainException('UF inválida', 400);
        }
        $cities = $citiesService->listCitiesOfUf($uf);

        return response()->json($this->paginateResults($perPage, $page, $cities));
    }
}
