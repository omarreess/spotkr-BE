<?php

namespace Modules\LeaderBoard\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notification;
use Illuminate\Routing\Controller;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Transformers\UserResource;
use Modules\FcmNotification\Notifications\FcmNotification;
use Modules\LeaderBoard\Http\Requests\LeaderboardFilterRequest;
use Modules\LeaderBoard\Services\ClientLeaderBoardService;

class AdminLeaderboardController extends Controller
{
    use HttpResponse;

    public function index(LeaderboardFilterRequest $request, ClientLeaderBoardService $clientLeaderBoardService)
    {
        return $this->paginatedResponse(
            $clientLeaderBoardService->index($request->validated()),
            UserResource::class,
        );
    }

    public function markAsWinner(): JsonResponse
    {
        $userId = request('user_id');
        $user = User::query()
            ->whereNotNull(AuthEnum::VERIFIED_AT)
            ->where('type', UserTypeEnum::CLIENT)
            ->where('status', true)
            ->whereNotBetween('last_winning_time', [now()->startOfMonth(), now()->endOfMonth()])
            ->findOrFail($userId);

        $totalWinningUsers = User::query()
            ->whereBetween('last_winning_time', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        if($totalWinningUsers < 3)
        {
            $user->forceFill(['last_winning_time' => now()])->save();

            $user->notify(new FcmNotification(
                'user_won_title',
                'user_won_body',
                additionalData: [
                    'model_id' => null,
                ],
                shouldTranslate: [
                    'title' => true,
                    'body' => true,
                ]
            ));

            return $this->okResponse(message: translate_word('user_won'));
        }

        return $this->validationErrorsResponse([
            'max_users_exceeded' => translate_word('max_users_exceeded', [
                'number' => 3,
            ]),
        ]);
    }
}
