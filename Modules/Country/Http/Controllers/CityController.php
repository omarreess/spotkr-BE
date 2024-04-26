<?php

namespace Modules\Country\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Country\Services\CityService;
use Modules\Country\Transformers\CityResource;

class CityController extends Controller
{
    use HttpResponse;

    private CityService $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function popular(): JsonResponse
    {
        $popularCities = $this->cityService->popular();

        return $this->resourceResponse(CityResource::collection($popularCities));
    }
}
