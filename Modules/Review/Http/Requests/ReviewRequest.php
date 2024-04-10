<?php

namespace Modules\Review\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Modules\Review\Enums\ReviewTypeEnum;

class ReviewRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            'reviewable_type' => ValidationRuleHelper::enumRules(ReviewTypeEnum::toArray()),
            'reviewable_id' => ValidationRuleHelper::foreignKeyRules(),
            'description' => ValidationRuleHelper::longTextRules(),
            'rating' => ValidationRuleHelper::doubleRules([
                'max' => 'max:5',
            ]),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
