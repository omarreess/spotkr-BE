<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Modules\Activity\Entities\Activity;
use Modules\Activity\Enums\ActivityTypeEnum;

class OrderRequest extends FormRequest
{
    use HttpResponse;

    public function rules(): array
    {
        $activity = Activity::query()->whereValidForClient()->findOrFail($this->activity_id);

        return [
            'activity_id' => ValidationRuleHelper::foreignKeyRules(),
            'adults_count' => ValidationRuleHelper::integerRules([
                'required' => $this->isCourse($activity) ? 'exclude' : 'required',
            ]),
            'children_count' => ValidationRuleHelper::integerRules([
                'required' => $this->isCourse($activity) ? 'exclude' : 'required',
                'min' => 'min:0',
            ]),
            'calendar_date' => ValidationRuleHelper::dateRules([
//                'required' => $this->isCourse($activity) ? 'exclude' : 'required',
            ]),
            'coupon' => ValidationRuleHelper::stringRules([
                'required' => 'sometimes',
            ]),
            'user_details' => ValidationRuleHelper::arrayRules(),
            'user_details.name' => ValidationRuleHelper::stringRules(),
            'user_details.phone' => ValidationRuleHelper::phoneRules(),
            'sessions_count' => ValidationRuleHelper::integerRules([
                'required' => $this->isCourse($activity) ? 'required' : 'exclude',
            ]),
            'credit_card_id' => ValidationRuleHelper::foreignKeyRules(),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }

    public function isCourse(Activity $activity)
    {
        return $activity->type == ActivityTypeEnum::COURSE;
    }
}
