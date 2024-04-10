<?php

namespace Modules\Review\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface ReviewableContract
{
    public function reviews(): MorphMany;
}
