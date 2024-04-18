<?php

namespace Modules\Payment\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            'source' => ValidationRuleHelper::stringRules(),
            'holder_name' => ValidationRuleHelper::stringRules([
                'required' => 'sometimes',
            ]),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
