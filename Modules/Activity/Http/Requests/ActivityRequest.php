<?php

namespace Modules\Activity\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Activity\Enums\ActivityDayEnum;
use Modules\Activity\Enums\ActivityTypeEnum;

class ActivityRequest extends FormRequest
{
    use HttpResponse;

    public function rules(): array
    {
        $inUpdate = ! preg_match('/.*activities$/', $this->url());

        $type = $this->type;
        $rules = [
            'type' => ValidationRuleHelper::enumRules(ActivityTypeEnum::availableTypes()),
            'name' => ValidationRuleHelper::stringRules(),
            'price' => ValidationRuleHelper::doubleRules([
                'required' => $type != ActivityTypeEnum::COURSE ? 'required' : 'exclude',
            ]),
            'discount' => ValidationRuleHelper::percentageRules([
                'required' => 'nullable',
            ]),
            'description' => ValidationRuleHelper::longTextRules(),
            'city_id' => ValidationRuleHelper::foreignKeyRules(),
            'category_id' => ValidationRuleHelper::foreignKeyRules([
                'required' => ! in_array($type, [ActivityTypeEnum::EVENT, ActivityTypeEnum::TRIP])
                    ? 'required'
                    : 'exclude',
            ]),
            'features' => ValidationRuleHelper::arrayRules([
                'required' => 'nullable',
            ]),
            'features.*' => ValidationRuleHelper::stringRules([
                'required' => 'sometimes',
            ]),
            'social_links' => ValidationRuleHelper::arrayRules([
                'required' => 'nullable',
            ]),
            'social_links.*' => ValidationRuleHelper::urlRules(false, [
                'required' => 'sometimes',
            ]),
            'phone' => ValidationRuleHelper::phoneRules([
                'required' => 'nullable',
            ]),
            'fax' => ValidationRuleHelper::phoneRules([
                'required' => 'nullable',
            ]),
            'website' => ValidationRuleHelper::urlRules(false),
            'main_image' => ValidationRuleHelper::storeOrUpdateImageRules($inUpdate),
            'other_images' => ValidationRuleHelper::arrayRules(['required' => 'sometimes']),
            'other_images.*' => ValidationRuleHelper::storeOrUpdateImageRules(true),
            'deleted_images_ids' => ValidationRuleHelper::arrayRules(['required' => 'sometimes']),
            'deleted_images_ids.*' => ValidationRuleHelper::foreignKeyRules([
                'required' => ! $inUpdate ? 'exclude' : 'sometimes',
            ]),
            'hold_at' => ValidationRuleHelper::dateTimeRules([
                'required' => in_array($type, [ActivityTypeEnum::EVENT, ActivityTypeEnum::TRIP])
                    ? 'required'
                    : 'exclude',
                'after_or_equal' => 'after_or_equal:now',
            ]),
        ];

        $this->addConditionalRules($rules, $type);

        return $rules;
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }

    private function addConditionalRules(array &$rules, string $type): void
    {
        if (! $this->address) {
            $rules['address'] = ValidationRuleHelper::arrayRules(['required' => 'exclude']);
        } else {
            $rules['address'] = ValidationRuleHelper::arrayRules();
            $rules['address.latitude'] = ValidationRuleHelper::latitudeRules();
            $rules['address.longitude'] = ValidationRuleHelper::longitudeRules();
            $rules['address.address'] = ValidationRuleHelper::addressRules();
        }

        if (in_array($type, [ActivityTypeEnum::COURSE, ActivityTypeEnum::SPORT])) {
            $rules['open_times'] = ValidationRuleHelper::arrayRules();
            $rules['open_times.*.day'] = ValidationRuleHelper::enumRules(ActivityDayEnum::toArray());
            $rules['open_times.*.from'] = ValidationRuleHelper::timeRules();
            $rules['open_times.*.to'] = ValidationRuleHelper::timeRules([
                'after' => 'after:open_times.*.from',
            ]);
        } else {
            $rules['open_times'] = ValidationRuleHelper::arrayRules(['required' => 'exclude']);
        }

        if ($type == ActivityTypeEnum::COURSE) {
            $rules['course_bundles'] = ValidationRuleHelper::arrayRules();

            $rules['course_bundles.*.price'] = ValidationRuleHelper::doubleRules();
            $rules['course_bundles.*.discount'] = ValidationRuleHelper::percentageRules([
                'required' => 'sometimes',
            ]);
            $rules['course_bundles.*.sessions_count'] = ValidationRuleHelper::integerRules();
            //            $rules['course_bundles.*.description'] = ValidationRuleHelper::longTextRules();
            //            $rules['course_bundles.*.name'] = ValidationRuleHelper::stringRules();
        } else {
            $rules['course_bundles'] = ValidationRuleHelper::arrayRules(['required' => 'exclude']);
        }
    }
}
