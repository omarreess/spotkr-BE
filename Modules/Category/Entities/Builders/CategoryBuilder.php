<?php

namespace Modules\Category\Entities\Builders;

use Illuminate\Database\Eloquent\Builder;

class CategoryBuilder extends Builder
{
    public function whereIsParentSport(): CategoryBuilder
    {
        return $this->where('id', 4);
    }

    public function whereIsNotParentSport(): CategoryBuilder
    {
        return $this->where('id', '!=', 4);
    }

    public function whereParentCategory(): CategoryBuilder
    {
        return $this->whereNull('parent_id');
    }

    public function whereParentIsSport(): CategoryBuilder
    {
        return $this->where('parent_id', 4);
    }
}
