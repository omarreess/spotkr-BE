<?php

namespace Modules\Activity\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StatusRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            'status' => ValidationRuleHelper::booleanRules(),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
