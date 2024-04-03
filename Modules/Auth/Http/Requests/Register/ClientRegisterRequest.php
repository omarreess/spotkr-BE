<?php

namespace Modules\Auth\Http\Requests\Register;

use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ClientRegisterRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            ...BaseRegister::baseRules(true),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
