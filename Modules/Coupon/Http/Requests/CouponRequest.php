<?php

namespace Modules\Coupon\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Modules\Coupon\Entities\Coupon;

class CouponRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
//        $inUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        return [
            'code' => ValidationRuleHelper::usernameRules([
                'unique' => ValidationRuleHelper::getUniqueColumn(
                    false,
                    (new Coupon())->getTable(),
                    $this->route('coupon'),
                    'code',
                )
            ]),
            'number_of_users' => ValidationRuleHelper::integerRules([
                'required' => 'nullable',
            ]),
            'valid_till' => ValidationRuleHelper::dateTimeRules([
                'required' => 'nullable',
                'after' => 'after:now',
            ]),
            'status' => ValidationRuleHelper::booleanRules([
                'required' => 'sometimes',
            ]),
            'discount' => ValidationRuleHelper::percentageRules([
                'integer' => 'numeric',
            ]),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
