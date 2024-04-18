<?php

namespace Modules\Payment\Helpers;

use App\Traits\HttpResponse;
use Exception;
use Illuminate\Support\Str;
use Stripe\Exception\InvalidArgumentException;
use Stripe\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Response;

class StripeExceptionHelper
{
    use HttpResponse;

    const INVALID_ARGUMENT_EXCEPTION = 'invalid_argument_exception';

    const INVALID_REQUEST_EXCEPTION = 'invalid_request_exception';

    public static function customerNotFound(string $errorMessage): bool
    {
        return self::messageContains(
            $errorMessage,
            ['No such customer', 'The resource ID cannot be null or whitespace']
        );
    }

    public static function tokenNotFound(string $errorMessage): bool
    {
        return self::messageContains($errorMessage, ['No such token']);
    }

    public static function sourceNotFound(string $errorMessage)
    {
        return self::messageContains($errorMessage, ['No such source']);
    }

    public static function tokenUsedBefore(string $errorMessage)
    {
        return self::messageContains($errorMessage, ['You cannot use a Stripe token more than once']);
    }

    public static function messageContains(string $errorMessage, array|string $needles): bool
    {
        return Str::contains($errorMessage, $needles, true);
    }

    public static function getErrorObject(Exception $exception)
    {
        $result = [];
        $errorMessage = $exception->getMessage();

        $exceptionClass = get_class($exception);

        switch ($exceptionClass) {
            case InvalidArgumentException::class:

                $result['type'] = self::INVALID_ARGUMENT_EXCEPTION;

                switch ($exception->getMessage()) {
                    case self::customerNotFound($errorMessage):
                        $result['msg'] = translate_error_message('customer', 'not_exists');
                        break;
                }

                if (! isset($result['msg'])) {
                    $result['msg'] = $exception->getMessage();
                }

                break;

            case InvalidRequestException::class:

                $result['type'] = self::INVALID_REQUEST_EXCEPTION;

                switch ($exception->getMessage()) {
                    case self::tokenNotFound($errorMessage):
                        $result['msg'] = translate_word('invalid_stripe_token');
                        break;

                    case self::tokenUsedBefore($errorMessage):
                        $result['msg'] = translate_word('stripe_token_used_before');
                        break;

                    case self::sourceNotFound($errorMessage):
                        $result['msg'] = translate_error_message('credit_card', 'not_found');
                        break;
                }

                if (! isset($result['msg'])) {
                    $result['msg'] = $exception->getMessage();
                }

                break;

            default:

                $result['type'] = 'unknown';
                $result['message'] = $errorMessage;

        }

        return $result;
    }

    public function returnStripeError(array $errorObject)
    {
        switch ($errorObject) {
            case isset($errorObject['type']) && $errorObject['type'] == self::INVALID_REQUEST_EXCEPTION:
            case isset($errorObject['type']) && $errorObject['type'] == self::INVALID_ARGUMENT_EXCEPTION:

                return $this->errorResponse(code: Response::HTTP_BAD_REQUEST, message: $errorObject['msg']);

            default:
                return $this->errorResponse(message: $errorObject['msg'] ?? 'unknown');

        }
    }

    public static function hasErrors(array $response)
    {
        return isset($response['type']) && $response['type'] != 'unknown';
    }
}
