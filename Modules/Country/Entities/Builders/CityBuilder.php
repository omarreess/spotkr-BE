<?php

namespace Modules\Country\Entities\Builders;

use Illuminate\Database\Eloquent\Builder;

class CityBuilder extends Builder
{
    public function whereBelongsToEgypt(): static
    {
        return $this->where('country_id', 42);
    }
}
