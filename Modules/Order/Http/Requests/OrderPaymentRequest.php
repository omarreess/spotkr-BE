<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;

class OrderPaymentRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            'credit_card_id' => ValidationRuleHelper::foreignKeyRules(),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
