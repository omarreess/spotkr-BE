<?php

namespace Modules\Activity\Entities;

use App\Casts\CoordinateCast;
use App\Casts\CourseBundleCast;
use App\Traits\PaginationTrait;
use App\Traits\Searchable;
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

class Activity extends Model implements HasMedia, ReviewableContract
{
    use HasFactory,
        PaginationTrait,
        Searchable,
        ActivityRelations,
        ReviewTrait,
        Markable,
        InteractsWithMedia;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model){
            if(! $model->third_party_id)
            {
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
}
