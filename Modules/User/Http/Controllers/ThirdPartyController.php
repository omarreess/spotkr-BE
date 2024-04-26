<?php

namespace Modules\User\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Transformers\UserResource;
use Modules\User\Http\Requests\ChangeStatusRequest;

class ThirdPartyController extends Controller
{
    use HttpResponse;

    public function thirdParties(): JsonResponse
    {
        $thirdParties = User::query()
            ->whereIsThirdParty()
            ->searchable(['name', 'email', 'phone'])
            ->latest()
            ->paginatedCollection();

        return $this->paginatedResponse($thirdParties, UserResource::class);
    }

    public function changeStatus(ChangeStatusRequest $request, $thirdParty)
    {
        User::query()->whereIsThirdParty()->findOrFail($thirdParty)->forceFill([
            'status' => $request->status,
        ])
            ->save();

        return $this->okResponse(message: translate_success_message('status', 'updated'));
    }
}
