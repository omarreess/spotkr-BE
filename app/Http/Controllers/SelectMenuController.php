<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Category\Entities\Category;
use Modules\Category\Services\CategoryService;
use Modules\Category\Transformers\CategoryResource;
use Modules\Country\Entities\City;
use Modules\Country\Entities\Country;
use Modules\Country\Transformers\CityResource;

class SelectMenuController extends Controller
{
    use HttpResponse;

    public function countries(): JsonResponse
    {
        return $this->resourceResponse(
            Country::latest()->searchable()->get(['id', 'name'])
        );
    }

    public function cities($country): JsonResponse
    {
        return $this->resourceResponse(
            City::whereCountryId($country)->get(['id', 'name'])
        );
    }

    public function parentCategories(): JsonResponse
    {
        return $this->resourceResponse(
            CategoryResource::collection(Category::whereNull('parent_id')->get())
        );
    }

    public function subCategories($parentCategory): JsonResponse
    {
        return $this->resourceResponse(
            CategoryResource::collection(Category::whereNotNull('parent_id')->where('parent_id', $parentCategory)->get())
        );
    }
}
