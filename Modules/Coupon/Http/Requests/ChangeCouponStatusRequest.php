<?php

namespace Modules\Coupon\Http\Requests;

use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ChangeCouponStatusRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
