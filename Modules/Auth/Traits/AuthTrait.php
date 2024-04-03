<?php

namespace Modules\Auth\Traits;

use Modules\Auth\Helpers\UserHelper;

trait AuthTrait
{
    public function whereValidType(bool $inMobile)
    {
        return $this
            ->when(! $inMobile, fn ($query) => $query->whereIn('type', UserHelper::nonMobileTypes()))
            ->when($inMobile, fn ($query) => $query->whereNotIn('type', UserHelper::nonMobileTypes()));
    }
}
