<?php

namespace App\Http\Controllers;

use DomainException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function paginateResults(int $perPage, int $page, array $data): array
    {
        $totalItems = count($data);
        $startIndex = ($page - 1) * $perPage;

        if ($startIndex >= $totalItems) {
            $message = "A página {$page} é inválida. Total de itens: {$totalItems}, Itens por página: {$perPage}";
            throw new DomainException($message, 400);
        }

        $endIndex = min($startIndex + $perPage - 1, $totalItems - 1);
        $paginatedData = array_slice($data, $startIndex, $endIndex - $startIndex + 1);

        return [
            'data' => $paginatedData,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $totalItems,
            'last_page' => ceil($totalItems / $perPage),
        ];
    }
}
