<?php

namespace App\Traits;

trait RateEloquentTrait
{
    public function orderByRate()
    {
        $ratingOrderBy = request()->input('order_by_rating');

        if(! is_null($ratingOrderBy))
        {
            $desc = $ratingOrderBy == 'desc';

            return $this->orderBy('rating_average', $desc ? 'desc' : 'asc');
        }

        return $this;
    }
}
