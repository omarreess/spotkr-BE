<?php

namespace Modules\Activity\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\Activity\Entities\Activity;

class BaseActivityService
{
    protected Activity $activityModel;

    public function __construct(Activity $activityModel)
    {
        $this->activityModel = $activityModel;
    }

    protected function baseIndex()
    {
        return $this->activityModel::query()
            ->onlyType()
            ->searchable()
            ->searchByForeignKey('category_id', request()->input('category_id'))
            ->searchByForeignKey('city_id', request()->input('city_id'))
            ->searchByForeignKey('third_party_id', request()->input('third_party_id'))
            ->with([
                'city:id,name',
                'category:id,name',
                'mainImage',
            ])
            ->orderByRate()
            ->select([
                'id',
                'name',
                'type',
                'status',
                'rating_average',
                'address',
                'phone',
                'price',
                'discount',
                //                'open_times',
                'category_id',
                'city_id',
                'include_in_adrenaline_rush',
                'include_in_carousel',
                'third_party_id',
            ]);
    }

    protected function baseShow(): Builder
    {
        return $this->activityModel::query()
            ->with([
                'basicCity',
                'basicCategory',
                'mainImage',
                'otherImages',
            ]);
    }
}
