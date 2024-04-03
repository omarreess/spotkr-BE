<?php

namespace Modules\Auth\Http\Requests\Register;

use App\Helpers\ValidationRuleHelper;
use App\Models\User;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BaseRegister extends FormRequest
{
    use HttpResponse;

    public function rules()
    {

    }

    public static function baseRules(bool $excludeAvatar = false, bool $inUpdate = false, $idValue = null): array
    {
        $usersTable = (new User())->getTable();

        return [
            'name' => ValidationRuleHelper::stringRules(),
            'phone' => ValidationRuleHelper::phoneRules([
                'unique' => ValidationRuleHelper::getUniqueColumn(
                    false,
                    $usersTable,
                    null,
                    'phone',
                )
            ]),
            'username' => ValidationRuleHelper::usernameRules([
                'unique' => ValidationRuleHelper::getUniqueColumn(
                    false,
                    $usersTable,
                    null,
                    'username',
                )
            ]),
            'avatar' => ValidationRuleHelper::storeOrUpdateImageRules($inUpdate, [
                'required',
            ]),
            'bio' => ValidationRuleHelper::longTextRules([
                'required' => 'nullable',
            ]),
            'social_links' => ValidationRuleHelper::arrayRules(),
            'social_links.*' => ValidationRuleHelper::urlRules(),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
