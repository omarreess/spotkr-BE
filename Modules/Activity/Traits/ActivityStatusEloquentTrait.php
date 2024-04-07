<?php

namespace Modules\Activity\Traits;

use Modules\Activity\Entities\Builders\ActivityBuilder;
use Modules\Activity\Enums\ActivityStatusEnum;

trait ActivityStatusEloquentTrait
{
    public function wherePending(): ActivityBuilder
    {
        return $this->where('status', ActivityStatusEnum::PENDING);
    }

    public function whereApproved(): ActivityBuilder
    {
        return $this->where('status', ActivityStatusEnum::ACCEPTED);
    }

    public function whereRejected(): ActivityBuilder
    {
        return $this->where('status', ActivityStatusEnum::REJECTED);
    }
}
