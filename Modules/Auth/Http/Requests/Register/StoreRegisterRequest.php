<?php

namespace Modules\Auth\Http\Requests\Register;

use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegisterRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            ...BaseRegister::baseRules(true),
            ...self::storeRules(),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }

    public static function storeRules($inUpdate = false)
    {
        return [
            'latitude' => ValidationRuleHelper::latitudeRules(),
            'longitude' => ValidationRuleHelper::longitudeRules(),
            'store_name' => ValidationRuleHelper::stringRules(),
            'commercial_register' => ValidationRuleHelper::storeOrUpdateImageRules($inUpdate),
            'tax_card' => ValidationRuleHelper::storeOrUpdateImageRules($inUpdate),
            'store_logo' => ValidationRuleHelper::storeOrUpdateImageRules($inUpdate),
        ];
    }
}
