<?php

namespace Modules\Review\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Modules\Review\Enums\ReviewTypeEnum;

class ReviewRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            'description' => ValidationRuleHelper::longTextRules([
                'required' => Rule::unless(! is_null($this->rating), 'required'),
            ]),
            'rating' => ValidationRuleHelper::doubleRules([
                'max' => 'max:5',
                'required' => Rule::unless(! is_null($this->description), 'required'),
            ]),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}