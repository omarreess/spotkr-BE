<?php

namespace Modules\Review\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Review\Entities\Review;

trait ReviewRelation
{
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
