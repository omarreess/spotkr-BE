<?php

namespace Modules\LeaderBoard\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Transformers\UserResource;

class AchievementController extends Controller
{
    use HttpResponse;

    public function index($client)
    {
        $userAchievements = User::query()
            ->whereType(UserTypeEnum::CLIENT)
            ->where('status', true)
            ->with([
                'achievements' => function ($builder) {
                    $builder
                        ->oldest('required_points')
                        ->with('icon');
                },
                'avatar',
            ])
            ->select(['id', 'name', 'username', 'bio'])
            ->findOrFail($client);

        return $this->resourceResponse(UserResource::make($userAchievements));
    }
}
