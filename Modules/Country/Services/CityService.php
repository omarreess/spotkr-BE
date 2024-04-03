<?php

namespace Modules\Country\Services;

use Modules\Country\Entities\City;

class CityService
{
    public City $cityModel;

    public function __construct(City $city)
    {
        $this->cityModel = $city;
    }

    public function popular()
    {
        $egyptCities = City::query()
            ->whereBelongsToEgypt()
            ->with('image')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $randomCities = City::query()
            ->inRandomOrder()
            ->with('image')
            ->limit(2)
            ->get();

        $cities = $egyptCities->merge($randomCities);

        $cities->shuffle();

        return $cities;
    }

    public function index()
    {

    }

    private function randomCities(bool $paginated = true)
    {

    }
}
