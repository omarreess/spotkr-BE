<?php

namespace Modules\Auth\Traits;

use App\Helpers\MediaHelper;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Auth\Enums\AuthEnum;
use Modules\LeaderBoard\Entities\Achievement;
use Modules\Order\Entities\Order;

trait UserRelations
{
    public function avatar()
    {
        return MediaHelper::mediaRelationship($this, AuthEnum::AVATAR_COLLECTION_NAME);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
