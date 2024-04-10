<?php

namespace Modules\Review\Traits;

trait ReviewEloquentTrait
{
    public function whereNotReviewed(): static
    {
        return $this->where('reviewed', false);
    }
}
