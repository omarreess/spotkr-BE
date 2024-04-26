<?php

namespace Modules\Review\Services;

use Illuminate\Support\Facades\DB;
use Modules\Review\Contracts\ReviewableContract;

class ReviewService
{
    public function handle(array $data, ReviewableContract $model)
    {
        DB::transaction(function () use ($data, $model) {
            if (! isset($data['rating'])) {
                $data['rating'] = null;
            }

            if (! isset($data['description'])) {
                $data['description'] = null;
            }

            $model->reviews()->create($data);
            $model->recalculateRating();
        });
    }
}
