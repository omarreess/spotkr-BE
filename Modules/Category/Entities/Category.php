<?php

namespace Modules\Category\Entities;

use App\Helpers\MediaHelper;
use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Category\Entities\Builders\CategoryBuilder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $parent_id
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read Category|null $parentCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $subCategories
 * @property-read int|null $sub_categories_count
 *
 * @method static CategoryBuilder|Category formatResult()
 * @method static CategoryBuilder|Category newModelQuery()
 * @method static CategoryBuilder|Category newQuery()
 * @method static CategoryBuilder|Category paginatedCollection()
 * @method static CategoryBuilder|Category query()
 * @method static CategoryBuilder|Category searchByForeignKey(string $foreignKeyColumn, ?string $value = null)
 * @method static CategoryBuilder|Category searchable(array $columns = [], array $translatedKeys = [], string $handleKeyName = 'handle')
 * @method static CategoryBuilder|Category whereCreatedAt($value)
 * @method static CategoryBuilder|Category whereId($value)
 * @method static CategoryBuilder|Category whereIsNotParentSport()
 * @method static CategoryBuilder|Category whereIsParentSport()
 * @method static CategoryBuilder|Category whereName($value)
 * @method static CategoryBuilder|Category whereParentCategory()
 * @method static CategoryBuilder|Category whereParentId($value)
 * @method static CategoryBuilder|Category whereParentIsSport()
 * @method static CategoryBuilder|Category whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, PaginationTrait, Searchable;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function icon()
    {
        return MediaHelper::mediaRelationship($this, 'category_icon');
    }

    public function image()
    {
        return MediaHelper::mediaRelationship($this, 'category_image');
    }

    public function newEloquentBuilder($query): CategoryBuilder
    {
        return new CategoryBuilder($query);
    }
}
