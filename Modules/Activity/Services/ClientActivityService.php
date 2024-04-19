<?php

namespace Modules\Activity\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\Activity\Entities\Activity;
use Modules\Activity\Enums\ActivityTypeEnum;

class ClientActivityService extends BaseActivityService
{
    public function index()
    {
        return $this
            ->baseIndex()
            ->inRandomOrder()
            ->forClient()
            ->paginatedCollection();
    }

    public function show($activity)
    {
        return $this
            ->baseShow()
            ->forClient()
            ->with([
                'thirdParty' => fn($query) => $query->select(['id', 'name', 'phone', 'email'])->with('avatar'),
                'category:id,name',
            ])
            ->findOrFail($activity);
    }

    public function similar($activity)
    {
        $activity = $this->activityModel::query()
            ->forClient()
            ->findOrFail($activity, ['id', 'category_id', 'city_id']);

        return $activity->similarActivities($this->baseIndex());
    }

    public function moreExperience($activity)
    {
        $activity = $this->activityModel::query()
            ->forClient()
            ->findOrFail($activity, ['id', 'category_id', 'city_id']);

        return $activity->moreExperience($this->baseIndex());
    }

    public function carousel()
    {
        return $this->activityModel::query()
            ->forClient()
            ->with('mainImage')
            ->select('id')
            ->where('include_in_carousel', true)
            ->paginatedCollection();
    }

    public function adrenalineRush()
    {
        return $this->activityModel::query()
            ->forClient()
            ->with('mainImage')
            ->select('id')
            ->where('include_in_adrenaline_rush', true)
            ->paginatedCollection();
    }
}
