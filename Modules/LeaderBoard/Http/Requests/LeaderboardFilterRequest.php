<?php

namespace Modules\LeaderBoard\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LeaderboardFilterRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            'sub_category_id' => ['nullable'],
            'sub_sub_category_id' => ['nullable'],
            'country_id' => ['nullable'],
            'period' => ValidationRuleHelper::enumRules(['month', 'day', 'year', 'week'], [
                'required' => 'nullable',
            ]),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
