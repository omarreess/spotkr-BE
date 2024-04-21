<?php

namespace Modules\Activity\Helpers;

use App\Exceptions\ValidationErrorsException;
use Modules\Activity\Entities\Activity;
use Modules\Activity\Enums\ActivityTypeEnum;

class ActivityHelper
{
    public static function calculatePrice(Activity $activity, int|null $sessionsCount): float|int
    {
        $price = self::getPrice($activity, $sessionsCount);

        if ($activity->discount) {
            $price = ($price * (float)$activity->discount) / 100;
        }

        return $price;
    }

    /**
     * @throws ValidationErrorsException
     */
    public static function getPrice(Activity $activity, int|null $sessionsCount)
    {
        $price = $activity->price;

        if($activity->type == ActivityTypeEnum::COURSE)
        {
            $price = collect($activity['course_bundles'])
                ->filter(fn($bundle) => $bundle['sessions_count'] == $sessionsCount)
                ->first()['price']
                ?? null;

            if(is_null($price))
            {
                throw new ValidationErrorsException([
                    'sessions_count' => 'sessions count not exists',
                ]);
            }
        }

        return $price;
    }
}
