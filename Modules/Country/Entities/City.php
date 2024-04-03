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
