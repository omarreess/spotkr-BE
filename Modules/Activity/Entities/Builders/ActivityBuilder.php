<?php

namespace Modules\Activity\Entities\Builders;

use App\Traits\RateEloquentTrait;
use Illuminate\Database\Eloquent\Builder;
use Modules\Activity\Enums\ActivityStatusEnum;
use Modules\Activity\Traits\ActivityStatusEloquentTrait;
use Modules\Markable\Traits\Favorable;

class ActivityBuilder extends Builder
{
    use ActivityStatusEloquentTrait, Favorable, RateEloquentTrait;

    public function forCurrentUser(): static
    {
        return $this->where('third_party_id', auth()->id());
    }

    public function onlyType(): static
    {
        $type = request()->input('type');

        if ($type) {
            $this->where('type', $type);
        }

        return $this;
    }

    public function whereValidForClient(): static
    {
        return $this->where('status', ActivityStatusEnum::ACCEPTED);
    }

    public function forClient()
    {
        return $this
            ->getFavorites()
            ->whereValidForClient();
    }

    public function similarCategories(bool $sameCategory, $id, $categoryParentId)
    {
        return $this
            ->forClient()
            ->inRandomOrder()
            ->whereHas(
                'category',
                fn (Builder $builder) => $builder
                    ->whereNotNull('parent_id')
                    ->where('parent_id', $sameCategory ? '=' : '<>', $categoryParentId)
            )
            ->where('id', '<>', $id);
    }
}
