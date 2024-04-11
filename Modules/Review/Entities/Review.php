<?php

namespace Modules\Review\Entities;

use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Review\Database\factories\ReviewFactory;
use Modules\Review\Traits\ReviewRelations;

/**
 * Modules\Review\Entities\Review
 *
 * @property string $id
 * @property string $reviewable_type
 * @property string $reviewable_id
 * @property string $user_id
 * @property float $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $reviewable
 * @property-read \App\Models\User $user
 * @method static \Modules\Review\Database\factories\ReviewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Review formatResult()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review paginatedCollection()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review searchByForeignKey(string $foreignKeyColumn, ?string $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Review searchable(array $columns = [], array $translatedKeys = [], string $handleKeyName = 'handle')
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereReviewableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereReviewableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUserId($value)
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereDescription($value)
 * @mixin \Eloquent
 */
class Review extends Model
{
    use HasFactory,
        HasUuids,
        PaginationTrait,
        ReviewRelations,
        Searchable;

    protected $fillable = [
        'rating',
        'user_id',
        'reviewable_id',
        'reviewable_type',
        'description',
    ];

    protected $casts = [
        'rating' => 'float',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->user_id) {
                $model->user_id = auth()->id();
            }
        });
    }

    public static function newFactory(): ReviewFactory
    {
        return ReviewFactory::new();
    }
}
