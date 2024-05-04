<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Http\Requests\ProfileRequest;
use Modules\Auth\Services\ProfileService;
use Modules\Auth\Transformers\UserResource;

class ProfileController extends Controller
{
    use HttpResponse;

    public static function getUsersCollectionName(): string
    {
        return AuthEnum::AVATAR_COLLECTION_NAME;
    }

    public function handle(ProfileRequest $request, ProfileService $profileService): JsonResponse
    {
        $result = $profileService->handle($request->validated());

        if (is_bool($result) || isset($result['verified'])) {
            return $this->okResponse(message: translate_success_message('profile', 'updated'));
        }

        return $this->validationErrorsResponse($result);
    }

    public function show()
    {
        $loggedUserInfo = User::whereId(auth()->id())->with(['avatar'])->first();

        return $this->resourceResponse(new UserResource($loggedUserInfo));
    }
}
