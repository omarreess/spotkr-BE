<?php

namespace Modules\LeaderBoard\Services;

use Modules\Activity\Entities\Activity;
use Modules\Activity\Enums\ActivityTypeEnum;
use Modules\LeaderBoard\Entities\Achievement;

class AchievementService
{
    public function updateTotalAchievements(Activity $activity, $user = null)
    {
        $user = $user ?: auth()->user();

        if ($activity->type == ActivityTypeEnum::SPORT) {
            $achievement = Achievement::query()
                ->where('user_id', $user->id)
                ->whereColumn('gained_points', '<', 'required_points')
                ->oldest('required_points')
                ->first();

            if (! $achievement) {
                $achievement = $user->achievements()->latest('required_points')->first();
            }

            $achievement->forceFill([
                'gained_points' => $achievement->gained_points + 1,
            ])
                ->save();
        }
    }
}
