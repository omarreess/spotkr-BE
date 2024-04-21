<?php

namespace Modules\LeaderBoard\Traits;

use App\Models\User;

trait AchievementRelations
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
