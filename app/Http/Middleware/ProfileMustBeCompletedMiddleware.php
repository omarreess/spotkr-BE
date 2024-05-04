<?php

namespace App\Http\Middleware;

use App\Traits\HttpResponse;
use Closure;
use Illuminate\Http\Request;
use Modules\Auth\Enums\UserTypeEnum;
use Symfony\Component\HttpFoundation\Response;

class ProfileMustBeCompletedMiddleware
{
    use HttpResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && UserTypeEnum::getUserType() != UserTypeEnum::ADMIN)
        {
            if(! auth()->user()->username)
            {
                return $this->forbiddenResponse('you must complete your profile first');
            }
        }

        return $next($request);
    }
}
