<?php

namespace Modules\Review\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait ReviewRelations
{
    public function reviewable(): MorphTo
    {
        return $this->morphTo('reviewable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
