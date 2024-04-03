<?php

namespace Modules\Auth\Http\Controllers;

use App\Exceptions\ValidationErrorsException;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Http\Requests\CodeSendRequest;
use Modules\Auth\Http\Requests\VerifyUserRequest;
use Modules\Auth\Services\VerifyUserService;
use Modules\Auth\Strategies\Verifiable;
use Modules\Otp\Contracts\OtpContract;

class VerifyController extends Controller
{
    use HttpResponse;

    private Verifiable $verifiable;

    public function __construct(Verifiable $verifiable)
    {
        $this->verifiable = $verifiable;
    }

    public function send(CodeSendRequest $request): JsonResponse
    {
        $handle = $request->handle;

        DB::transaction(fn() => $this->verifiable->sendCode($handle));

        return $this->okResponse(message: translate_word('resend_verify_code'));
    }

    public function verify(VerifyUserRequest $request): JsonResponse
    {
        $handle = $request->handle;
        $code = $request->code;

        DB::transaction(fn() => $this->verifiable->verifyCode($handle, $code));

        return $this->okResponse(message: translate_word('verified'));
    }

    /**
     * @throws ValidationErrorsException
     */
    public function sendOneTimePassword(CodeSendRequest $request, VerifyUserService $verifyUserService)
    {
        $verifyUserService->sendOneTimePassword($request->handle);

        return $this->okResponse(message: translate_word('one_time_password_sent'));
    }
}
