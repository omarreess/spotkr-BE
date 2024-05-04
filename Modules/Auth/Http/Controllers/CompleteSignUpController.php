<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\CompleteSignUpRequest;

class CompleteSignUpController extends Controller
{
    use HttpResponse;

    public function __invoke(CompleteSignUpRequest $request): JsonResponse
    {
        auth()->user()->update($request->validated());

        return $this->okResponse(message: translate_success_message('profile', 'updated'));
    }
}
