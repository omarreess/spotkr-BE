<?php

namespace Modules\Auth\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\UserTypeEnum;

class ProfileRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $uniqueColumn = AuthEnum::UNIQUE_COLUMN;
        $shouldUpdateReadonlyData = UserTypeEnum::getUserType() == UserTypeEnum::ADMIN;
        $usersTable = (new User())->getTable();

        return [
            'name' => ValidationRuleHelper::stringRules(),
            $uniqueColumn => ValidationRuleHelper::phoneRules([
                'unique' => ValidationRuleHelper::getUniqueColumn(
                    true,
                    $usersTable,
                    auth()->id(),
                    'phone',
                ),
                'required' => $shouldUpdateReadonlyData ? 'sometimes' : 'exclude',
            ]),
            'username' => ValidationRuleHelper::usernameRules([
                'unique' => ValidationRuleHelper::getUniqueColumn(
                    true,
                    $usersTable,
                    auth()->id(),
                    'username',
                ),
                'required' => $shouldUpdateReadonlyData ? 'sometimes' : 'exclude',
            ]),
            'bio' => ValidationRuleHelper::longTextRules([
                'required' => 'nullable',
            ]),
            'social_links' => ValidationRuleHelper::arrayRules([
                'required' => 'sometimes'
            ]),
            'social_links.*' => ValidationRuleHelper::urlRules(false, [
                'required' => 'sometimes',
            ]),
            'avatar' => ValidationRuleHelper::storeOrUpdateImageRules(true),
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
