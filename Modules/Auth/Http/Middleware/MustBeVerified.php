<?php

namespace Modules\Auth\Http\Middleware;

use App\Traits\HttpResponse;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Auth\Enums\AuthEnum;
use Symfony\Component\HttpFoundation\Response;

class MustBeVerified
{
    use HttpResponse;

    /**
     * @return JsonResponse|mixed
     *
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! auth()->check()) {
            $this->throwNotAuthenticated();
        }

        if (AuthEnum::userShouldBeVerified(auth()->user())) {
            return $this->errorResponse(
                null,
                Response::HTTP_FORBIDDEN,
                translate_error_message('user', 'not_verified'),
                additional: ['verified' => false]
            );
        }

        return $next($request);
    }
}
