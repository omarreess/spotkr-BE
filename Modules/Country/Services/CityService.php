<?php

namespace Modules\Country\Services;

use App\Exceptions\ValidationErrorsException;
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

    /**
     * @throws ValidationErrorsException
     */
    public function cityExists($id, string $errorKey = 'city_id')
    {
        $city = City::query()
            ->where('id', $id)
            ->first();

        if (! $city) {
            throw new ValidationErrorsException([
                $errorKey => translate_error_message('city', 'not_exists'),
            ]);
        }

        return $city;
    }
}
