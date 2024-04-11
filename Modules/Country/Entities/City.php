<?php

namespace Modules\Country\Entities;

use App\Helpers\MediaHelper;
use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Country\Entities\Builders\CityBuilder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Country\Entities\Country $country
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static CityBuilder|City formatResult()
 * @method static CityBuilder|City newModelQuery()
 * @method static CityBuilder|City newQuery()
 * @method static CityBuilder|City paginatedCollection()
 * @method static CityBuilder|City query()
 * @method static CityBuilder|City searchByForeignKey(string $foreignKeyColumn, ?string $value = null)
 * @method static CityBuilder|City searchable(array $columns = [], array $translatedKeys = [], string $handleKeyName = 'handle')
 * @method static CityBuilder|City whereBelongsToEgypt()
 * @method static CityBuilder|City whereCountryId($value)
 * @method static CityBuilder|City whereCreatedAt($value)
 * @method static CityBuilder|City whereId($value)
 * @method static CityBuilder|City whereName($value)
 * @method static CityBuilder|City whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class City extends Model implements HasMedia
{
    use HasFactory, PaginationTrait, Searchable, InteractsWithMedia;

    protected $fillable = [
        'name',
        'country_id'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function image()
    {
        return MediaHelper::mediaRelationship($this, 'city_image');
    }

    public function newEloquentBuilder($query): CityBuilder
    {
        return new CityBuilder($query);
    }
}
