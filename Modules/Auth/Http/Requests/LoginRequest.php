<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Enums\AuthEnum;

class LoginRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $fromMobile = ! preg_match('/.*dashboard$/', $this->url());

        return [
            AuthEnum::UNIQUE_COLUMN => [
                'required'
            ],
            'password' => [
                $fromMobile ? 'exclude': 'required',
            ],
            'one_time_password' => [
                $fromMobile ? 'required': 'exclude',
            ],
            'fcm_token' => [
                'nullable',
                'string'
            ],
        ];
    }

    /**
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
