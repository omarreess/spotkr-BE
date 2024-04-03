<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Http\Requests\SocialAuthRequest;
use Modules\Auth\Services\SocialiteService;
use Modules\Auth\Transformers\UserResource;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class SocialAuthController extends Controller
{
    use HttpResponse;

    private SocialiteService $socialite;

    public function __construct()
    {
        $this->socialite = new SocialiteService();
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function client(SocialAuthRequest $request)
    {
        return $this->handleProviderCallback($request->validated(), UserTypeEnum::CLIENT);
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function thirdParty(SocialAuthRequest $request)
    {
        return $this->handleProviderCallback($request->validated(), UserTypeEnum::THIRD_PARTY);
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function handleProviderCallback(array $data, string $type): JsonResponse
    {
        $result = $this->socialite->handleProviderCallback($data, $type);

        if (! $result instanceof User) {
            return $this->validationErrorsResponse($result);
        }

        return $this->okResponse(
            new UserResource($result),
            translate_success_message('user', 'logged_in')
        );
    }
}
