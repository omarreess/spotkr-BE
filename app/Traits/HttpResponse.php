<?php

namespace App\Traits;

use Elattar\Prepare\Traits\HttpResponse as BaseHttpResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait HttpResponse
{
    use BaseHttpResponse {
        BaseHttpResponse::paginatedResponse as basePaginatedResponse;
    }

    public function paginatedResponse(
        LengthAwarePaginator $collection,
        string $resourceClass,
        bool $isCollection = false,
        string $message = 'Data Fetched Successfully',
        int $code = Response::HTTP_OK
    ): JsonResponse {
        $data = [
            'data' => $isCollection ? new $resourceClass($collection->items()) : $resourceClass::collection($collection->items()),
            'links' => [
                'first' => $collection->url(1),
                'last' => $collection->url($collection->lastPage()),
                'next' => $collection->nextPageUrl(),
                'prev' => $collection->previousPageUrl(),
            ],
            'meta' => [
                'current_page' => $collection->currentPage(),
                'from' => $collection->firstItem(),
                'last_page' => $collection->lastPage(),
                'total' => $collection->total(),
            ],
            'message' => $message,
            'code' => $code,
            'type' => 'success',
        ];

        return response()->json($data, $code);
    }
}
