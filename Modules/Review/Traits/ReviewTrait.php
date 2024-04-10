<?php

namespace Modules\Review\Traits;

trait ReviewTrait
{
    public function recalculateRating(): void
    {
        $this->forceFill([
            'rating_average' => $this->reviews()->avg('rating'),
        ])
            ->save();
    }

    public function storeReview(array $data): void
    {
        $this->reviews()->create($data);
    }
}
