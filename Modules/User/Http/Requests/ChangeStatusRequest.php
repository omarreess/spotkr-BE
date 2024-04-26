<?php

namespace Modules\User\Http\Requests;

use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ChangeStatusRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            //
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
