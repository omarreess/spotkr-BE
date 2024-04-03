<?php

namespace Modules\Auth\Http\Controllers;

use App\Exceptions\ValidationErrorsException;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Actions\Register\ClientRegisterAction;
use Modules\Auth\Actions\Register\ThirdPartyRegisterAction;
use Modules\Auth\Http\Requests\Register\ClientRegisterRequest;
use Modules\Auth\Http\Requests\Register\StoreRegisterRequest;
use Modules\Auth\Strategies\Verifiable;
use Throwable;

class RegisterController extends Controller
{
    use HttpResponse;

    private Verifiable $verifiable;

    public function __construct(Verifiable $verifiable)
    {
        $this->verifiable = $verifiable;
    }

    /**
     * @throws Throwable
     * @throws ValidationErrorsException
     */
    public function client(ClientRegisterRequest $request, ClientRegisterAction $registerService): JsonResponse
    {
        $registerService->handle($request->validated(), $this->verifiable);

        return $this->baseReturn();
    }

    public function thirdParty(ClientRegisterRequest $request, ThirdPartyRegisterAction $thirdRegisterAction): JsonResponse
    {
        $thirdRegisterAction->handle($request->validated(), $this->verifiable);

        return $this->baseReturn();
    }

    private function baseReturn(): JsonResponse
    {
        return $this->createdResponse(
            message: translate_success_message('user', 'created')
            .' '.translate_word('user_verification_sent')
        );
    }
}
