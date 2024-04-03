<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Country\Entities\City;
use Modules\Country\Entities\Country;
use Modules\Country\Transformers\CityResource;

class SelectMenuController extends Controller
{
    use HttpResponse;

    public function countries(): \Illuminate\Http\JsonResponse
    {
        return $this->resourceResponse(
            Country::latest()->searchable()->get(['id', 'name'])
        );
    }

    public function cities($country): \Illuminate\Http\JsonResponse
    {
        return $this->resourceResponse(
            City::whereCountryId($country)->get(['id', 'name'])
        );
    }
}
