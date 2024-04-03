<?php

namespace Modules\Auth\Exceptions;

use App\Exceptions\ValidationErrorsException;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Modules\Auth\Abstracts\AbstractAuthException;
use Symfony\Component\HttpFoundation\Response;

class LoginException extends AbstractAuthException
{
    use HttpResponse;

    /**
     * @throws ValidationErrorsException
     */
    public function wrongCredentials()
    {
        return $this->errorResponse(
            null,
            Response::HTTP_UNAUTHORIZED,
            translate_word('wrong_credentials'),
        );
    }

    /**
     * @throws ValidationErrorsException
     */
    public function frozenAccount(): JsonResponse
    {
        return $this->errorResponse(
            null,
            Response::HTTP_FORBIDDEN,
            translate_word('frozen'),
            additional: ['verified' => true]
        );
    }

    /**
     * @throws ValidationErrorsException
     */
    public function notVerified()
    {
        $this->throwValidationException([
            'account' => translate_word('not_verified'),
        ]);
    }
}
