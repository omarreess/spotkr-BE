<?php

namespace Modules\Auth\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;

class CompleteSignUpRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        $currentUser = auth()->user();
        $usersTable = (new User())->getTable();

        return [
            'username' => ValidationRuleHelper::usernameRules([
                'required' => is_null($currentUser->username) ? 'required' : 'exclude',
                'unique' => ValidationRuleHelper::getUniqueColumn(
                    false,
                    $usersTable,
                    null,
                    'username',
                )
            ]),
            'phone' => ValidationRuleHelper::phoneRules([
                'required' => is_null($currentUser->phone) ? 'required' : 'exclude',
                'unique' => ValidationRuleHelper::getUniqueColumn(
                    false,
                    $usersTable,
                    null,
                    'phone',
                )
            ]),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
