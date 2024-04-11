<?php

namespace Modules\Markable\Entities;

use Modules\Markable\Abstracts\Mark;

/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $markable_type
 * @property string $markable_id
 * @property string|null $value
 * @property array|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $markable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereMarkableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereMarkableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereValue($value)
 * @mixin \Eloquent
 */
class Favorite extends Mark
{
    public static function markableRelationName(): string
    {
        return 'favoriters';
    }
}
