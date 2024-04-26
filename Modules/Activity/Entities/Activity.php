<?php

namespace Modules\Activity\Entities;

use App\Casts\CoordinateCast;
use App\Casts\CourseBundleCast;
use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Activity\Entities\Builders\ActivityBuilder;
use Modules\Activity\Http\Controllers\ThirdPartyActivityController;
use Modules\Activity\Traits\ActivityRelations;
use Modules\Markable\Entities\FavoriteModel;
use Modules\Markable\Traits\Markable;
use Modules\Review\Contracts\ReviewableContract;
use Modules\Review\Traits\ReviewTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $name
 * @property float|null $price
 * @property float|null $discount percentage
 * @property string $description
 * @property int $third_party_id
 * @property int $city_id
 * @property int $category_id
 * @property string|null $phone
 * @property string|null $fax
 * @property string|null $email
 * @property string|null $website
 * @property string $type
 * @property float $rating_average
 * @property string $status
 * @property string|null $hold_at
 * @property mixed|null $address
 * @property array|null $open_times
 * @property array $features array of strings
 * @property array|null $social_links
 * @property mixed|null $course_bundles
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $include_in_carousel
 * @property bool $include_in_adrenaline_rush
 * @property-read \Modules\Category\Entities\Category $basicCategory
 * @property-read \Modules\Country\Entities\City $basicCity
 * @property-read \Modules\Category\Entities\Category $category
 * @property-read \Modules\Country\Entities\City $city
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Review\Entities\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \App\Models\User $thirdParty
 *
 * @method static ActivityBuilder|Activity forClient()
 * @method static ActivityBuilder|Activity forCurrentUser()
 * @method static ActivityBuilder|Activity formatResult()
 * @method static ActivityBuilder|Activity getFavorites()
 * @method static ActivityBuilder|Activity newModelQuery()
 * @method static ActivityBuilder|Activity newQuery()
 * @method static ActivityBuilder|Activity onlyType()
 * @method static ActivityBuilder|Activity orderByRate()
 * @method static ActivityBuilder|Activity paginatedCollection()
 * @method static ActivityBuilder|Activity query()
 * @method static ActivityBuilder|Activity searchByForeignKey(string $foreignKeyColumn, ?string $value = null)
 * @method static ActivityBuilder|Activity searchable(array $columns = [], array $translatedKeys = [], string $handleKeyName = 'handle')
 * @method static ActivityBuilder|Activity similarCategories(bool $sameCategory, $id, $categoryParentId)
 * @method static ActivityBuilder|Activity whereAddress($value)
 * @method static ActivityBuilder|Activity whereApproved()
 * @method static ActivityBuilder|Activity whereCategoryId($value)
 * @method static ActivityBuilder|Activity whereCityId($value)
 * @method static ActivityBuilder|Activity whereCourseBundles($value)
 * @method static ActivityBuilder|Activity whereCreatedAt($value)
 * @method static ActivityBuilder|Activity whereDescription($value)
 * @method static ActivityBuilder|Activity whereDiscount($value)
 * @method static ActivityBuilder|Activity whereEmail($value)
 * @method static ActivityBuilder|Activity whereFax($value)
 * @method static ActivityBuilder|Activity whereFeatures($value)
 * @method static ActivityBuilder|Activity whereHasFavorites()
 * @method static ActivityBuilder|Activity whereHasMark(\Modules\Markable\Abstracts\Mark $mark, \Illuminate\Database\Eloquent\Model $user, ?string $value = null)
 * @method static ActivityBuilder|Activity whereHoldAt($value)
 * @method static ActivityBuilder|Activity whereId($value)
 * @method static ActivityBuilder|Activity whereIncludeInAdrenalineRush($value)
 * @method static ActivityBuilder|Activity whereIncludeInCarousel($value)
 * @method static ActivityBuilder|Activity whereName($value)
 * @method static ActivityBuilder|Activity whereOpenTimes($value)
 * @method static ActivityBuilder|Activity wherePending()
 * @method static ActivityBuilder|Activity wherePhone($value)
 * @method static ActivityBuilder|Activity wherePrice($value)
 * @method static ActivityBuilder|Activity whereRatingAverage($value)
 * @method static ActivityBuilder|Activity whereRejected()
 * @method static ActivityBuilder|Activity whereSocialLinks($value)
 * @method static ActivityBuilder|Activity whereStatus($value)
 * @method static ActivityBuilder|Activity whereThirdPartyId($value)
 * @method static ActivityBuilder|Activity whereType($value)
 * @method static ActivityBuilder|Activity whereUpdatedAt($value)
 * @method static ActivityBuilder|Activity whereValidForClient()
 * @method static ActivityBuilder|Activity whereWebsite($value)
 * @method static ActivityBuilder|Activity withFavorites()
 *
 * @mixin \Eloquent
 */
class Activity extends Model implements HasMedia, ReviewableContract
{
    use ActivityRelations,
        HasFactory,
        InteractsWithMedia,
        Markable,
        PaginationTrait,
        ReviewTrait,
        Searchable;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->third_party_id) {
                $model->third_party_id = auth()->id();
            }
        });
    }

    protected static $marks = [
        FavoriteModel::class,
    ];

    protected $fillable = [
        'name',
        'price',
        'discount',
        'type',
        'description',
        'address',
        'phone',
        'fax',
        'email',
        'website',
        'third_party_id',
        'category_id',
        'city_id',
        'rating_average',
        'status',
        'hold_at',
        'open_times',
        'features',
        'course_bundles',
        'social_links',
        'include_in_carousel',
        'include_in_adrenaline_rush',
    ];

    protected $casts = [
        'address' => CoordinateCast::class,
        'course_bundles' => CourseBundleCast::class,
        'open_times' => 'array',
        'features' => 'array',
        'social_links' => 'array',
        'discount' => 'double',
        'price' => 'double',
        'rating_average' => 'double',
        'include_in_carousel' => 'boolean',
        'include_in_adrenaline_rush' => 'boolean',
    ];

    public function newEloquentBuilder($query): ActivityBuilder
    {
        return new ActivityBuilder($query);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection(ThirdPartyActivityController::MAIN_IMAGE_COLLECTION_NAME)
            ->singleFile();
    }

    public function resetOtherImageCollection(): void
    {
        $this
            ->addMediaCollection(ThirdPartyActivityController::OTHER_IMAGES_COLLECTION_NAME)
            ->singleFile();
    }

    public function similarActivities(Builder $baseBuilder)
    {
        return $this->baseCities($baseBuilder);
    }

    public function moreExperience(Builder $baseBuilder)
    {
        return $this->baseCities($baseBuilder, false);
    }

    private function baseCities(Builder $baseBuilder, bool $sameCategory = true)
    {
        $currentCategory = $this->category;

        $sameCountry = $baseBuilder->clone()
            ->where('city_id', $this->city_id)
            ->similarCategories($sameCategory, $this->id, $currentCategory->parent_id)
            ->limit(4);

        $differenceCountry = $baseBuilder->clone()
            ->where('city_id', '<>', $this->city_id)
            ->similarCategories($sameCategory, $this->id, $currentCategory->parent_id)

            ->limit(2);

        return $sameCountry->union($differenceCountry)->get();
    }
}
