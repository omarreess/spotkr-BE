<?php

namespace Modules\Country\Services;

use App\Exceptions\ValidationErrorsException;
use Modules\Country\Entities\Country;

class CountryService
{
    private Country $countryModel;

    public function __construct(Country $countryModel)
    {
        $this->countryModel = $countryModel;
    }

    /**
     * @throws ValidationErrorsException
     */
    public function countryExists($countryId, string $errorKey = 'country_id')
    {
        $country = $this->countryModel::query()->find($countryId);

        if(! $country)
        {
            throw new ValidationErrorsException([
                $errorKey => translate_error_message('country', 'not_exists'),
            ]);
        }

        return $country;
    }
}
