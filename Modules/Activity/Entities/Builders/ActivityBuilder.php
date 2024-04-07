<?php

namespace Modules\Activity\Entities\Builders;

use App\Traits\RateEloquentTrait;
use Illuminate\Database\Eloquent\Builder;
use Modules\Activity\Traits\ActivityStatusEloquentTrait;
use Modules\Markable\Traits\Favorable;

class ActivityBuilder extends Builder
{
    use RateEloquentTrait, ActivityStatusEloquentTrait, Favorable;

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
}
